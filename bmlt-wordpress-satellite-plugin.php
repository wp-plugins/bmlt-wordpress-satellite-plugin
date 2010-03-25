<?php
/**
	\file	bmlt_plugin.php
	
	\brief	This is a simple WordPress plugin of a BMLT satellite client.
	
Plugin Name: BMLT Satellite Server
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin implementation of the Basic Meeting List Toolbox.
This will replace the "&lt;!--BMLT--&gt;" in the content with the BMLT search.
If you place that in any part of a page (not a post), the page will contain a BMLT satellite server.
Version: 1.4.4
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
You need to specify "<!--BMLT-->" in the code section of a page (Will not work in a post).
*/ 

/**
	\class BMLTPlugin
	
	\brief This is the class that implements and encapsulates the plugin functionality. A single instance of this is created, and manages the plugin.
*/

require_once ( dirname ( __FILE__ ).'/xml_utils.inc' );
class BMLTPlugin
	{
	var $adminOptionsName = "BMLTAdminOptions";							///< The name, in the database, for the options for this plugin.
	var	$default_rootserver = 'http://bmlt.magnaws.com/';				///< This is the default root BMLT server URI.
	var $default_gkey = 'ABQIAAAABCC8PsaKPPEsC3k649kYPRSz44RiWJ5-5P3kVwShr4yWZ40gchTIeC9GanTylZSXXJOwTtGbQEsZKA';	///< This is the default Google Maps API key (localhost).
	var $default_bmlt_fullscreen = false;								///< This is the default value for the "Map Search Is Full Screen."
	var $default_map_center_latitude = 29.764377375163125;				///< This is the default basic search map center latitude
	var $default_map_center_longitude = -95.4931640625;					///< This is the default basic search map center longitude
	var $default_map_zoom = 9;											///< This is the default basic search map zoom level
	var $default_language = 'en';										///< This is the default language for the server.
	var $default_support_old_browsers = true;							///< If this is false, then only JavaScript-enabled browsers can use this (reduces the load time of pages). Default is true.
	var $default_initial_view = '';										///< This is the initial view when the search first appears. Default is whatever the root server decides.
	var	$default_new_search = null;										///< If this is set to something, then a new search uses the exact URI.
	var	$default_sb_array = array();									///< This may be a list of "pre-checked" Service bodies.
	var $default_push_down_more_details = 0;							///< This is a flag that indicates whether or not to "push down" the map or list to make room for the "More Details" window.
	var $default_additional_css = null;									///< The admin can add arbitrary CSS here.
	
	/// These items affect the options page in the dashboard. You can change these to alter the displayed strings.
	var $options_title = 'Basic Meeting List Toolbox Options';			///< This is the title that is displayed over the options.
	var $rootserver_label = 'Root BMLT Server:';						///< This is the prompt/label for the root server text item.
	var $gkey_label = 'Google Maps API Key:';							///< This is the prompt/label for the Google Maps API key text item.
	var $bmlt_fullscreen_label = 'Map Search Covers the Full Page';
	var $bmlt_map_label = 'Select the Center Point and Zoom for the Map';
	var $options_submit_button = 'Set These Values';
	var $menu_new_search_text = 'New Search';
	var $language_menu_prompt = 'Select a Language:';
	var $support_old_browsers_prompt = 'Support Non-JavaScript Browsers';
	var $no_js_warning = '<noscript class="no_js">This Meeting Search will not work because your browser does not support JavaScript. However, you can use the <a href="###ROOT_SERVER###">main server</a>.</noscript>';
	var $initial_view = array ( 'values' => array ( '', 'map', 'text', 'advanced' ), 'prompts' => array ( 'Root Server Decides', 'Map', 'Text', 'Advanced' ) );
	var $initial_view_prompt = 'Initial Search Type:';
	var $new_search_label = 'Specific URL For a New Search:';
	var $new_search_suffix = ' (Leave blank for automatic)';
	var $service_body_checkboxes_label = 'If you want the Advanced Search Tab to have one or more Service bodies checked, then select them here:';
	var $push_down_checkbox_label = '&quot;More Details&quot; Windows &quot;push down&quot; the main list or map, as opposed to popping up over them.';
	var $more_styles_label = 'Add CSS Styles to the Plugin:';
	
	/// This is returned in an exception if the cURL call fails.
	static $static_uri_failed = 'Call to remote server failed';
	
	/// This is used to display the menu
	var $menu_string = 'BMLT Options';	///< The name of the menu item.
	
	/// This is the original query.
	var $my_http_vars = null;
	
	/// This is the original query, repackaged for use.
	var $my_params = null;

	/**
		\brief This is called before anything else. Its principal purpose is to intercept AJAX redirects, and return the results with no overhead.
	*/
	function init ( )
		{
		$this->my_http_vars = array_merge_recursive ( $_GET, $_POST );
		$options = $this->getAdminOptions ( );
			
		if ( !(isset ( $this->my_http_vars['search_form'] ) && $this->my_http_vars['search_form'] )
			&& !(isset ( $this->my_http_vars['do_search'] ) && $this->my_http_vars['do_search'] ) 
			&& !(isset ( $this->my_http_vars['single_meeting_id'] ) && $this->my_http_vars['single_meeting_id'] ) 
			)
			{
			$this->my_http_vars['search_form'] = true;
			}
		
		$this->my_http_vars['script_name'] = preg_replace ( '|(.*?)\?.*|', "$1", $_SERVER['REQUEST_URI'] );
		$this->my_http_vars['gmap_key'] = $options['gmaps_api_key'];
		$this->my_http_vars['satellite'] = $this->my_http_vars['script_name'];
		$this->my_http_vars['lang_enum'] = $options['bmlt_language'];
		$this->my_http_vars['start_view'] = $options['bmlt_initial_view'];
		
		if ( !$options['support_old_browsers'] )
			{
			$this->my_http_vars['supports_ajax'] = 'yes';
			$this->my_http_vars['no_ajax_check'] = 'yes';
			}
		else
			{
			unset ( $this->my_http_vars['no_ajax_check'] );
			}

		$this->my_params = '';
			
		foreach ( $this->my_http_vars as $key => $value )
			{
			if ( $key != 'switcher' )	// We don't propagate switcher.
				{
				if ( is_array ( $value ) )
					{
					foreach ( $value as $val )
						{
						if ( is_array ( $val ) )
							{
							$val = join ( ',', $val );
							}
						$this->my_params .= '&'.urlencode ( $key ) ."[]=". urlencode ( $val );
						}
					$key = null;
					}
				if ( $key )
					{
					$this->my_params .= '&'.urlencode ( $key );
					
					if ( $value )
						{
						$this->my_params .= "=". urlencode ( $value );
						}
					}
				}
			}
		
		if ( isset ( $this->my_http_vars['redirect_ajax'] ) && $this->my_http_vars['redirect_ajax'] )
			{
			$root_server = $options['root_server']."client_interface/xhtml/index.php";
			die ( self::call_curl ( "$root_server?switcher=RedirectAJAX".$this->my_params, false ) );
			}
				
		if ( isset ( $this->my_http_vars['result_type_advanced'] ) && ($this->my_http_vars['result_type_advanced'] == 'booklet') )
			{
			$uri =  $options['root_server']."local_server/pdf_generator/?list_type=booklet$this->my_params";
			header ( "Location: $uri" );
			die();
			}
		elseif ( isset ( $this->my_http_vars['result_type_advanced'] ) && ($this->my_http_vars['result_type_advanced'] == 'listprint') )
			{
			$uri =  $options['root_server']."local_server/pdf_generator/?list_type=listprint$this->my_params";
			header ( "Location: $uri" );
			die();
			}
		}
	
	/**
		\brief see if we are dealing with a mobile browser that uses a small screen and limited bandwidth.
		
		\returns a Boolean. True if the browser is one that should get the special version of our site.
	*/
	function _mobileBrowser ( )
		{
		$ret = isset ( $this->my_http_vars['simulate_iphone'] ) || preg_match ( '/ipod/i', $_SERVER['HTTP_USER_AGENT'] ) || preg_match ( '/iphone/i', $_SERVER['HTTP_USER_AGENT'] );
		
		if ( !$ret )
			{
			$ret = isset ( $this->my_http_vars['simulate_android'] ) || preg_match ( '/android/i', $_SERVER['HTTP_USER_AGENT'] );
			}

		if ( !$ret )
			{
			$ret = isset ( $this->my_http_vars['simulate_blackberry'] ) || preg_match ( '/blackberry/i', $_SERVER['HTTP_USER_AGENT'] );
			}
	
		if ( !$ret )
			{
			$ret = isset ( $this->my_http_vars['simulate_opera_mini'] ) || preg_match ( "/opera\s+mini/i", $_SERVER['HTTP_USER_AGENT'] );
			}
		
		return $ret;
		}

	/**
		\brief This echoes the appropriate head element stuff for this plugin.
	*/
	function head ( )
		{
		$options = $this->getAdminOptions ( );
		$root_server_root = $options['root_server'];
		
		if ( $root_server_root )
			{
			global $wp_query;
			$page_obj_id = $wp_query->get_queried_object_id();
			if ( $page_obj_id )
				{
				$page_obj = get_page ( $page_obj_id );
				if ( $page_obj && preg_match ( "/<!-- ?BMLT ?-->/", $page_obj->post_content ) )
					{
					// Mobile browsers get redirected to the root server.
					if ( $this->_mobileBrowser() )
						{
						header ( 'Location: '.$root_server_root );
						}
			
					$root_server = $root_server_root."client_interface/xhtml/index.php";
					
					echo "<!-- Added by the BMLT plugin. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
					echo self::call_curl ( "$root_server?switcher=GetHeaderXHTML".$this->my_params );
					echo '<link rel="stylesheet" href="'.get_option('siteurl').'/wp-content/plugins/bmlt-wordpress-satellite-plugin/styles.css" type="text/css" />';
					$additional_css = trim ( $options['additional_css'] );
					if ( $options['push_down_more_details'] )
						{
						$additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_ajax_div{position:static;margin:0;width:100%;}';
						$additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_close_box_div{position:relative;left:100%;margin-left:-18px;}';
						$additional_css .= 'table#bmlt_container div#bmlt_contact_us_form_div{position:static;width:auto;margin:0;}';
						}
					
					if ( $additional_css )
						{
						echo '<style type="text/css">'.preg_replace ( "|\s+|", " ", $additional_css ).'</style>';
						}
					}
				}
			}
		}

	/**
		\brief Is the "meat" of the filter. It processes the content. If the page contains, anywhere, the "trigger" string ("<!--BMLT-->"), then the entire page is replaced with the BMLT.
		
		\returns an XHTML string, containing the processed page content.
	*/
	function content_filter ( $the_content )
		{
		$options = $this->getAdminOptions ( );
		$root_server_root = $options['root_server'];
		
		if ( $root_server_root )
			{
			$root_server = $root_server_root."client_interface/xhtml/index.php";
				
			if ( preg_match ( "/<!-- ?BMLT ?-->/", $the_content) && is_page() )
				{
				$pid = get_page_uri(get_the_ID());
				
				$plink = get_permalink ( get_the_ID() );
				
				$menu = '';
				
				if ( $pid && !isset ( $this->my_http_vars['search_form'] ) )
					{
					if ( $options['bmlt_new_search_url'] )
						{
						$plink = $options['bmlt_new_search_url'];
						}
					$menu = '<div class="bmlt_menu_div no_print"><a href="'.htmlspecialchars($plink).'">'.htmlspecialchars($this->menu_new_search_text).'</a></div>';
					}
				
				if ( isset ( $this->my_http_vars['search_form'] ) )
					{
					$pre_checked_param = null;
					$pre_checked = $options['bmlt_service_body_filters'];
					
					if ( is_array ( $pre_checked ) && count ( $pre_checked ) )
						{
						foreach ( $pre_checked as $id )
							{
							$pre_checked_param .= "&preset_service_bodies[]=$id";
							}
						}
					
					$map_center = "&search_spec_map_center=".$options['map_center_latitude'].",".$options['map_center_longitude'].",".$options['map_zoom'];
					$uri = "$root_server?switcher=GetSimpleSearchForm$this->my_params$map_center$pre_checked_param";
					$the_new_content = self::call_curl ( $uri );
					}
				elseif ( isset ( $this->my_http_vars['single_meeting_id'] ) && $this->my_http_vars['single_meeting_id'] )
					{
					$the_new_content = self::call_curl ( "$root_server?switcher=GetOneMeeting".$this->my_params );
					}
				elseif ( isset ( $this->my_http_vars['do_search'] ) )
					{
					$uri = "$root_server?switcher=GetSearchResults".$this->my_params;
					$the_new_content = self::call_curl ( $uri );
					}
				
				if ( !$options['support_old_browsers'] )
					{
					$the_new_content = str_replace ( '###ROOT_SERVER###', $root_server_root, $this->no_js_warning ).$the_new_content;
					}
				
				$the_new_content = '<table id="bmlt_container" class="bmlt_container_table"><tbody><tr><td>'.$menu.'<div class="bmlt_content_div">'.$the_new_content.'</div>'.$menu.'</td></tr></tbody></table>';
				}
			}
		
		return preg_replace ( "|(\<p[^>]*?>)?\<\!\-\-BMLT\-\-\>(\<\/p[^>]*?>)?|", $the_new_content, $the_content );
		}
	
	/**
		\brief This gets the admin options from the database.
	*/
	function getAdminOptions ( )
		{
		$BMLTOptions = array (	'root_server' => $this->default_rootserver,
								'gmaps_api_key' => $this->default_gkey,
								'bmlt_fullscreen' => $this->default_bmlt_fullscreen,
								'map_center_latitude' => $this->default_map_center_latitude,
								'map_center_longitude' => $this->default_map_center_longitude,
								'map_zoom' => $this->default_map_zoom,
								'bmlt_language' => $this->default_language,
								'support_old_browsers' => $this->default_support_old_browsers,
								'bmlt_initial_view' => $this->default_initial_view,
								'bmlt_new_search_url' => $this->default_new_search,
								'bmlt_service_body_filters' => $this->default_sb_array,
								'push_down_more_details' => $this->default_push_down_more_details,
								'additional_css' => $this->default_additional_css
								);

		$old_BMLTOptions = get_option ( $this->adminOptionsName );
		
		if ( is_array ( $old_BMLTOptions ) && count ( $old_BMLTOptions ) )
			{
	 		foreach ( $old_BMLTOptions as $key => $value )
				{
		  		$BMLTOptions[$key] = $value;
				}
			}

		update_option ( $this->adminOptionsName, $BMLTOptions );

		return $BMLTOptions;
		}

	/**
		\brief This echoes the admin page.
	*/
	function printAdminPage ( )
		{
		$BMLTOptions = $this->getAdminOptions();
						
		if ( isset ( $this->my_http_vars['update_BMLTSettings'] ) )
			{
			if ( !isset ( $this->my_http_vars['push_down_more_details'] ) )
				{
				$this->my_http_vars['push_down_more_details'] = $this->default_push_down_more_details;
				}
			if ( !isset ( $this->my_http_vars['additional_css'] ) )
				{
				$this->my_http_vars['additional_css'] = $this->default_additional_css;
				}
			
			$BMLTOptions['push_down_more_details'] = $this->my_http_vars['push_down_more_details'];
			$BMLTOptions['additional_css'] = $this->my_http_vars['additional_css'];

			if ( isset($this->my_http_vars['root_server']))
				{
				$BMLTOptions['root_server'] = $this->my_http_vars['root_server'];
				}	
			if ( isset($this->my_http_vars['gmaps_api_key']))
				{
				$BMLTOptions['gmaps_api_key'] = $this->my_http_vars['gmaps_api_key'];
				}	
			if ( isset($this->my_http_vars['map_center_latitude']))
				{
				$BMLTOptions['map_center_latitude'] = $this->my_http_vars['map_center_latitude'];
				}	
			if ( isset($this->my_http_vars['map_center_longitude']))
				{
				$BMLTOptions['map_center_longitude'] = $this->my_http_vars['map_center_longitude'];
				}	
			if ( isset($this->my_http_vars['map_zoom']) )
				{
				$BMLTOptions['map_zoom'] = $this->my_http_vars['map_zoom'];
				}	
			if ( isset($this->my_http_vars['bmlt_language']) )
				{
				$BMLTOptions['bmlt_language'] = $this->my_http_vars['bmlt_language'];
				}
			if ( isset($this->my_http_vars['bmlt_new_search_url']) )
				{
				$BMLTOptions['bmlt_new_search_url'] = $this->my_http_vars['bmlt_new_search_url'];
				}
			else
				{
				$BMLTOptions['bmlt_new_search_url'] = null;
				}
			
			if ( is_array($this->my_http_vars['bmlt_service_body_filters']) )
				{
				$BMLTOptions['bmlt_service_body_filters'] = $this->my_http_vars['bmlt_service_body_filters'];
				}
			else
				{
				$BMLTOptions['bmlt_service_body_filters'] = array();
				}
			
			if ( isset($this->my_http_vars['support_old_browsers']) )
				{
				$BMLTOptions['support_old_browsers'] = $this->my_http_vars['support_old_browsers'];
				}
			else
				{
				$BMLTOptions['support_old_browsers'] = false;
				}
			if ( isset($this->my_http_vars['bmlt_initial_view']) )
				{
				$BMLTOptions['bmlt_initial_view'] = $this->my_http_vars['bmlt_initial_view'];
				}
			
			if ( !isset ( $BMLTOptions['bmlt_initial_view'] ) || !$BMLTOptions['bmlt_initial_view'] )
				{
				$BMLTOptions['bmlt_initial_view'] = $this->default_initial_view;
				}
			
			$lang_popup = '';
			
			// We get the list of languages supported by the server, and present them as a popup. If there is no valid server, or there is only one language, the popup is not shown.
			if ( isset($BMLTOptions['root_server']) && $BMLTOptions['root_server'] )
				{
				try	// If the server URI is not pointing to a valid server, then we'll likely blow the connection.
					{
					$root_server = $BMLTOptions['root_server']."client_interface/xhtml/index.php";
					$uri = "$root_server?switcher=GetServerLanguages";
					$lang_list = self::call_curl ( $uri );
					if ( $lang_list )
						{
						$lang_array = split ( '","', $lang_list );
						// We only bother if there is more than one language. The array has to be even.
						if ( (count ( $lang_array ) > 2) && !(count ( $lang_array ) % 2) )
							{
							$menu_array = array();
							// Now, we make the array associative, with even elements the key, and odd elements the value.
							for ( $count = 0; $count < count ( $lang_array ); )
								{
								$key = htmlspecialchars ( trim ( $lang_array[$count++], '"' ) );
								$value = htmlspecialchars ( trim ( $lang_array[$count++], '"' ) );
								$menu_array[$key] = $value;
								}
							
							// Make sure that our currently selected language exists in the array. If not, we go to default.
							if ( !array_key_exists ( $BMLTOptions['bmlt_language'], $menu_array ) )
								{
								$BMLTOptions['bmlt_language'] = $this->default_language;
								}
							
							// This shouldn't happen, but it might, if this is being installed for a foreign server.
							if ( !array_key_exists ( $BMLTOptions['bmlt_language'], $menu_array ) )
								{
								$BMLTOptions['bmlt_language'] = 'en';
								}
							
							// OK. We're ready to create the popup.
							$lang_popup = '<div class="bmlt_options_line" style="clear:left">';
							$lang_popup .= '<label class="bmlt_options_label" for="bmlt_language" style="font-weight:bold;text-align:right;display:block;float:left;width:300px">';
							$lang_popup .= __ ( $this->language_menu_prompt, "BMLTPlugin" );
							$lang_popup .= '</label>';
							$lang_popup .= '<select name="bmlt_language" id="bmlt_language">';
							foreach ( $menu_array as $key => $value )
								{
								$lang_popup .= '<option value="'.$key.'"';
								if ( $BMLTOptions['bmlt_language'] == $key )
									{
									$lang_popup .= ' selected="selected"';
									}
								
								$lang_popup .= ">$value</option>";
								}
							$lang_popup .= '</select>';
							$lang_popup .= '</div>';
							}
						}
					}
				catch ( Exception $e )
					{
					}
				}

			update_option ( $this->adminOptionsName, $BMLTOptions );
			
			echo '<div class="updated">';
				_e ( "Settings Updated.", "BMLTPlugin" );
			echo '</div>';
			}
		
		$google_include = "http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$BMLTOptions['gmaps_api_key'];
		?>
		
		<div class="wrap" style="text-align:center">
			<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<input type="hidden" id="bmlt_map_center_latitude" name="map_center_latitude" value="<?php echo $BMLTOptions['map_center_latitude'] ?>" />
				<input type="hidden" id="bmlt_map_center_longitude" name="map_center_longitude" value="<?php echo $BMLTOptions['map_center_longitude'] ?>" />
				<input type="hidden" id="bmlt_map_zoom" name="map_zoom" value="<?php echo $BMLTOptions['map_zoom'] ?>" />
				<div class="bmlt_options_div" style="text-align:left;margin-left:auto;margin-right:auto">
					<h2 style="margin-left:300px"><?php echo $this->options_title ?></h2>
					<div class="bmlt_text_items_div" style="float:left;margin-left:auto;margin-right:auto">
						<div class="bmlt_options_line" style="text-align:center">
							<input type="submit" name="update_BMLTSettings" style="font-size:small" value="<?php _e ( $this->options_submit_button, "BMLTPlugin" ) ?>" />
						</div>
						<div class="bmlt_options_line" style="margin-top:12px;clear:both">
							<label class="bmlt_options_label" for="bmlt_initial_view" style="font-weight:bold;text-align:right;display:block;float:left;width:300px"><?php _e ( $this->initial_view_prompt, "BMLTPlugin" ) ?></label>
							<select style="margin-left:4px;text-align:left" id="bmlt_initial_view" name="bmlt_initial_view">
							<?php
								$values = $this->initial_view['values'];
								$prompts = $this->initial_view['prompts'];
								
								for ( $c = 0; $c < count ( $prompts ); $c++ )
									{
									echo '<option value="'.htmlspecialchars ( $values[$c] ).'"';
									if ( $values[$c] == $BMLTOptions['bmlt_initial_view'] )
										{
										echo ' selected="selected"';
										}
									echo '>'.htmlspecialchars ( $prompts[$c] ).'</option>';
									}
							?>
							</select>
						</div>
						<div class="bmlt_options_line" style="float:left">
							<label class="bmlt_options_label" for="bmlt_root_server" style="font-weight:bold;text-align:right;display:block;float:left;width:300px"><?php _e ( $this->rootserver_label, "BMLTPlugin" ) ?></label>
							<input style="margin-left:4px;width:50em;font-size:small" class="bmlt_options_text" name="root_server" id="bmlt_root_server" type="text" value="<?php echo $BMLTOptions['root_server'] ?>" />
						</div>
						<div class="bmlt_options_line" style="clear:left">
							<label class="bmlt_options_label" for="bmlt_gmaps_api_key" style="font-weight:bold;text-align:right;display:block;float:left;width:300px"><?php _e ( $this->gkey_label, "BMLTPlugin" ) ?></label>
							<input style="margin-left:4px;width: 50em;font-size:small" class="bmlt_options_text" name="gmaps_api_key" id="bmlt_gmaps_api_key" type="text" value="<?php echo $BMLTOptions['gmaps_api_key'] ?>" />
						</div>
						<div class="bmlt_options_line" style="clear:left">
							<label class="bmlt_options_label" for="bmlt_new_search_url" style="font-weight:bold;text-align:right;display:block;float:left;width:300px"><?php _e ( $this->new_search_label, "BMLTPlugin" ) ?></label>
							<input style="margin-left:4px;width: 50em;font-size:small" class="bmlt_options_text" name="bmlt_new_search_url" id="bmlt_new_search_url" type="text" value="<?php echo $BMLTOptions['bmlt_new_search_url'] ?>" /><?php _e ( $this->new_search_suffix, "BMLTPlugin" ) ?>
						</div>
						<div class="bmlt_options_line" style="clear:left">
							<?php echo '<input style="float:left;margin-right:4px;margin-left:300px" class="bmlt_options_text" name="support_old_browsers" id="bmlt_support_old_browsers_check" type="checkbox" value="1"'.($BMLTOptions['support_old_browsers'] ? ' checked="checked"' : '').' />'; ?>
							<label class="bmlt_options_label" for="bmlt_support_old_browsers_check" style="font-weight:bold;text-align:left;display:block"><?php _e ( $this->support_old_browsers_prompt, "BMLTPlugin" ) ?></label>
						</div>
						<div class="bmlt_options_line" style="clear:left">
							<?php echo '<input style="float:left;margin-right:4px;margin-left:300px" class="bmlt_options_text" name="push_down_more_details" id="bmlt_push_down_more_details_check" type="checkbox" value="1"'.($BMLTOptions['push_down_more_details'] ? ' checked="checked"' : '').' />'; ?>
							<label class="bmlt_options_label" for="bmlt_push_down_more_details_check" style="font-weight:bold;text-align:left;display:block"><?php _e ( $this->push_down_checkbox_label, "BMLTPlugin" ) ?></label>
						</div>
						<div class="bmlt_options_line" style="clear:left">
							<label class="bmlt_options_label" for="bmlt_additional_css" style="font-weight:bold;text-align:right;display:block;float:left;width:300px"><?php _e ( $this->more_styles_label, "BMLTPlugin" ) ?></label>
							<textarea style="font-size:small" cols="100" rows="20" name="additional_css" id="bmlt_additional_css"><?php echo _e ( $BMLTOptions['additional_css'], "BMLTPlugin" ) ?></textarea>
						</div>
						<?php echo $this->create_sb_checkboxes() ?>
						<?php echo $lang_popup ?>
						<div class="bmlt_options_line" style="margin-left:300px;margin-top:12px;clear:left;text-align:center;">
							<h3><?php _e ( $this->bmlt_map_label, "BMLTPlugin" ) ?></h3>
							<div id="meeting_map" style="width:700px;height:700px;border:1px solid black"></div>
						</div>
					</div>
				</div>
			</form>
			<script src="<?php echo $google_include ?>" type="text/javascript"></script>
			<script type="text/javascript">
			/* <![CDATA[ */
				var g_geocoder_browser_set_center_map = null;
				
				/*******************************************************************/
				/** \class	c_geocoder_browser_set_center
				
					\brief	This is a special JavaScript Class that manages a Google Map.
				*/

				/** These are the various class data members. */
				c_geocoder_browser_set_center.prototype.point = null;		/**< The current GLatLng for the map marker */
				c_geocoder_browser_set_center.prototype.map = null;			/**< The Google Maps instance */
				c_geocoder_browser_set_center.prototype.marker = null;		/**< The marker instance */
				
				/*******************************************************************/
				/** \brief	Constructor. Sets up the map and the various DOM elements.
				*/
				function c_geocoder_browser_set_center ( in_lat, in_lng, in_zoom ) {
					g_geocoder_browser_set_center_map = this;
				
					if ( GBrowserIsCompatible() )
						{
						/* This should never happen. */
						if ( !in_lat ) in_lat = 40.83;
						if ( !in_lng ) in_lng = -72.9;
						if ( !in_zoom ) in_zoom = 10;
						
						g_geocoder_browser_set_center_map.map = new GMap2(document.getElementById("meeting_map"), {draggableCursor: "crosshair"});
						if ( g_geocoder_browser_set_center_map.map )
							{
							g_geocoder_browser_set_center_map.map.addControl(new GLargeMapControl());
							g_geocoder_browser_set_center_map.map.addControl(new GMapTypeControl());
							
							point = new GLatLng ( in_lat, in_lng );
					
							g_geocoder_browser_set_center_map.map.setCenter(point, in_zoom);
							g_geocoder_browser_set_center_map.marker = new GMarker(point, {draggable: true, title: "Drag to a New Location."});
							GEvent.addListener(g_geocoder_browser_set_center_map.marker, "dragend", g_geocoder_browser_set_center_map.Dragend );
							GEvent.addListener(g_geocoder_browser_set_center_map.map, "zoomend", g_geocoder_browser_set_center_map.Zoomend );
							GEvent.addListener(g_geocoder_browser_set_center_map.map, "click", g_geocoder_browser_set_center_map.MapClickCallback );
							g_geocoder_browser_set_center_map.map.addOverlay(g_geocoder_browser_set_center_map.marker);
							g_geocoder_browser_set_center_map.Dragend ();
							};
						};
				};
				
				/*******************************************************************/
				/** \brief	
				*/
				c_geocoder_browser_set_center.prototype.Zoomend = function ( in_old_zoom, in_new_zoom )
				{
					document.getElementById('bmlt_map_zoom').value = in_new_zoom;
				};
				
				/*******************************************************************/
				/** \brief	
				*/
				c_geocoder_browser_set_center.prototype.Dragend = function (  )
				{
					point = g_geocoder_browser_set_center_map.marker.getLatLng();
					document.getElementById('bmlt_map_center_latitude').value = point.lat();
					document.getElementById('bmlt_map_center_longitude').value = point.lng();
					document.getElementById('bmlt_map_zoom').value = g_geocoder_browser_set_center_map.map.getZoom();
				};
				
				/*******************************************************************/
				/** \brief	Clicking in the map simulates a very fast drag.
				*/
				c_geocoder_browser_set_center.prototype.MapClickCallback = function ( in_overlay, in_point )
				{
					g_geocoder_browser_set_center_map.marker.setLatLng (in_point );
					g_geocoder_browser_set_center_map.Dragend();
				};
				
				new c_geocoder_browser_set_center ( <?php echo $BMLTOptions['map_center_latitude'] ?>, <?php echo $BMLTOptions['map_center_longitude'] ?>, <?php echo $BMLTOptions['map_zoom'] ?> );
			/* ]]> */
			</script>
 		</div><?php
		}
	
	/**
		\brief This creates an indented list of Service Body checkboxes, and returns them as XHTML.
		If any of the Service Bodies were selected to be "pre-checked," they are checked.
		This function calls the server, to get the available Service bodies to be displayed.
		
		\returns a string, containing the XHTML. NULL if the operation fails.
	*/
	function create_sb_checkboxes ()
		{
		$ret = null;
		
		try
			{
			$options = $this->getAdminOptions ( );
			$root_server = $options['root_server']."client_interface/xhtml/index.php";
			$uri = "$root_server?switcher=GetServiceBodiesXML".$this->my_params;
			$xml_data = self::call_curl ( $uri );
			$xml_array = xml2ary ( $xml_data );
			
			if ( !is_array ( $options['bmlt_service_body_filters'] ) || !count ( $options['bmlt_service_body_filters'] ) )
				{
				$options['bmlt_service_body_filters'] = array();
				}
			
			// Try to create a SimpleXML object.
	
			$ret = '<div class="bmlt_options_line" style="clear:left">';
				$ret .= '<div style="text-align:center;font-weight:bold">'.htmlspecialchars ( $this->service_body_checkboxes_label ).'</div>';
				$ret .= '<dl style="margin-left:300px">';
				
				foreach ( $xml_array['sb']['_c'] as &$sb_type )
					{
					if ( !is_array ( $sb_type['_a'] ) )
						{
						foreach ( $sb_type as &$elem )
							{
							$ret .= self::create_sb_checkboxes_for_one_sb ( $elem, $options );
							}
						}
					else
						{
						$ret .= self::create_sb_checkboxes_for_one_sb ( $sb_type, $options );
						}
					}
				
				$ret .= '</dl>';
			$ret .= '</div>';
			}
		catch ( Exception $e )
			{
			// We die quietly, so as not to wake the kids.
// Just for debug.
// echo ( 'AAAUGH!<pre>'.htmlspecialchars ( print_r ( $e, true ) ).'</pre>' );
			} 
		
		return $ret;
		}
	
	/**
		\brief This creates a single Service Body checkboxe, and returns them as XHTML.
		If the Service Body is selected to be "pre-checked," it is checked.
		
		\returns a string, containing the XHTML. NULL if the operation fails.
	*/
	static function create_sb_checkboxes_for_one_sb (	&$in_sb_element,	///< This is an array, containing the decoded XML reply from the server.
														&$in_options		///< These are our options.
														)
		{
		$ret = null;
		
		// First, we get the attributes, which are the name and the ID of this Service Body
		if ( isset ( $in_sb_element['_a'] ) )
			{
			$id = intval ( $in_sb_element['_a']['id'] );
			$name = htmlspecialchars ( $in_sb_element['_a']['name'] );
			}
		
		if ( isset ( $id ) && isset ( $name ) )
			{
			$ret = '<dt id="bmlt_sb_option_checkbox_dt_'.$id.'" class="bmlt_sb_option_checkbox_dt">';
			$ret .= '<input name="bmlt_service_body_filters[]" value="'.$id.'" type="checkbox" id="bmlt_sb_option_checkbox_'.$id.'" class="bmlt_sb_option_checkbox"';
			if ( in_array ( $id, $in_options['bmlt_service_body_filters'] ) )
				{
				$ret .= ' checked="checked"';
				}
			
			$ret .= '/>';
			$ret .= '<label for="bmlt_sb_option_checkbox_'.$id.'" style="padding-left:4px;font-weight:bold">'.$name.'</label>';
			if ( is_array ( $in_sb_element['_c']['sb']['_c'] ) && count ( $in_sb_element['_c']['sb']['_c'] ) )
				{
				$ret .= '<dd class="bmlt_sb_option_checkbox_dd"><dl style="margin-left:2em">';
					foreach ( $in_sb_element['_c']['sb']['_c'] as &$sb_type )
						{
						if ( !is_array ( $sb_type['_a'] ) )
							{
							foreach ( $sb_type as &$elem )
								{
								$ret .= self::create_sb_checkboxes_for_one_sb ( $elem, $in_options );
								}
							}
						else
							{
							$ret .= self::create_sb_checkboxes_for_one_sb ( $sb_type, $in_options );
							}
						}
				$ret .= '</dl></dd>';
				}
			$ret .= '</dt>';
			}
		return $ret;
		}
		
	/**
		\brief This is a function that returns the results of an HTTP call to a URI.
		It is a lot more secure than file_get_contents, but does the same thing.
		
		\returns a string, containing the response. Null if the call fails to get any data.
		
		\throws an exception if the call fails.
	*/
	static function call_curl ( $in_uri,				///< A string. The URI to call.
								$in_post = true,		///< If false, the transaction is a GET, not a POST. Default is true.
								&$http_status = null	///< Optional reference to a string. Returns the HTTP call status.
								)
		{
		$ret = null;
		
		// If the curl extension isn't loaded, we try one backdoor thing. Maybe we can use file_get_contents.
		if ( !extension_loaded ( 'curl' ) )
			{
			if ( ini_get ( 'allow_url_fopen' ) )
				{
				$ret = file_get_contents ( $in_uri );
				}
			}
		else
			{
			// Create a new cURL resource.
			$resource = curl_init();
			
			// If we will be POSTing this transaction, we split up the URI.
			if ( $in_post )
				{
				$spli = explode ( "?", $in_uri, 1 );
				
				if ( is_array ( $spli ) && count ( $spli ) )
					{
					$in_uri = $spli[0];
					$in_params = $spli[1];
					// Convert query string into an array using parse_str(). parse_str() will decode values along the way.
					parse_str($in_params, $temp);
					
					// Now rebuild the query string using http_build_query(). It will re-encode values along the way.
					// It will also take original query string params that have no value and appends a "=" to them
					// thus giving them and empty value.
					$in_params = http_build_query($temp);
				
					curl_setopt ( $resource, CURLOPT_POST, true );
					curl_setopt ( $resource, CURLOPT_POSTFIELDS, $in_params );
					}
				}
			
			// Set url to call.
			curl_setopt ( $resource, CURLOPT_URL, $in_uri );
			
			// Make curl_exec() function (see below) return requested content as a string (unless call fails).
			curl_setopt ( $resource, CURLOPT_RETURNTRANSFER, true );
			
			// By default, cURL prepends response headers to string returned from call to curl_exec().
			// You can control this with the below setting.
			// Setting it to false will remove headers from beginning of string.
			// If you WANT the headers, see the Yahoo documentation on how to parse with them from the string.
			curl_setopt ( $resource, CURLOPT_HEADER, false );
			
			// Allow  cURL to follow any 'location:' headers (redirection) sent by server (if needed set to true, else false- defaults to false anyway).
			// Disabled, because some servers disable this for security reasons.
//			curl_setopt ( $resource, CURLOPT_FOLLOWLOCATION, true );
			
			// Set maximum times to allow redirection (use only if needed as per above setting. 3 is sort of arbitrary here).
			curl_setopt ( $resource, CURLOPT_MAXREDIRS, 3 );
			
			// Set connection timeout in seconds (very good idea).
			curl_setopt ( $resource, CURLOPT_CONNECTTIMEOUT, 10 );
			
			// Direct cURL to send request header to server allowing compressed content to be returned and decompressed automatically (use only if needed).
			curl_setopt ( $resource, CURLOPT_ENCODING, 'gzip,deflate' );
			
			// Execute cURL call and return results in $content variable.
			$content = curl_exec ( $resource );
			
			// Check if curl_exec() call failed (returns false on failure) and handle failure.
			if ( $content === false )
				{
				// Cram as much info into the exception as possible.
				throw new Exception ( "curl failure calling $in_uri, ".curl_error ( $resource ).", ".curl_errno ( $resource ) );
				}
			else
				{
				// Do what you want with returned content (e.g. HTML, XML, etc) here or AFTER curl_close() call below as it is stored in the $content variable.
			
				// You MIGHT want to get the HTTP status code returned by server (e.g. 200, 400, 500).
				// If that is the case then this is how to do it.
				$http_status = curl_getinfo ($resource, CURLINFO_HTTP_CODE );
				}
			
			// Close cURL and free resource.
			curl_close ( $resource );
			
			// Maybe echo $contents of $content variable here.
			if ( $content !== false )
				{
				$ret = $content;
				}
			}
		
		return $ret;
	}
};

if ( class_exists ( "BMLTPlugin" ) )
	{
	global $BMLTPluginOp;
	
	if ( !isset ( $BMLTPluginOp ) )
		{
		$BMLTPluginOp = new BMLTPlugin();
		
		if ( !function_exists ( "BMLTOption_ap" ) )
			{
			/**
				\brief This creates the admin options menu and sets the options page funtion.
			*/
			function BMLTOption_ap ( )
				{
				global $BMLTPluginOp;
				if ( !isset ( $BMLTPluginOp ) )
					{
					return;
					}

				if ( function_exists ( 'add_options_page' ) )
					{
					add_options_page ( $BMLTPluginOp->options_title, $BMLTPluginOp->menu_string, 9, basename ( __FILE__ ), array ( &$BMLTPluginOp, 'printAdminPage' ) );
					}
				}	
		
			add_action ( 'admin_menu', 'BMLTOption_ap' );
			}
		}
	
	add_filter ( 'the_content', array ( &$BMLTPluginOp, 'content_filter'), 10  );
	add_filter ( 'wp_head', array ( &$BMLTPluginOp, 'head' ) );
	add_action ( 'init', array ( &$BMLTPluginOp, 'init' ) );
	}
?>