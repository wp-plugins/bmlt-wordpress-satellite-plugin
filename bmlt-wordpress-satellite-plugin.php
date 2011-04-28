<?php
/****************************************************************************************//**
*   \file   bmlt-wordpress-satellite-plugin.php                                             *
*                                                                                           *
*   \brief  This is a WordPress plugin of a BMLT satellite client.                          *
*   \version 2.1.8                                                                          *
*                                                                                           *
*   These need to be without the asterisks, as WP parses them.                              *
Plugin Name: BMLT WordPress Satellite
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.
Version: 2.1.8
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
********************************************************************************************/

// define ( '_DEBUG_MODE_', 1 ); //Uncomment for easier JavaScript debugging.

// Include the satellite driver class.
require_once ( dirname ( __FILE__ ).'/bmlt-cms-satellite-plugin.php' );

/****************************************************************************************//**
*   \class BMLTWPPlugin                                                                     *
*                                                                                           *
*   \brief This is the class that implements and encapsulates the plugin functionality.     *
*   A single instance of this is created, and manages the plugin.                           *
*                                                                                           *
*   This plugin registers errors by echoing HTML comments, so look at the source code of    *
*   the page if things aren't working right.                                                *
********************************************************************************************/

class BMLTWPPlugin extends BMLTPlugin
{
    /************************************************************************************//**
    *   \brief Constructor.                                                                 *
    ****************************************************************************************/
    function __construct ()
        {
        parent::__construct ();
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the AJAX callback target.                             *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_admin_ajax_base_uri()
        {
        return $_SERVER['PHP_SELF'].'?page=bmlt-wordpress-satellite-plugin.php';
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the basic admin form submit (action) URI              *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_admin_form_uri()
        {
        return $_SERVER['PHP_SELF'].'?page=bmlt-wordpress-satellite-plugin.php';
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the AJAX callback target.                             *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_ajax_base_uri()
        {
        return $_SERVER['PHP_SELF'];
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the plugin directory.                                 *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_plugin_path()
        {
        $url = '';
        if ( function_exists ( 'plugins_url' ) )
            {
            if ( plugins_url() )
                {
                $url = plugins_url()."/bmlt-wordpress-satellite-plugin/";
                }
            elseif ( defined ('WP_PLUGIN_URL' ) )
                {
                $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/";
                }
            else
                {
                echo "<!-- BMLTPlugin ERROR (get_plugin_path)! Empty plugins_url() and no WP_PLUGIN_URL! -->";
                }
            }
        elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
            {
            $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/";
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (get_plugin_path)! No plugins_url() and no WP_PLUGIN_URL! -->";
            }
        
        return $url;
        }

    
    /************************************************************************************//**
    *   \brief This uses the WordPress text processor (__) to process the given string.     *
    *                                                                                       *
    *   This allows easier translation of displayed strings. All strings displayed by the   *
    *   plugin should go through this function.                                             *
    *                                                                                       *
    *   \returns a string, processed by WP.                                                 *
    ****************************************************************************************/
    protected function process_text (  $in_string  ///< The string to be processed.
                                    )
        {
        if ( function_exists ( '__' ) )
            {
            $in_string = htmlspecialchars ( __( $in_string, 'BMLTPlugin' ) );
            }
        else
            {
            echo "<!-- BMLTPlugin Warning (process_text): __() does not exist! -->";
            }
            
        return $in_string;
        }
        
    /************************************************************************************//**
    *   \brief Sets up the admin and handler callbacks.                                     *
    ****************************************************************************************/
    protected function set_callbacks ( )
        {
        if ( function_exists ( 'add_filter' ) )
            {
            add_filter ( 'the_content', array ( self::get_plugin_object(), 'content_filter')  );
            add_filter ( 'wp_head', array ( self::get_plugin_object(), 'standard_head' ) );
            add_filter ( 'admin_head', array ( self::get_plugin_object(), 'admin_head' ) );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (set_callbacks)! No add_filter()! -->";
            }
        
        if ( function_exists ( 'add_action' ) )
            {
            add_action ( 'admin_init', array ( self::get_plugin_object(), 'admin_ajax_handler' ) );
            add_action ( 'admin_menu', array ( self::get_plugin_object(), 'option_menu' ) );
            add_action ( 'wp', array ( self::get_plugin_object(), 'ajax_router' ) );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (set_callbacks)! No add_action()! -->";
            }
        }

    /************************************************************************************//**
    *   \brief This gets the admin options from the database (allows CMS abstraction).      *
    *                                                                                       *
    *   \returns an associative array, with the option settings.                            *
    ****************************************************************************************/
    protected function cms_get_option ( $in_option_key    ///< The name of the option
                                    )
        {
        $ret = $this->geDefaultBMLTOptions();
        
        if ( function_exists ( 'get_option' ) )
            {
            $ret = get_option ( $in_option_key );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (cms_get_option)! No get_option()! -->";
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief This gets the admin options from the database (allows CMS abstraction).      *
    ****************************************************************************************/
    protected function cms_set_option ( $in_option_key,   ///< The name of the option
                                        $in_option_value  ///< the values to be set (associative array)
                                        )
        {
        if ( function_exists ( 'update_option' ) )
            {
            $ret = update_option ( $in_option_key, $in_option_value );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (cms_set_option)! No update_option()! -->";
            }
        }
    
    /************************************************************************************//**
    *   \brief Deletes a stored option (allows CMS abstraction).                            *
    ****************************************************************************************/
    protected function cms_delete_option ( $in_option_key   ///< The name of the option
                                        )
        {
        if ( function_exists ( 'delete_option' ) )
            {
            $ret = delete_option ( $in_option_key );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (cms_delete_option)! No delete_option()! -->";
            }
        }

    /************************************************************************************//**
    *   \brief This gets the page meta for the given page. (allows CMS abstraction).        *
    *                                                                                       *
    *   \returns a mixed type, with the meta data                                           *
    ****************************************************************************************/
    protected function cms_get_post_meta (  $in_page_id,    ///< The ID of the page/post
                                            $in_settings_id ///< The ID of the meta tag to fetch
                                            )
        {
        $ret = null;
        
        if ( function_exists ( 'get_post_meta' ) )
            {
            $ret = get_post_meta ( $in_page_id, $in_settings_id, true );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (cms_get_post_meta)! No get_post_meta()! -->";
            }
        
        return $ret;
        }

    /************************************************************************************//**
    *   \brief This function fetches the settings ID for a page (if there is one).          *
    *                                                                                       *
    *   If $in_check_mobile is set to true, then ONLY a check for mobile support will be    *
    *   made, and no other shortcodes will be checked.                                      *
    *                                                                                       *
    *   \returns a mixed type, with the settings ID.                                        *
    ****************************************************************************************/
    protected function cms_get_page_settings_id ($in_content,               ///< Required (for the base version) content to check.
                                                 $in_check_mobile = false   ///< True if this includes a check for mobile. Default is false.
                                                )
        {
        $my_option_id = null;
        $page_id = null;
        $page = get_page($page_id);
        
        if ( !$in_check_mobile && isset ( $this->my_http_vars['bmlt_settings_id'] ) && is_array ($this->getBMLTOptions ( $this->my_http_vars['bmlt_settings_id'] )) )
            {
            $my_option_id = $this->my_http_vars['bmlt_settings_id'];
            }
        else
            {
            $support_mobile = preg_replace ( '/\D/', '', trim ( $this->cms_get_post_meta ( $page->ID, 'bmlt_mobile' ) ) );
            
            if ( !$support_mobile && $in_check_mobile )
                {
                $support_mobile = self::get_shortcode ( $in_content, 'bmlt_mobile');
                
                if ( $support_mobile === true )
                    {
                    $options = $this->getBMLTOptions ( 1 );
                    $support_mobile = strval ( $options['id'] );
                    }
                }

            if ( $in_check_mobile && $support_mobile && !isset ( $this->my_http_vars['BMLTPlugin_mobile'] ) && (self::mobile_sniff_ua ($this->my_http_vars) != 'xhtml') )
                {
                $my_option_id = $support_mobile;
                }
            elseif ( !$in_check_mobile )
                {
                $my_option_id = intval ( preg_replace ( '/\D/', '', trim ( $this->cms_get_post_meta ( $page->ID, 'bmlt_settings_id' ) ) ) );
                if ( isset ( $this->my_http_vars['bmlt_settings_id'] ) && intval ( $this->my_http_vars['bmlt_settings_id'] ) )
                    {
                    $my_option_id = intval ( $this->my_http_vars['bmlt_settings_id'] );
                    }
                elseif ( $in_content = $in_content ? $in_content : $page->post_content )
                    {
                    $my_option_id_content = parent::cms_get_page_settings_id ( $in_content, $in_check_mobile );
                    
                    $my_option_id = $my_option_id_content ? $my_option_id_content : $my_option_id;
                    }
                
                if ( !$my_option_id )   // If nothing else gives, we go for the default (first) settings.
                    {
                    $options = $this->getBMLTOptions ( 1 );
                    $my_option_id = $options['id'];
                    }
                }
            }
        
        return $my_option_id;
        }
        
    /************************************************************************************//**
    *                               THE WORDPRESS CALLBACKS                                 *
    ****************************************************************************************/
        
    /************************************************************************************//**
    *   \brief Presents the admin page.                                                     *
    ****************************************************************************************/
    function admin_page ( )
        {
        echo $this->return_admin_page ( );
        }
       
    /************************************************************************************//**
    *   \brief Presents the admin menu options.                                             *
    *                                                                                       *
    * NOTE: This function requires WP. Most of the rest can probably be more easily         *
    * converted for other CMSes.                                                            *
    ****************************************************************************************/
    function option_menu ( )
        {
        if ( function_exists ( 'add_options_page' ) && (self::get_plugin_object() instanceof BMLTPlugin) )
            {
            add_options_page ( self::$local_options_title, self::$local_menu_string, 9, basename ( __FILE__ ), array ( self::get_plugin_object(), 'admin_page' ) );
            }
        elseif ( !function_exists ( 'add_options_page' ) )
            {
            echo "<!-- BMLTPlugin ERROR (option_menu)! No add_options_page()! -->";
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (option_menu)! No BMLTPlugin Object! -->";
            }
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function standard_head ( )
        {
        $load_head = false;   // This is a throwback. It prevents the GM JS from being loaded if there is no directly specified settings ID.
        $head_content = "<!-- Added by the BMLT plugin 2.0. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
        $load_head = true;
        
        // If you specify the bmlt_mobile custom field in this page (not post), then it can force the browser to redirect to a mobile handler.
        // The value of bmlt_mobile must be the settings ID of the server you want to handle the mobile content.
        // Post redirectors are also handled, but at this point, only the page will be checked.
        $page_id = null;
        $page = get_page($page_id);
        
        $support_mobile = $this->cms_get_page_settings_id ( $page->post_content, true );
        
        if ( $support_mobile )
            {
            $mobile_options = $this->getBMLTOptions_by_id ( $support_mobile );
            }
        else
            {
            $support_mobile = null;
            }
        
        $options = $this->getBMLTOptions_by_id ( $this->cms_get_page_settings_id($in_content) );

        if ( $support_mobile && is_array ( $mobile_options ) && count ( $mobile_options ) )
            {
            $mobile_url = $_SERVER['PHP_SELF'].'?BMLTPlugin_mobile&bmlt_settings_id='.$support_mobile;
            if ( isset ( $this->my_http_vars['WML'] ) )
                {
                $mobile_url .= '&WML='.intval ( $this->my_http_vars['WML'] );
                }
            if ( isset ( $this->my_http_vars['simulate_smartphone'] ) )
                {
                $mobile_url .= '&simulate_smartphone';
                }
            ob_end_clean();
            header ( "location: $mobile_url" );
            die ( );
            }
        
        if ( !$options['gmaps_api_key'] )   // No GMAP API key, no BMLT window.
            {
            $load_head = false;
            }
        
        $this->my_http_vars['gmap_key'] = $options['gmaps_api_key'];
        
        $this->my_http_vars['start_view'] = $options['bmlt_initial_view'];
        
        $this->load_params ( );
        
        $root_server_root = $options['root_server'];
        
        $head_content .= '<link rel="stylesheet" type="text/css" href="';
        
        $url = $this->get_plugin_path();
        
        $head_content .= htmlspecialchars ( $url.'themes/'.$options['theme'].'/' );
        
        if ( !defined ('_DEBUG_MODE_' ) )
            {
            $head_content .= 'style_stripper.php?filename=';
            }
        
        $head_content .= 'styles.css" />';

        if ( $root_server_root )
            {
            $root_server = $root_server_root."/client_interface/xhtml/index.php";
            
            if ( $load_head )
                {
                $head_content .= bmlt_satellite_controller::call_curl ( "$root_server?switcher=GetHeaderXHTML".$this->my_params );
                }
            
            if ( $options['push_down_more_details'] )
                {
                $additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_ajax_div{position:static;margin:0;width:100%;}';
                $additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_close_box_div{position:relative;left:100%;margin-left:-18px;}';
                $additional_css .= 'table#bmlt_container div#bmlt_contact_us_form_div{position:static;width:100%;margin:0;}';
                }
            
            if ( $options['additional_css'] )
                {
                $additional_css .= $options['additional_css'];
                }
            
            if ( $additional_css )
                {
                $head_content .= '<style type="text/css">'.preg_replace ( "|\s+|", " ", $additional_css ).'</style>';
                }
            }
        
        $head_content .= '<script type="text/javascript" src="';
        
        $head_content .= htmlspecialchars ( $url );
        
        if ( !defined ('_DEBUG_MODE_' ) )
            {
            $head_content .= 'js_stripper.php?filename=';
            }
        
        $head_content .= 'javascript.js"></script>';

        echo $head_content;
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content for the admin.                             *
    ****************************************************************************************/
    function admin_head ( )
        {
        $this->standard_head ( );   // We start with the standard stuff.
        
        $head_content = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';  // Load the Google Maps stuff for our map.
        
        if ( function_exists ( 'plugins_url' ) )
            {
            $head_content .= '<link rel="stylesheet" type="text/css" href="';
            
            $url = $this->get_plugin_path();
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE_' ) )
                {
                $head_content .= 'style_stripper.php?filename=';
                }
            
            $head_content .= 'admin_styles.css" />';
            
            $head_content .= '<script type="text/javascript" src="';
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE_' ) )
                {
                $head_content .= 'js_stripper.php?filename=';
                }
            
            $head_content .= 'admin_javascript.js"></script>';
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (head)! No plugins_url()! -->";
            }
            
        echo $head_content;
        }
    
    /************************************************************************************//**
    *   \brief Massages the page content.                                                   *
    *                                                                                       *
    *   \returns a string, containing the "massaged" content.                               *
    ****************************************************************************************/
    function content_filter ( $in_the_content   ///< The content in need of filtering.
                            )
        {
        // Simple searches can be mixed in with other content.
        $in_the_content = $this->display_simple_search ( $in_the_content );

        $count = 0;

        $in_the_content = $this->display_popup_search ( $in_the_content, $count );
        
        if ( !$count )
            {
            $in_the_content = $this->display_old_search ( $in_the_content, $count );
            }
        
        return $in_the_content;
        }
        
    /************************************************************************************//**
    *   \brief This is a function that filters the content, and replaces a portion with the *
    *   "popup" search, if provided by the 'bmlt_simple_searches' custom field.             *
    *                                                                                       *
    *   \returns a string, containing the content.                                          *
    ****************************************************************************************/

    function display_popup_search ( $in_content,      ///< This is the content to be filtered.
                                        &$out_count       ///< This is set to 1, if a substitution was made.
                                        )
        {
        if ( self::get_shortcode ( $in_content, 'simple_search_list' ) )
            {
            $text = $this->cms_get_post_meta ( get_the_ID(), 'bmlt_simple_searches' );
            $display .= '';
            if ( $text )
                {
                $text_ar = explode ( "\n", $text );
                
                if ( is_array ( $text_ar ) && count ( $text_ar ) )
                    {
                    $display .= '<noscript class="no_js">'.$this->process_text ( self::$local_noscript ).'</noscript>';
                    $display .= '<div id="interactive_form_div" class="interactive_form_div" style="display:none"><form action="#" onsubmit="return false"><div>';
                    $display .= '<label class="meeting_search_select_label" for="meeting_search_select">Find Meetings:</label> ';
                    $display .= '<select id="meeting_search_select"class="simple_search_list" onchange="BMLTPlugin_simple_div_filler (this.value,this.options[this.selectedIndex].text);this.options[this.options.length-1].disabled=(this.selectedIndex==0)">';
                    $display .= '<option disabled="disabled" selected="selected">'.$this->process_text ( self::$local_select_search ).'</option>';
                    $lines_max = count ( $text_ar );
                    $lines = 0;
                    while ( $lines < $lines_max )
                        {
                        $line['parameters'] = trim($text_ar[$lines++]);
                        $line['prompt'] = trim($text_ar[$lines++]);
                        if ( $line['parameters'] && $line['prompt'] )
                            {
                            $uri = $this->get_ajax_base_uri().'?bmlt_settings_id='.$this->cms_get_page_settings_id($in_content).'&amp;direct_simple&amp;search_parameters='.urlencode ( $line['parameters'] );
                            $display .= '<option value="'.$uri.'">'.__($line['prompt']).'</option>';
                            }
                        }
                    $display .= '<option disabled="disabled"></option>';
                    $display .= '<option disabled="disabled" value="">'.$this->process_text ( self::$local_clear_search ).'</option>';
                    $display .= '</select></div></form>';
                    
                    $display .= '<script type="text/javascript">';
                    $display .= 'document.getElementById(\'interactive_form_div\').style.display=\'block\';';
                    $display .= 'document.getElementById(\'meeting_search_select\').selectedIndex=0;';

                    $options = $this->getBMLTOptions_by_id ( $this->cms_get_page_settings_id($in_content) );
                    $url = $this->get_plugin_path();
                    $img_url .= htmlspecialchars ( $url.'themes/'.$options['theme'].'/images/' );
                    
                    $display .= "var c_g_BMLTPlugin_images = '$img_url';";
                    $display .= '</script>';
                    $display .= '<div id="simple_search_container"></div></div>';
                    }
                }
            
            $in_content = self::replace_shortcode ($in_content, 'simple_search_list', $display);
            }
        
        return $in_content;
        }
};

/****************************************************************************************//**
*                                   MAIN CODE CONTEXT                                       *
********************************************************************************************/
global $BMLTPluginOp;

if ( !isset ( $BMLTPluginOp ) && class_exists ( "BMLTWPPlugin" ) )
    {
    $BMLTPluginOp = new BMLTWPPlugin();
    }
?>