<?php
/**
	\file	bmlt_plugin.php
	
	\brief	This is a simple, 1-file WordPress plugin of a BMLT satellite client.
	
Plugin Name: BMLT Satellite Server
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin implementation of the Basic Meeting List Toolbox.
This will replace the "&lt;!--BMLT--&gt;" in the content with the BMLT search.
If you place that in any part of a page (not a post), the page will contain a BMLT satellite server.
Version: 1.0.0
Install: Drop this directory (or file) into the "wp-content/plugins/" directory and activate it.
You need to specify "<!--BMLT-->" in the code section of a page (Will not work in a post).
*/ 

/**
	\class BMLTPlugin
	
	\brief This is the class that implements and encapsulates the plugin functionality. A single instance of this is created, and manages the plugin.
*/

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
	var $initial_view = array ( 'values' => array ( '', 'map', 'text' ), 'prompts' => array ( 'Root Server Decides', 'Map', 'Text' ) );
	var $initial_view_prompt = 'Initial Search Type:';
	
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
	
			die ( self::call_curl ( "$root_server?switcher=RedirectAJAX".$this->my_params ) );
			}
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
			$root_server = $root_server_root."client_interface/xhtml/index.php";
			
			echo "<!-- Added by the BMLT plugin. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n";
		
			echo self::call_curl ( "$root_server?switcher=GetHeaderXHTML".$this->my_params );

			?>
			<style type="text/css">
				/* <![CDATA[ */
				table#bmlt_container .no_js
				{
					text-align:center;
					font-weight:bold;
					font-size: large;
					color:red;
				}
				
				table#bmlt_container.bmlt_container_table
				{
					text-align: center;
					width: 100%;
					margin-left:auto;
					margin-right:auto;
				}
				
				table#bmlt_container .c_comdef_search_specification_map_vis_a,
				table#bmlt_container .c_comdef_search_specification_map_invis_a
				{
					text-align: center;
					font-size: medium;
					font-weight:bold;
				}
				
				table#bmlt_container div
				{
					text-align:center;
				}
				
				table#bmlt_container fieldset div
				{
					text-align:left;
				}
				
				table#bmlt_container div.c_comdef_search_results_map_container_div
				{
					width: 100%;
					position: relative;
					height: 700px;
				}
				
				table#bmlt_container input.c_comdef_search_specification_search_string_text
				{
					width: 30em;
				}
				
				table#bmlt_container tr.c_comdef_search_results_header_row a,
				table#bmlt_container tr.c_comdef_search_results_header_row a:visited,
				table#bmlt_container div.bmlt_menu_div a,
				table#bmlt_container div.bmlt_menu_div a:visited,
				table#bmlt_container div.c_comdef_search_results_single_header_div h1 a,
				table#bmlt_container div.c_comdef_search_results_single_header_div h1 a:visited,
				table#bmlt_container div.close_div a,
				table#bmlt_container div.close_div a:visited,
				table#bmlt_container div.marker_main_info_window_inner_div a,
				table#bmlt_container div.marker_main_info_window_inner_div a:visited
				{
					display:block;
					background-color: #555;
					outline: none;
					color: #99f;
					text-decoration: underline;
					font-weight:bold;
				}
				
				table#bmlt_container div.comdef_style_result_nav_header a,
				table#bmlt_container div.comdef_style_result_nav_header a:visited
				{
					outline: none;
					color: #99f;
					text-decoration: underline;
					font-weight:bold;
				}
				
				table#bmlt_container div.comdef_style_result_nav_header a:hover,
				table#bmlt_container div.comdef_style_result_nav_header a:active
				{
					outline: none;
					color: #f96;
					text-decoration: none;
				}
				
				table#bmlt_container div.filter_message_div_list_container div,
				table#bmlt_container div.filter_message_div
				{
					text-align: left;
				}
				
				table#bmlt_container tr.c_comdef_search_results_header_row a:hover,
				table#bmlt_container tr.c_comdef_search_results_header_row a:active,
				table#bmlt_container div.bmlt_menu_div a:hover,
				table#bmlt_container div.bmlt_menu_div a:active,
				table#bmlt_container div.c_comdef_search_results_single_header_div h1 a:hover,
				table#bmlt_container div.c_comdef_search_results_single_header_div h1 a:active,
				table#bmlt_container div.close_div a:hover,
				table#bmlt_container div.close_div a:active,
				table#bmlt_container div.marker_main_info_window_inner_div a:hover,
				table#bmlt_container div.marker_main_info_window_inner_div a:active
				{
					outline: none;
					color: #f96;
					text-decoration: none;
				}
				
				table#bmlt_container div.bmlt_menu_div
				{
					text-align: center;
				}
				
				table#bmlt_container div.bmlt_menu_div a,
				table#bmlt_container div.bmlt_menu_div a:visited
				{
					line-height: 1.75em;
				}
				
				table#bmlt_container div.c_comdef_search_results_map_container_div
				{
					width: 100%;
					position: relative;
					height: 700px;
				}
				
				table#bmlt_container div.bmlt_menu_div,
				table#bmlt_container div.bmlt_menu_div a,
				table#bmlt_container div.bmlt_menu_div a:visited,
				table#bmlt_container div.c_comdef_search_results_single_div,
				table#bmlt_container div.c_comdef_search_results_single_div div.c_comdef_search_results_single_header_div,
				table#bmlt_container div.c_comdef_search_results_single_div div.c_comdef_single_container,
				table#bmlt_container div.c_comdef_search_results_single_div div.c_comdef_single_container div.c_comdef_single_data_div
				{
					margin:0;
					margin-left:0;
					margin-right:0;
					width:auto;
					border:none;
				}
				
				table#bmlt_container div.c_comdef_search_results_single_div div.c_comdef_single_container div.embedded_map_div
				{
					margin-left:0;
					margin-right:0;
					width:auto;
				}
				
				table#bmlt_container div.c_comdef_search_results_single_div h1
				{
					font-size: xx-large;
					line-height: normal;
				}
				
				table#bmlt_container div.c_comdef_search_results_single_close_box_div a,
				table#bmlt_container div.c_comdef_search_results_single_close_box_div a:visited
				{
					color: white;
				}
				
				table#bmlt_container div.c_comdef_search_results_single_close_box_div a:hover,
				table#bmlt_container div.c_comdef_search_results_single_close_box_div a:active
				{
					color: red;
				}
				
				/**
					This is the links inside the marker info window.
				*/
				table#bmlt_container .marker_info_window_div .marker_more_info_a,
				table#bmlt_container .marker_info_window_div .marker_more_info_a:visited
				{
					padding: 4px;
					margin-top: 4px;
					text-align: center;
					display:block;
					background-color: #555;
					outline: none;
					color: #99f;
					text-decoration: underline;
				}
				
				/**
					and hovered.
				*/
				table#bmlt_container .marker_info_window_div .marker_more_info_a:hover,
				table#bmlt_container .marker_info_window_div .marker_more_info_a:active
				{
					outline: none;
					color: #f96;
					text-decoration: none;
				}
				
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab_selected,
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab_selected:visited,
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab_selected:hover,
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab_selected:active
				{
					outline: none;
					border-top: 1px solid black;
					border-left: 1px solid black;
					border-right: 1px solid black;
					background-color: #eee;
					color: black;
					cursor:default;
					text-decoration:none;
				}
				
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab,
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab:visited
				{
					background-color: #555;
					color: #99f;
					border-top:1px solid #eee;
					border-bottom: 1px solid black;
					text-decoration: underline;
				}
				
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab:hover,
				table#bmlt_container div.c_comdef_search_specification_form_header_div a.bmlt_spec_tab:active
				{
					outline: none;
					background-color: #999;
					color: #933;
					text-decoration: none;
				}
				
				/**
					This is what the links in the map (not string) look like.
					This is just the disclosure link.
				*/
				table#bmlt_container .c_comdef_search_specification_map_vis_a,
				table#bmlt_container .c_comdef_search_specification_map_vis_a:visited,
				table#bmlt_container .c_comdef_search_specification_map_invis_a,
				table#bmlt_container .c_comdef_search_specification_map_invis_a:visited
				{
					display: block;
					height: 16px;
					font-weight: bold;
					color: #00c;
					text-decoration: underline;
				}
				
				/**
					This is what the disclosure link looks like hovered.
				*/
				table#bmlt_container .c_comdef_search_specification_map_vis_a:active,
				table#bmlt_container .c_comdef_search_specification_map_vis_a:hover,
				table#bmlt_container .c_comdef_search_specification_map_invis_a:active,
				table#bmlt_container .c_comdef_search_specification_map_invis_a:hover
				{
					color: #c03;
					text-decoration: none;
				}
				
				/**
					This is the way that links in the header area look "at rest."
				*/
				table#bmlt_container .comdef_style_result_count_header a.c_comdef_map_link_header_a,
				table#bmlt_container .comdef_style_result_count_header a.c_comdef_map_link_header_a:visited
				{
					outline: none;
					color: #99f;
					text-decoration: underline;
				}
				
				/**
					This is how the links look when they are being rolled over.
				*/
				table#bmlt_container .comdef_style_result_count_header a.c_comdef_map_link_header_a:hover,
				table#bmlt_container .comdef_style_result_count_header a.c_comdef_map_link_header_a:active
				{
					outline: none;
					color: #f96;
					text-decoration: none;
				}
				
				/**
					Links in the meeting text look like this "at rest."
				*/
				table#bmlt_container .c_comdef_search_results_table tbody .c_comdef_search_results_meeting_list_a,
				table#bmlt_container .c_comdef_search_results_table tbody .c_comdef_search_results_meeting_list_a:visited
				{
					outline: none;
					border: none;
					display: block;
					width: 100%;
					height: 100%;
					padding: 2px;
					color: #339;
					text-decoration: underline;
				}
				
				/**
					When moused over, they look like this.
				*/
				table#bmlt_container .c_comdef_search_results_table tbody .c_comdef_search_results_meeting_list_a:active,
				table#bmlt_container .c_comdef_search_results_table tbody .c_comdef_search_results_meeting_list_a:hover
				{
					outline: none;
					background-color: #555;
					color: white;
					text-decoration: none;
				}
			/* ]]> */
			</style><?php
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
					$menu = '<div class="bmlt_menu_div no_print"><a href="'.htmlspecialchars($plink).'">'.htmlspecialchars($this->menu_new_search_text).'</a></div>';
					}
				
				if ( isset ( $this->my_http_vars['search_form'] ) )
					{
					$map_center = "&search_spec_map_center=".$options['map_center_latitude'].",".$options['map_center_longitude'].",".$options['map_zoom'];
					$the_new_content = self::call_curl ( "$root_server?switcher=GetSimpleSearchForm".$this->my_params.$map_center );
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
								'bmlt_initial_view' => $this->default_initial_view
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
							<?php echo '<input style="float:left;margin-right:4px;margin-left:300px" class="bmlt_options_text" name="support_old_browsers" id="bmlt_support_old_browsers_check" type="checkbox" value="1"'.($BMLTOptions['support_old_browsers'] ? ' checked="checked"' : '').' />'; ?>
							<label class="bmlt_options_label" for="bmlt_support_old_browsers_check" style="font-weight:bold;text-align:left;display:block"><?php _e ( $this->support_old_browsers_prompt, "BMLTPlugin" ) ?></label>
						</div>
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
		\brief This is a function that returns the results of an HTTP call to a URI.
		It is a lot more secure than file_get_contents, but does the same thing.
		
		\returns a string, containing the response. Null if the call fails to get any data.
		
		\throws an exception if the call fails.
	*/
	static function call_curl ( $in_uri,				///< A string. The URI to call.
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
	
	add_filter ( 'the_content', array ( &$BMLTPluginOp, 'content_filter' ) );
	add_filter ( 'wp_head', array ( &$BMLTPluginOp, 'head' ) );
	add_action ( 'init', array ( &$BMLTPluginOp, 'init' ) );
	}
?>