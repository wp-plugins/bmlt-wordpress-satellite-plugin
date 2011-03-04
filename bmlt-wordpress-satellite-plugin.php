<?php
/****************************************************************************************//**
*   \file   bmlt-wordpress-satellite-plugin.php                                             *
*                                                                                           *
*   \brief  This is a WordPress plugin of a BMLT satellite client.                          *
*   \version 2.0.1                                                                          *
*                                                                                           *
*   These need to be without the asterisks, as WP parses them.                              *
Plugin Name: BMLT WordPress Satellite
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.
Version: 2.0.1
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
********************************************************************************************/

// define ( '_DEBUG_MODE_', 1 ); //Uncomment for easier JavaScript debugging.

// Include the satellite driver class.
require_once ( 'bmlt_satellite_controller.class.php' );

/****************************************************************************************//**
*   \class BMLTPlugin                                                                       *
*                                                                                           *
*   \brief This is the class that implements and encapsulates the plugin functionality.     *
*   A single instance of this is created, and manages the plugin.                           *
*                                                                                           *
*   This plugin registers errors by echoing HTML comments, so look at the source code of    *
*   the page if things aren't working right.                                                *
********************************************************************************************/

class BMLTPlugin
{
    /************************************************************************************//**
    *                           STATIC DATA MEMBERS (SINGLETON)                             *
    ****************************************************************************************/
    
    /// This is a SINGLETON pattern. There can only be one...
    static  $g_s_there_can_only_be_one = null;                                      ///< This is a static variable that holds the single instance.
    
    /************************************************************************************//**
    *                           STATIC DATA MEMBERS (DEFAULTS)                              *
    *   In Version 2, these are all ignored:                                                *
    *       $default_bmlt_fullscreen                                                        *
    *       $default_support_old_browsers                                                   *
    *       $default_initial_view                                                           *
    *       $default_sb_array                                                               *
    *       $default_language                                                               *
    ****************************************************************************************/
    
    static  $adminOptionsName = "BMLTAdminOptions";                                 ///< The name, in the database, for the version 1 options for this plugin.
    static  $admin2OptionsName = "BMLT2AdminOptions";                               ///< These options are for version 2.
    
    // These are the old settings that we still care about.
    static  $default_rootserver = '';                                               ///< This is the default root BMLT server URI.
    static  $default_map_center_latitude = 29.764377375163125;                      ///< This is the default basic search map center latitude
    static  $default_map_center_longitude = -95.4931640625;                         ///< This is the default basic search map center longitude
    static  $default_map_zoom = 8;                                                  ///< This is the default basic search map zoom level
    static  $default_new_search = '';                                               ///< If this is set to something, then a new search uses the exact URI.
    static  $default_gkey = '';                                                     ///< This is only necessary for older versions.
    static  $default_push_down_more_details = '1';      ///< If this is set to 1, then "More Details" and "Contact" windows will "push down" the content, instead of floating over it.
    static  $default_additional_css = '';               ///< This is additional CSS that is inserted inline into the <head> section.
    static  $default_initial_view = '';                 ///< The initial view for old-style BMLT. It can be 'map', 'text', 'advanced', 'advanced map', 'advanced text' or ''.
    static  $default_theme = 'default';                 ///< This is the default for the "style theme" for the plugin. Different settings can have different themes.
    
    /************************************************************************************//**
    *                           STATIC DATA MEMBERS (LOCALIZABLE)                           *
    ****************************************************************************************/

    /// These are all for the admin pages.
    static  $local_options_title = 'Basic Meeting List Toolbox Options';    ///< This is the title that is displayed over the options.
    static  $local_menu_string = 'BMLT Options';                            ///< The name of the menu item.
    static  $local_options_prefix = 'Select Setting ';                      ///< The string displayed before each number in the options popup.
    static  $local_options_add_new = 'Add A new Setting';                   ///< The string displayed in the "Add New Option" button.
    static  $local_options_save = 'Save Changes';                           ///< The string displayed in the "Save Changes" button.
    static  $local_options_delete_option = 'Delete This Setting';           ///< The string displayed in the "Delete Option" button.
    static  $local_options_delete_failure = 'The setting deletion failed.'; ///< The string displayed upon unsuccessful deletion of an option page.
    static  $local_options_create_failure = 'The setting creation failed.'; ///< The string displayed upon unsuccessful creation of an option page.
    static  $local_options_delete_option_confirm = 'Are you sure that you want to delete this setting?';    ///< The string displayed in the "Are you sure?" confirm.
    static  $local_options_delete_success = 'The setting was deleted successfully.';                        ///< The string displayed upon successful deletion of an option page.
    static  $local_options_create_success = 'The setting was created successfully.';                        ///< The string displayed upon successful creation of an option page.
    static  $local_options_save_success = 'The settings were updated successfully.';                        ///< The string displayed upon successful update of an option page.
    static  $local_options_save_failure = 'The settings were not updated.';                                 ///< The string displayed upon unsuccessful update of an option page.
    static  $local_options_url_bad = 'This root server URL will not work for this plugin.';                 ///< The string displayed if a root server URI fails to point to a valid root server.
    static  $local_options_access_failure = 'You are not allowed to perform this operation.';               ///< This is displayed if a user attempts a no-no.
    static  $local_options_unsaved_message = 'You have unsaved changes. Are you sure you want to leave without saving them?';   ///< This is displayed if a user attempts to leave a page without saving the options.
    static  $local_options_settings_id_prompt = 'The ID for this Setting is ';                              ///< This is so that users can see the ID for the setting.
    
    /// These are all for the admin page option sheets.
    static  $local_options_name_label = 'Setting Name:';                    ///< The Label for the setting name item.
    static  $local_options_rootserver_label = 'Root Server:';               ///< The Label for the root server item.
    static  $local_options_new_search_label = 'New Search URL:';            ///< The Label for the new search item.
    static  $local_options_gkey_label = 'Google Maps API Key:';             ///< The Label for the Google Maps API Key item.
    static  $local_options_no_name_string = 'Enter Setting Name';           ///< The Value to use for a name field for a setting with no name.
    static  $local_options_no_root_server_string = 'Enter a Root Server URL';   ///< The Value to use for a root with no URL.
    static  $local_options_no_new_search_string = 'Enter a New Search URL'; ///< The Value to use for a new search with no URL.
    static  $local_options_no_gkey_string = 'Enter a New API Key';          ///< The Value to use for a new search with no URL.
    static  $local_options_test_server = 'Test';                            ///< This is the title for the "test server" button.
    static  $local_options_test_server_success = 'Version ';                ///< This is a prefix for the version, on success.
    static  $local_options_test_server_failure = 'This Root Server URL is not Valid';               ///< This is a prefix for the version, on failure.
    static  $local_options_test_server_tooltip = 'This tests the root server, to see if it is OK.'; ///< This is the tooltip text for the "test server" button.
    static  $local_options_map_label = 'Select a Center Point and Zoom Level for Map Displays';     ///< The Label for the map.
    static  $local_options_gkey_caveat = 'These are only necessary for old-style BMLT implementations';  ///< This lets people know that this is not necessary for newer installs.
    static  $local_options_initial_view = array (                           ///< The list of choices for presentation in the popup.
                                                '' => 'Root Server Decides', 'map' => 'Map', 'text' => 'Text', 'advanced' => 'Advanced (Server Decides)', 'advanced_map' => 'Advanced Map', 'advanced_text' => 'Advanced Text'
                                                );
    static  $local_options_initial_view_prompt = 'Initial Search Type:';    ///< The label for the initial view popup.
    static  $local_options_theme_prompt = 'Select a Color Theme:';          ///< The label for the theme selection popup.
    static  $local_options_push_down_checkbox_label = '"More Details" Windows "push down" the main list or map, as opposed to popping up over them.'; ///< The label for the "more details" checkbox.
    static  $local_options_more_styles_label = 'Add CSS Styles to the Plugin:';           ///< The label for the Additional CSS textarea.
    static  $local_single_meeting_tooltip = 'Follow This Link for Details About This Meeting.'; ///< The tooltip shown for a single meeting.
    static  $local_gm_link_tooltip = 'Follow This Link to be Taken to A Google Maps Location for This Meeting.';    ///< The tooltip shown for the Google Maps link.
    static  $local_not_enough_for_old_style = 'In order to display the "classic" BMLT window, you need to have both a root server and a Google Maps API key in the corresponding setting.'; ///< Displayed if there is no GMAP API key.
    
    /// These are for the actual search displays
    static  $local_select_search = 'Select a Quick Search';                 ///< Used for the "filler" in the quick search popup.
    static  $local_clear_search = 'Clear Search Results';                   ///< Used for the "Clear" item in the quick search popup.
    static  $local_menu_new_search_text = 'New Search';                     ///< For the new search menu in the old-style BMLT search.
                                    
    /************************************************************************************//**
    *                      STATIC DATA MEMBERS (SPECIAL LOCALIZABLE)                        *
    ****************************************************************************************/

    /// This is the only localizable string that is not processed. This is because it contains HTML. However, it is also a "hidden" string that is only displayed when the browser does not support JS.
    static  $local_no_js_warning = '<noscript class="no_js">This Meeting Search will not work because your browser does not support JavaScript. However, you can use the <a rel="external nofollow" href="###ROOT_SERVER###">main server</a> to do the search.</noscript>'; ///< This is the noscript presented for the old-style meeting search. It directs the user to the root server, which will support non-JS browsers.
     
    /// This one is processed.
    static  $local_noscript = 'This will not work, because you do not have JavaScript active.';             ///< The string displayed in a <noscript> element.
   
    /************************************************************************************//**
    *                               STATIC DATA MEMBERS (MISC)                              *
    ****************************************************************************************/
    
    static  $local_options_success_time = 2000;                             ///< The number of milliseconds a success message is displayed.
    static  $local_options_failure_time = 5000;                             ///< The number of milliseconds a failure message is displayed.
    
    /************************************************************************************//**
    *                              STATIC DATA MEMBERS (MOBILE)                             *
    *                                                                                       *
    *   We use an adaptation of the standalone Fast Mobile Lookup, here. These originated   *
    *   as defines, but are now static data members.                                        *
    ****************************************************************************************/

    /// This defines a "grace time," so that meetings later today will include the current time, plus the "grace time," so it isn't so anal.
    static  $mobile_grace_time = 15;	///< This is in minutes.

    /// This is set to either 'M' (Miles) or 'K' (Kilometers), for the distance display.
    static  $distance_units = 'M';
                                    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (MOBILE LOCALIZABLE)                        *
    ****************************************************************************************/

    /// The units for distance.
    static  $local_mobile_kilometers = 'Kilometers';
    static  $local_mobile_miles = 'Miles';
    static  $local_mobile_distance = 'Distance';  ///< Distance (the string)

    /// The page titles.
    static  $local_mobile_results_page_title = 'Quick Meeting Search Results';
    static  $local_mobile_results_form_title = 'Find Nearby Meetings Quickly';

    /// The fast GPS lookup links.
    static  $local_GPS_banner = 'Select A Fast Meeting Lookup';
    static  $local_GPS_banner_subtext = 'Bookmark these links for even faster searches in the future.';
    static  $local_search_all = 'Search for all meetings near my present location.';
    static  $local_search_today = 'Later Today';
    static  $local_search_tomorrow = 'Tomorrow';

    /// The search for an address form.
    static  $local_list_check = 'If you are experiencing difficulty with the interactive map, or wish to have the results returned as a list, check this box and enter an address.';
    static  $local_search_address_single = 'Search for Meetings Near An Address';

    /// Used instead of "near my present location."
    static  $local_search_all_address = 'Search for all meetings near this address.';
    static  $local_search_submit_button = 'Search For Meetings';

    /// This is what is entered into the text box.
    static  $local_enter_an_address = 'Enter An Address';

    /// Error messages.
    static  $local_mobile_fail_no_meetings = 'No Meetings Found!';
    static  $local_server_fail = 'The search failed because the server encountered an error!';
    static  $local_cant_find_address = 'Cannot Determine the Location From the Address Information!';
    static  $local_cannot_determine_location = 'Cannot Determine Your Current Location!';
    static  $local_enter_address_alert = 'Please enter an address!';

    /// The text for the "Map to Meeting" links
    static  $local_map_link = 'Map to Meeting';

    /// Only used for WML pages
    static  $local_next_card = 'Next Meeting >>';
    static  $local_prev_card = '<< Previous Meeting';
    
    /// Used for the info and list windows.
    static  $local_formats = 'Formats';
    static  $local_noon = 'Noon';
    static  $local_midnight = 'Midnight';

    /// This array has the weekdays, spelled out. Since weekdays start at 1 (Sunday), we consider 0 to be an error.
    static	$local_weekdays = array ( 'ERROR', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );

    /************************************************************************************//**
    *                                  DYNAMIC DATA MEMBERS                                 *
    ****************************************************************************************/
    
    var $my_driver = null;              ///< This will contain an instance of the BMLT satellite driver class.
    var $my_params = null;              ///< This will contain the $this->my_http_vars and $_POST query variables.
    var $my_option_id = null;           ///< This will be used to hold a page-chosen option ID.
    var $my_http_vars = null;           ///< This will hold all of the query arguments.
    
    /************************************************************************************//**
    *                                    FUNCTIONS/METHODS                                  *
    ****************************************************************************************/
    
    /************************************************************************************//**
    *   \brief Get the instance                                                             *
    *                                                                                       *
    *   \return An instance  of BMLTPlugin                                                  *
    ****************************************************************************************/
    static function get_plugin_object ()
        {
        return self::$g_s_there_can_only_be_one;
        }
    
    /****************************************************************************************//**
    *   \brief Checks the UA of the caller, to see if it should return XHTML Strict or WML.     *
    *                                                                                           *
    *   NOTE: This is very, very basic. It is not meant to be a studly check, like WURFL.       *
    *                                                                                           *
    *   \returns A string. The supported type ('xhtml', 'xhtml_mp' or 'wml')                    *
    ********************************************************************************************/
    static function mobile_sniff_ua (   $in_http_vars   ///< The query variables.
                                    )
    {
        if ( isset ( $in_http_vars['WML'] ) && (intval ( $in_http_vars['WML'] ) == 1) )
            {
            $language = 'wml';
            }
        elseif ( isset ( $in_http_vars['WML'] ) && (intval ( $in_http_vars['WML'] ) == 2) )
            {
            $language = 'xhtml_mp';
            }
        else
            {
            if (!isset($_SERVER['HTTP_ACCEPT']))
                {
                return false;
                }
        
            $http_accept = explode (',', $_SERVER['HTTP_ACCEPT']);
        
            $accept = array();
        
            foreach ($http_accept as $type)
                {
                $type = strtolower(trim(preg_replace('/\;.*$/', '', preg_replace ('/\s+/', '', $type))));
        
                $accept[$type] = true;
                }
        
            $language = 'xhtml';
        
            if (isset($accept['text/vnd.wap.wml']))
                {
                $language = 'wml';
        
                if (isset($accept['application/xhtml+xml']) || isset($accept['application/vnd.wap.xhtml+xml']))
                    {
                    $language = 'xhtml_mp';
                    }
                }
            else
                {
                if (    preg_match ( '/ipod/i', $_SERVER['HTTP_USER_AGENT'] )
                    ||  preg_match ( '/ipad/i', $_SERVER['HTTP_USER_AGENT'] )
                    ||  preg_match ( '/iphone/i', $_SERVER['HTTP_USER_AGENT'] )
                    ||  preg_match ( '/android/i', $_SERVER['HTTP_USER_AGENT'] )
                    ||  preg_match ( '/blackberry/i', $_SERVER['HTTP_USER_AGENT'] )
                    ||  preg_match ( "/opera\s+mini/i", $_SERVER['HTTP_USER_AGENT'] )
                    ||  isset ( $in_http_vars['simulate_smartphone'] )
                    )
                    {
                    $language = 'smartphone';
                    }
                }
            }
        return $language;
    }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the plugin directory.                                 *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    static function get_plugin_path()
        {
        $url = '';
        if ( plugins_url() )
            {
            $url = plugins_url()."/bmlt-wordpress-satellite-plugin/";
            }
        elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
            {
            $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/";
            }
        return $url;
        }
    
    /************************************************************************************//**
    *   \brief Constructor. Enforces the SINGLETON, and sets up the callbacks.              *
    ****************************************************************************************/
    function __construct ()
        {
        if ( !isset ( self::$g_s_there_can_only_be_one ) || (self::$g_s_there_can_only_be_one === null) )
            {
            self::$g_s_there_can_only_be_one = $this;
            
            $this->my_http_vars = array_merge_recursive ( $_GET, $_POST );
                
            if ( !(isset ( $this->my_http_vars['search_form'] ) && $this->my_http_vars['search_form'] )
                && !(isset ( $this->my_http_vars['do_search'] ) && $this->my_http_vars['do_search'] ) 
                && !(isset ( $this->my_http_vars['single_meeting_id'] ) && $this->my_http_vars['single_meeting_id'] ) 
                )
                {
                $this->my_http_vars['search_form'] = true;
                }
            
            $this->my_http_vars['script_name'] = preg_replace ( '|(.*?)\?.*|', "$1", $_SERVER['REQUEST_URI'] );
            $this->my_http_vars['satellite'] = $this->my_http_vars['script_name'];
            $this->my_http_vars['supports_ajax'] = 'yes';
            $this->my_http_vars['no_ajax_check'] = 'yes';

            // We need to start off by setting up our driver.
            $this->my_driver = new bmlt_satellite_controller;
            
            if ( $this->my_driver instanceof bmlt_satellite_controller )
                {
                if ( function_exists ( 'add_filter' ) )
                    {
                    add_filter ( 'the_content', array ( self::get_plugin_object(), 'content_filter')  );
                    add_filter ( 'wp_head', array ( self::get_plugin_object(), 'standard_head' ) );
                    add_filter ( 'admin_head', array ( self::get_plugin_object(), 'admin_head' ) );
                    }
                else
                    {
                    echo "<!-- BMLTPlugin ERROR (__construct)! No add_filter()! -->";
                    }
                
                if ( function_exists ( 'add_action' ) )
                    {
                    add_action ( 'admin_init', array ( self::get_plugin_object(), 'admin_init' ) );
                    add_action ( 'admin_menu', array ( self::get_plugin_object(), 'option_menu' ) );
                    add_action ( 'wp', array ( self::get_plugin_object(), 'wp_handler' ) );
                    }
                else
                    {
                    echo "<!-- BMLTPlugin ERROR (__construct)! No add_action()! -->";
                    }
                }
            else
                {
                echo "<!-- BMLTPlugin ERROR (__construct)! Can't Instantiate the Satellite Driver! Please reinstall the plugin! -->";
                }
            }
        else
            {
            echo "<!-- BMLTPlugin Warning: __construct() called multiple times! -->";
            }
        }
        
    /************************************************************************************//**
    *                                       ACCESSORS                                       *
    ****************************************************************************************/
    
    /************************************************************************************//**
    *   \brief Accessor: This gets the driver object.                                       *
    *                                                                                       *
    *   \returns a reference to the bmlt_satellite_controller driver object                 *
    ****************************************************************************************/
    function &get_my_driver ()
        {
        return $this->my_driver;
        }
    
    /************************************************************************************//**
    *                               MISCELLANEOUS FUNCTIONS                                 *
    ****************************************************************************************/
    
    /************************************************************************************//**
    *   \brief This uses the WordPress text processor (__) to process the given string.     *
    *                                                                                       *
    *   This allows easier translation of displayed strings. All strings displayed by the   *
    *   plugin should go through this function.                                             *
    *                                                                                       *
    *   \returns a string, processed by WP.                                                 *
    ****************************************************************************************/
    static function process_text (  $in_string  ///< The string to be processed.
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
    *                               OPTIONS MANAGEMENT                                      *
    *****************************************************************************************
    *   This takes some 'splainin'.                                                         *
    *                                                                                       *
    *   The admin2 options track how many servers we're tracking, and allow the admin to    *
    *   increment by 1. The first options don't have a number. "Numbered" options begin at  *
    *   2. You are allowed to save new options at 1 past the current number of options. You *
    *   delete options by decrementing the number in the admin2 options (the index). If you *
    *   re-increment the options, you will see the old values. It is possible to reset to   *
    *   default, and you do that by specifying an option number less than 0 (-1).           *
    *                                                                                       *
    *   The reason for this funky, complex game, is so we can have multiple options, and we *
    *   don't ignore old options from previous versions.                                    *
    *                                                                                       *
    *   I considered setting up an abstracted, object-based system for managing these, but  *
    *   it's complex enough without the added overhead, and, besides, that would give a lot *
    *   more room for bugs. It's kinda hairy already, and the complexity is not great       *
    *   enough to justify designing a whole object subsystem for it.                        *
    ****************************************************************************************/
    
    /************************************************************************************//**
    *   \brief This gets the admin options from the database.                               *
    *                                                                                       *
    *   \returns an associative array, with the option settings.                            *
    ****************************************************************************************/
    function getBMLTOptions ( $in_option_number = null  /**<    It is possible to store multiple options.
                                                                If there is a number here (>1), that will be used.
                                                                If <0, a new option will be returned (not saved).
                                                        */
                            )
        {
        $BMLTOptions = null;
        
        if ( function_exists ( 'get_option' ) )
            {
            // These are the defaults. If the saved option has a different value, it replaces the ones in here.
            $BMLTOptions = array (  'root_server' => self::$default_rootserver,
                                    'map_center_latitude' => self::$default_map_center_latitude,
                                    'map_center_longitude' => self::$default_map_center_longitude,
                                    'map_zoom' => self::$default_map_zoom,
                                    'bmlt_new_search_url' => self::$default_new_search,
                                    'gmaps_api_key' => self::$default_gkey,
                                    'bmlt_initial_view' => self::$default_initial_view,
                                    'push_down_more_details' => self::$default_push_down_more_details,
                                    'additional_css' => self::$default_additional_css,
                                    'id' => abs (function_exists ( 'microtime' ) ? intval(microtime(true) * 10000) : ((time() * 1000) + intval(rand(0, 999)))),   // This gives the option a unique slug
                                    'setting_name' => '',
                                    'theme' => self::$default_theme
                                    );
            
            // Make sure we aren't resetting to default.
            if ( ($in_option_number == null) || (intval ( $in_option_number ) > 0) )
                {
                $option_number = null;
                // If they want a certain option number, then it needs to be greater than 1, and within the number we have assigned.
                if ( (intval ( $in_option_number ) > 1) && (intval ( $in_option_number ) <= $this->get_num_options ( ) ) )
                    {
                    $option_number = '_'.intval( $in_option_number );
                    }
            
                // These are the standard options.
                $old_BMLTOptions = get_option ( self::$adminOptionsName.$option_number );
                
                if ( is_array ( $old_BMLTOptions ) && count ( $old_BMLTOptions ) )
                    {
                    foreach ( $old_BMLTOptions as $key => $value )
                        {
                        if ( isset ( $BMLTOptions[$key] ) ) // We deliberately ignore old settings that no longer apply.
                            {
                            $BMLTOptions[$key] = $value;
                            }
                        }
                    }
            
                // Strip off the trailing slash.
                $BMLTOptions['root_server'] = preg_replace ( "#\/$#", "", trim($BMLTOptions['root_server']), 1 );
                }
            else
                {
                $in_option_number = $this->get_num_options() + 1;
                }
            
            $this->setBMLTOptions ( $BMLTOptions, $in_option_number );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (getBMLTOptions)! No get_option()! -->";
            }
        
        return $BMLTOptions;
        }
    
    /************************************************************************************//**
    *   \brief This gets the admin options from the database, but by using the option id.   *
    *                                                                                       *
    *   \returns an associative array, with the option settings.                            *
    ****************************************************************************************/
    function getBMLTOptions_by_id ( $in_option_id,              ///< The option ID. It cannot be optional.
                                    &$out_option_number = null  ///< This can be optional. A reference to an integer that will be given the option number.
                                    )
        {
        $BMLTOptions = null;
        
        if ( isset ( $out_option_number ) )
            {
            $out_option_number = 0;
            }
        
        if ( function_exists ( 'get_option' ) )
            {
            $count = $this->get_num_options ( );
            
            // We sort through the available options, looking for the ID.
            for ( $i = 1; $i <= $count; $i++ )
                {
                $option_number = '';
                
                if ( $i > 1 )   // We do this, for compatibility with older options.
                    {
                    $option_number = "_$i";
                    }
                
                $name = self::$adminOptionsName.$option_number;
                $temp_BMLTOptions = get_option ( $name );
                
                if ( is_array ( $temp_BMLTOptions ) && count ( $temp_BMLTOptions ) )
                    {
                    if ( intval ($temp_BMLTOptions['id']) == intval($in_option_id) )
                        {
                        $BMLTOptions = $temp_BMLTOptions;
                        // If they want to know the ID, we supply it here.
                        if ( isset ( $out_option_number ) )
                            {
                            $out_option_number = $i;
                            }
                        break;
                        }
                    }
                else
                    {
                    echo "<!-- BMLTPlugin ERROR (getBMLTOptions_by_id)! No options found for $name! -->";
                    }
                }
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (getBMLTOptions_by_id)! No get_option()! -->";
            }
        
        return $BMLTOptions;
        }
    
    /************************************************************************************//**
    *   \brief This updates the database with the given options.                            *
    *                                                                                       *
    *   \returns a boolean. true if success.                                                *
    ****************************************************************************************/
    function setBMLTOptions (   $in_options,            ///< An array. The options to be stored. If no number is supplied in the next parameter, the ID is used.
                                $in_option_number = 1   ///< It is possible to store multiple options. If there is a number here, that will be used.
                            )
        {
        $ret = false;
        
        if ( function_exists ( 'update_option' ) )
            {
            if ( ($in_option_number == null) || (intval($in_option_number) < 1) || (intval($in_option_number) > ($this->get_num_options ( ) + 1)) )
                {
                $in_option_number = 0;
                $this->getBMLTOptions_by_id ( $in_options['id'], $in_option_number );
                }
            
            if ( intval ( $in_option_number ) > 0 )
                {
                $option_number = null;
                // If they want a certain option number, then it needs to be greater than 1, and within the number we have assigned (We can also increase by 1).
                if ( (intval ( $in_option_number ) > 1) && (intval ( $in_option_number ) <= ($this->get_num_options ( ) + 1)) )
                    {
                    $option_number = '_'.intval( $in_option_number );
                    }
                $in_option_number = (intval ( $in_option_number ) > 1) ? intval ( $in_option_number ) : 1;
                
                $name = self::$adminOptionsName.$option_number;
                
                // If this is a new option, then we also update the admin 2 options, incrementing the number of servers.
                if ( intval ( $in_option_number ) == ($this->get_num_options ( ) + 1) )
                    {
                    $in_options['id'] = abs (function_exists ( 'microtime' ) ? intval(microtime(true) * 10000) : ((time() * 1000) + intval(rand(0, 999))));   // This gives the option a unique slug
                    $admin2Options = array ('num_servers' => intval( $in_option_number ));
    
                    $this->setAdmin2Options ( $admin2Options );
                    }
                
                update_option ( $name, $in_options );
                
                $ret = true;
                }
            else
                {
                echo "<!-- BMLTPlugin ERROR (setBMLTOptions)! The option number ($in_option_number) is out of range! -->";
                }
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (setBMLTOptions)! No update_option()! -->";
            }
            
        return $ret;
        }
        
    /************************************************************************************//**
    *   \brief This gets the admin 2 options from the database.                             *
    *                                                                                       *
    *   \returns an associative array, with the option settings.                            *
    ****************************************************************************************/
    function getAdmin2Options ( )
        {
        $bmlt2_BMLTOptions = null;
        
        if ( function_exists ( 'get_option' ) )
            {
            $bmlt2_BMLTOptions = array ('num_servers' => 1  ///< This is how many servers we start with (1)
                                        );
            // We have a special set of options for version 2.
            $old_BMLTOptions = get_option ( self::$admin2OptionsName );
            
            if ( is_array ( $old_BMLTOptions ) && count ( $old_BMLTOptions ) )
                {
                foreach ( $old_BMLTOptions as $key => $value )
                    {
                    $bmlt2_BMLTOptions[$key] = $value;
                    }
                }
            
            $this->setAdmin2Options ( $bmlt2_BMLTOptions );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (getAdmin2Options)! No get_option()! -->";
            }
            
        return $bmlt2_BMLTOptions;
        }
    
    /************************************************************************************//**
    *   \brief This updates the database with the given options (Admin2 options).           *
    *                                                                                       *
    *   \returns a boolean. true if success.                                                *
    ****************************************************************************************/
    function setAdmin2Options ( $in_options ///< An array. The options to be stored.
                                )
        {
        $ret = false;
        
        if ( function_exists ( 'update_option' ) )
            {
            update_option ( self::$admin2OptionsName, $in_options );
            $ret = true;
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (setAdmin2Options)! No update_option()! -->";
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Gets the number of active options.                                           *
    *                                                                                       *
    *   \returns an integer. The number of options.                                         *
    ****************************************************************************************/
    function get_num_options ( )
        {
        $ret = 1;
        $opts = $this->getAdmin2Options();
        if ( isset ( $opts['num_servers'] ) )
            {
            $ret = intval ( $opts['num_servers'] );
            }
        else    // If the options weren't already set, we create them now.
            {
            $opts = array ( 'num_servers' => 1 );
            $this->setAdmin2Options ( $opts );
            }
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Makes a new set of options, set as default.                                  *
    *                                                                                       *
    *   \returns An integer. The index of the options (It will always be the number of      *
    *   initial options, plus 1. Null if failed.                                            *
    ****************************************************************************************/
    function make_new_options ( )
        {
        $opt = $this->getBMLTOptions ( -1 );
        $ret = null;
        
        // If we successfully get the options, we save them, in order to 
        if ( is_array ( $opt ) && count ( $opt ) )
            {
            $ret = $this->get_num_options ( );
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (make_new_options)! Failed to create new options! -->";
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Deletes the options by ID.                                                   *
    *                                                                                       *
    *   \returns a boolean. true if success.                                                *
    ****************************************************************************************/
    function delete_options_by_id ( $in_option_id   ///< The ID of the option to delete.
                                    )
        {
        $ret = false;
        
        $option_num = 0;
        $this->getBMLTOptions_by_id ( $in_option_id, $option_num ); // We just want the option number.
        
        if ( $option_num > 0 )  // If it's 1, we'll let the next function register the error.
            {
            $ret = $this->delete_options ( $option_num );
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Deletes the indexed options.                                                 *
    *                                                                                       *
    *   This is a bit of a delicate operation, because we need to re-index all of the other *
    *   options, beyond the one being deleted.                                              *
    *                                                                                       *
    *   You cannot delete the first options (1), if they are the only ones.                 *
    *                                                                                       *
    *   \returns a boolean. true if success.                                                *
    ****************************************************************************************/
    function delete_options ( $in_option_number /**<    The index of the option to delete.
                                                        It can be 1 -> the number of available options.
                                                        For safety's sake, this cannot be optional.
                                                        We cannot delete the first (primary) option if there are no others.
                                                */
                            )
        {
        $first_num = intval ( $in_option_number );

        $ret = false;
        
        if ( first_num )
            {
            if ( function_exists ( 'delete_option' ) )
                {
                $last_num = $this->get_num_options ( );
                
                if ( (($first_num > 1) && ($first_num <= $last_num )) || (($first_num == 1) && ($last_num > 1)) )
                    {
                    /*
                        OK. At this point, we know which option we'll be deleting. The way we "delete"
                        the option is to cascade all the ones after it down, and then we delete the last one.
                        If this is the last one, then there's no need for a cascade, and we simply delete it.
                    */
                    
                    for ( $i = $first_num; $i < $last_num; $i++ )
                        {
                        $opt = $this->getBMLTOptions ( $i + 1 );
                        $this->setBMLTOptions ( $opt, $i );
                        }
                    
                    $option_number = "_$last_num";
                    
                    // Delete the selected option
                    $option_name = self::$adminOptionsName.$option_number;
                    
                    delete_option ( $option_name );
                    
                    // This actually decrements the number of available options.
                    $admin2Options = array ('num_servers' => $last_num - 1);
    
                    $this->setAdmin2Options ( $admin2Options );
                    $ret = true;
                    }
                else
                    {
                    if ( $first_num > 1 )
                        {
                        echo "<!-- BMLTPlugin ERROR (delete_options)! Option request number out of range! It must be between 1 and $last_num -->";
                        }
                    elseif ( $first_num == 1 )
                        {
                        echo "<!-- BMLTPlugin ERROR (delete_options)! You can't delete the last option! -->";
                        }
                    else
                        {
                        echo "<!-- BMLTPlugin ERROR (delete_options)! -->";
                        }
                    }
                }
            else
                {
                echo "<!-- BMLTPlugin ERROR (delete_options)! no delete_option()! -->";
                }
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (delete_options)! Option request number ($first_num) out of range! -->";
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *                               PAGE DISPLAY FUNCTIONS                                  *
    ****************************************************************************************/
        
    /************************************************************************************//**
    *   \brief This will return the HTML for one sheet of options in the admin page.        *
    *                                                                                       *
    *   \returns The XHTML to be displayed.                                                 *
    ****************************************************************************************/
    function display_options_sheet ($in_options_index = 1,  ///< The options index. If not given, the first (main) ones are used.
                                    $display_mode = 'none'  ///< If this page is to be displayed, make it 'block'.
                                    )
        {
        $ret = '';
        $in_options_index = intval ( $in_options_index );
        
        if ( ($in_options_index < 1) || ($in_options_index > $this->get_num_options()) )
            {
            echo "<!-- BMLTPlugin Warning (display_options_sheet)! $in_options_index is out of range! Using the first options. -->";
            $in_options_index = 1;
            }
        
        $options = $this->getBMLTOptions ( $in_options_index );
        
        if ( is_array ( $options ) && count ( $options ) && isset ( $options['id'] ) )
            {

            $ret .= '<div class="BMLTPlugin_option_sheet" id="BMLTPlugin_option_sheet_'.$in_options_index.'_div" style="display:'.htmlspecialchars ( $display_mode ).'">';
                $ret .= '<h2 class="BMLTPlugin_option_id_h2">'.self::process_text ( self::$local_options_settings_id_prompt ).htmlspecialchars ( intval ( $options['id'] ) ).'</h2>';
                $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                    $id = 'BMLTPlugin_option_sheet_name_'.$in_options_index;
                    $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_name_label ).'</label>';
                        $string = (isset ( $options['setting_name'] ) && $options['setting_name'] ? $options['setting_name'] : self::process_text ( self::$local_options_no_name_string ) );
                    $ret .= '<input class="BMLTPlugin_option_sheet_line_name_text" id="'.htmlspecialchars ( $id ).'" type="text" value="'.htmlspecialchars ( $string ).'"';
                    $ret .= ' onfocus="BMLTPlugin_ClickInText(this.id,\''.self::process_text ( self::$local_options_no_name_string ).'\',false)"';
                    $ret .= ' onblur="BMLTPlugin_ClickInText(this.id,\''.self::process_text ( self::$local_options_no_name_string ).'\',true)"';
                    $ret .= ' onchange="BMLTPlugin_DirtifyOptionSheet()" onkeyup="BMLTPlugin_DirtifyOptionSheet()" />';
                $ret .= '</div>';
                $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                    $id = 'BMLTPlugin_option_sheet_root_server_'.$in_options_index;
                    $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_rootserver_label ).'</label>';
                        $string = (isset ( $options['root_server'] ) && $options['root_server'] ? $options['root_server'] : self::process_text ( self::$local_options_no_root_server_string ) );
                    $ret .= '<input class="BMLTPlugin_option_sheet_line_root_server_text" id="'.htmlspecialchars ( $id ).'" type="text" value="'.htmlspecialchars ( $string ).'"';
                    $ret .= ' onfocus="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_root_server_string).'\',false)"';
                    $ret .= ' onblur="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_root_server_string).'\',true)"';
                    $ret .= ' onchange="BMLTPlugin_DirtifyOptionSheet()" onkeyup="BMLTPlugin_DirtifyOptionSheet()" />';
                    $ret .= '<div class="BMLTPlugin_option_sheet_Test_Button_div">';
                        $ret .= '<input type="button" value="'.self::process_text ( self::$local_options_test_server ).'" onclick="BMLTPlugin_TestRootUri_call()" title="'.self::process_text ( self::$local_options_test_server_tooltip ).'" />';
                        $ret .= '<div class="BMLTPlugin_option_sheet_NEUT" id="BMLTPlugin_option_sheet_indicator_'.$in_options_index.'"></div>';
                        $ret .= '<div class="BMLTPlugin_option_sheet_Version" id="BMLTPlugin_option_sheet_version_indicator_'.$in_options_index.'"></div>';
                    $ret .= '</div>';
                $ret .= '</div>';
                $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                    $id = 'BMLTPlugin_option_sheet_new_search_'.$in_options_index;
                    $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_new_search_label ).'</label>';
                        $string = (isset ( $options['bmlt_new_search_url'] ) && $options['bmlt_new_search_url'] ? $options['bmlt_new_search_url'] : self::process_text ( self::$local_options_no_new_search_string ) );
                    $ret .= '<input class="BMLTPlugin_option_sheet_line_new_search_text" id="'.htmlspecialchars ( $id ).'" type="text" value="'.htmlspecialchars ( $string ).'"';
                    $ret .= ' onfocus="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_new_search_string).'\',false)"';
                    $ret .= ' onblur="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_new_search_string).'\',true)"';
                    $ret .= ' onchange="BMLTPlugin_DirtifyOptionSheet()" onkeyup="BMLTPlugin_DirtifyOptionSheet()" />';
                $ret .= '</div>';
                $dir_res = opendir ( dirname ( __FILE__ ).'/themes' );
                if ( $dir_res )
                    {
                    $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                        $id = 'BMLTPlugin_option_sheet_theme_'.$in_options_index;
                        $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_theme_prompt ).'</label>';
                        $ret .= '<select id="'.htmlspecialchars ( $id ).'" onchange="BMLTPlugin_DirtifyOptionSheet()">';
                            while ( false !== ( $file_name = readdir ( $dir_res ) ) )
                                {
                                if ( !preg_match ( '/^\./', $file_name ) && is_dir ( dirname ( __FILE__ ).'/themes/'.$file_name ) )
                                    {
                                    $ret .= '<option value="'.htmlspecialchars ( $file_name ).'"';
                                    if ( $file_name == $options['theme'] )
                                        {
                                        $ret .= ' selected="selected"';
                                        }
                                    $ret .= '>'.htmlspecialchars ( $file_name ).'</option>';
                                    }
                                }
                        $ret .= '</select>';
                    $ret .= '</div>';
                    }
                $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                    $id = 'BMLTPlugin_option_sheet_additional_css_'.$in_options_index;
                    $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_more_styles_label ).'</label>';
                    $ret .= '<textarea class="BMLTPlugin_option_sheet_additional_css_textarea" id="'.htmlspecialchars ( $id ).'" onchange="BMLTPlugin_DirtifyOptionSheet()">';
                    $ret .= htmlspecialchars ( $options['additional_css'] );
                    $ret .= '</textarea>';
                $ret .= '</div>';
                $ret .= '<fieldset class="BMLTPlugin_option_sheet_old_settings_fieldset">';
                    $ret .= '<legend class="BMLTPlugin_gmap_caveat_legend">'.self::process_text ( self::$local_options_gkey_caveat ).'</legend>';
                    $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                        $id = 'BMLTPlugin_option_sheet_gkey_'.$in_options_index;
                        $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_gkey_label ).'</label>';
                            $string = (isset ( $options['gmaps_api_key'] ) && $options['gmaps_api_key'] ? $options['gmaps_api_key'] : self::process_text ( self::$local_options_no_gkey_string ) );
                        $ret .= '<input class="BMLTPlugin_option_sheet_line_gkey_text" id="'.htmlspecialchars ( $id ).'" type="text" value="'.htmlspecialchars ( $string ).'"';
                        $ret .= ' onfocus="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_gkey_string).'\',false)"';
                        $ret .= ' onblur="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_gkey_string).'\',true)"';
                        $ret .= ' onchange="BMLTPlugin_DirtifyOptionSheet()" onkeyup="BMLTPlugin_DirtifyOptionSheet()" />';
                    $ret .= '</div>';
                    $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                        $id = 'BMLTPlugin_option_sheet_initial_view_'.$in_options_index;
                        $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_initial_view_prompt ).'</label>';
                        $ret .= '<select id="'.htmlspecialchars ( $id ).'" onchange="BMLTPlugin_DirtifyOptionSheet()">';
                            foreach ( self::$local_options_initial_view as $value => $prompt )
                                {
                                $ret .= '<option value="'.htmlspecialchars ( $value ).'"';
                                if ( $value == $options['bmlt_initial_view'] )
                                    {
                                    $ret .= ' selected="selected"';
                                    }
                                $ret .= '>'.self::process_text ( $prompt ).'</option>';
                                }
                        $ret .= '</select>';
                    $ret .= '</div>';
                    $ret .= '<div class="BMLTPlugin_option_sheet_line_div BMLTPlugin_special_check_div">';
                        $id = 'BMLTPlugin_option_sheet_push_down_'.$in_options_index;
                        $ret .= '<input class="BMLTPlugin_special_check" type="checkbox" id="'.htmlspecialchars ( $id ).'" onclick="BMLTPlugin_DirtifyOptionSheet()"';
                            if ( $options['push_down_more_details'] == '1' )
                                {
                                $ret .= ' checked="checked"';
                                }
                        $ret .= ' />';
                        $ret .= '<label class="BMLTPlugin_special_check_label" for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_push_down_checkbox_label ).'</label>';
                    $ret .= '</div>';
                $ret .= '</fieldset>';
            $ret .= '</div>';
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (display_options_sheet)! Options not found for $in_options_index! -->";
            }
        
        return $ret;
        }
        
    /************************************************************************************//**
    *   \brief Checks to see if the current user is authorized.                             *
    *                                                                                       *
    *   \returns a boolean. True if the user is authorized.                                 *
    ****************************************************************************************/
    static function user_authorized ( )
        {
        $ret = false;
        
        if ( $current_user->user_level >  7 )
            {
            $ret = true;
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Loads a parameter list.                                                      *
    *                                                                                       *
    *   \returns a string, containing the joined parameters.                                *
    ****************************************************************************************/
    static function get_params ( $in_array )
        {
        $my_params = '';

        foreach ( $in_array as $key => $value )
            {
            if ( isset ( $in_array['direct_simple'] ) || (!isset ( $in_array['direct_simple'] ) && $key != 'switcher') )    // We don't propagate switcher.
                {
                if ( is_array ( $value ) )
                    {
                    foreach ( $value as $val )
                        {
                        if ( is_array ( $val ) )
                            {
                            $val = join ( ',', $val );
                            }
                        $my_params .= '&'.urlencode ( $key ) ."[]=". urlencode ( $val );
                        }
                    $key = null;
                    }
                
                if ( $key )
                    {
                    $my_params .= '&'.urlencode ( $key );
                    
                    if ( $value )
                        {
                        $my_params .= "=". urlencode ( $value );
                        }
                    }
                }
            }
        
        return $my_params;
        }
    
    /************************************************************************************//**
    *   \brief Loads the parameter list.                                                    *
    ****************************************************************************************/
    function load_params ( )
        {
        $this->my_params = self::get_params ( $this->my_http_vars );
        }
    
    /************************************************************************************//**
    *                               THE WORDPRESS CALLBACKS                                 *
    ****************************************************************************************/
        
    /************************************************************************************//**
    *   \brief Handles the WP callback.                                                     *
    *                                                                                       *
    *   This function is called after the page has loaded its custom fields, so we can      *
    *   figure out which settings we're using. If the settings support mobiles, and the UA  *
    *   indicates this is a mobile phone, we redirect the user to our fast mobile handler.  *
    ****************************************************************************************/
    function wp_handler ( )
        {
        if ( isset ( $this->my_http_vars['BMLTPlugin_mobile_ajax_router'] ) )
            {
            $options = $this->getBMLTOptions_by_id ( $this->my_http_vars['bmlt_settings_id'] );
            $uri = $options['root_server'].'/'.$this->my_http_vars['request'];
            ob_end_clean(); // Just in case we are in an OB
            die ( bmlt_satellite_controller::call_curl ( $uri ) );
            }
        else
            {
            if ( isset ( $this->my_http_vars['BMLTPlugin_mobile'] ) )
                {
                $ret = $this->BMLTPlugin_fast_mobile_lookup ();
    
                $url = self::get_plugin_path();
                
                ob_end_clean(); // Just in case we are in an OB
                
                $handler = null;
                
                if ( zlib_get_coding_type() === false )
                    {
                    $handler = "ob_gzhandler";
                    }
                
                ob_start($handler);
                    echo $ret;
                ob_end_flush();
                die ( );
                }
            else
                {
                $page_id = null;
                $page = get_page($page_id);
                $option_id = intval ( preg_replace ( '/\D/', '', trim ( get_post_meta ( $page->ID, 'bmlt_settings_id', true ) ) ) );
                
                if ( !$option_id ) // If a setting was not already applied, we search for a custom field.
                    {
                    if ( isset ( $this->my_http_vars['bmlt_settings_id'] ) && intval ( $this->my_http_vars['bmlt_settings_id'] ) )
                        {
                        $option_id = intval ( $this->my_http_vars['bmlt_settings_id'] );
                        }
                    }
                
                if ( !$option_id ) // All else fails, we use the first setting (default).
                    {
                    $options = $this->getBMLTOptions ( 1 );
                    $option_id = $options['id'];
                    }
                
                $options = $this->getBMLTOptions_by_id ( $option_id );
        
                $this->load_params ( );
            
                if ( isset ( $this->my_http_vars['redirect_ajax'] ) && $this->my_http_vars['redirect_ajax'] )
                    {
                    $url = $options['root_server']."/client_interface/xhtml/index.php?switcher=RedirectAJAX$this->my_params";
                    ob_end_clean(); // Just in case we are in an OB
                    die ( bmlt_satellite_controller::call_curl ( $url ) );
                    }
                elseif ( isset ( $this->my_http_vars['direct_simple'] ) )
                    {
                    $root_server = $options['root_server']."/client_interface/simple/index.php";
                    $params = urldecode ( $this->my_http_vars['search_parameters'] );
                    $url = "$root_server?switcher=GetSearchResults&".$params;
                    $result = bmlt_satellite_controller::call_curl ( $url );
                    $result = preg_replace ( '|\<a |', '<a rel="nofollow external" ', $result );
                    // What all this does, is pick out the single URI in the search parameters string, and replace the meeting details link with it.
                    if ( preg_match ( '|&single_uri=|', $this->my_http_vars['search_parameters'] ) )
                        {
                        $single_uri = '';
                        $sp = explode ( '&', $this->my_http_vars['search_parameters'] );
                        foreach ( $sp as $s )
                            {
                            if ( preg_match ( '|single_uri=|', $s ) )
                                {
                                list ( $key, $single_uri ) = explode ( '=', $s );
                                break;
                                }
                            }
                        if ( $single_uri )
                            {
                            $result = preg_replace ( '|\<a [^>]*href="'.preg_quote($options['root_server']).'.*?single_meeting_id=(\d+)[^>]*>|', "<a rel=\"nofollow\" title=\"".self::process_text (self::$local_single_meeting_tooltip)."\" href=\"".$single_uri."=$1&amp;supports_ajax=yes\">", $result );
                            }
                        $result = preg_replace ( '|\<a rel="external"|','<a rel="nofollow external" title="'.self::process_text (self::$local_gm_link_tooltip).'"', $result );
                        }
                    ob_end_clean(); // Just in case we are in an OB
                    die ( $result );
                    }
                elseif ( isset ( $this->my_http_vars['result_type_advanced'] ) && ($this->my_http_vars['result_type_advanced'] == 'booklet') )
                    {
                    $uri =  $options['root_server']."/local_server/pdf_generator/?list_type=booklet$this->my_params";
                    ob_end_clean(); // Just in case we are in an OB
                    header ( "Location: $uri" );
                    die();
                    }
                elseif ( isset ( $this->my_http_vars['result_type_advanced'] ) && ($this->my_http_vars['result_type_advanced'] == 'listprint') )
                    {
                    $uri =  $options['root_server']."/local_server/pdf_generator/?list_type=listprint$this->my_params";
                    ob_end_clean(); // Just in case we are in an OB
                    header ( "Location: $uri" );
                    die();
                    }
                }
            }
        }
    
    /************************************************************************************//**
    *   \brief This does any admin actions necessary.                                       *
    ****************************************************************************************/
    function admin_init ( )
        {
        if ( isset ( $this->my_http_vars['BMLTPlugin_Save_Settings_AJAX_Call'] ) )
            {
            $ret = 0;
            
            if ( isset ( $this->my_http_vars['BMLTPlugin_set_options'] ) )
                {
                $ret = 1;
                
                $num_options = $this->get_num_options();
                
                for ( $i = 1; $i <= $num_options; $i++ )
                    {
                    $options = $this->getBMLTOptions ( $i );
                    
                    if ( is_array ( $options ) && count ( $options ) )
                        {
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_name_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_name_'.$i] ) )
                                {
                                $options['setting_name'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_name_'.$i] );
                                }
                            else
                                {
                                $options['setting_name'] = '';
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_root_server_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_root_server_'.$i] ) )
                                {
                                $options['root_server'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_root_server_'.$i] );
                                }
                            else
                                {
                                $options['root_server'] = self::$default_rootserver;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_new_search_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_new_search_'.$i] ) )
                                {
                                $options['bmlt_new_search_url'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_new_search_'.$i] );
                                }
                            else
                                {
                                $options['bmlt_new_search_url'] = self::$default_new_search;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_initial_view_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_initial_view_'.$i] ) )
                                {
                                $options['bmlt_initial_view'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_initial_view_'.$i] );
                                }
                            else
                                {
                                $options['bmlt_initial_view'] = self::$default_initial_view;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_theme_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_theme_'.$i] ) )
                                {
                                $options['theme'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_theme_'.$i] );
                                }
                            else
                                {
                                $options['theme'] = self::$default_theme;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_gkey_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_gkey_'.$i] ) )
                                {
                                $options['gmaps_api_key'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_gkey_'.$i] );
                                }
                            else
                                {
                                $options['gmaps_api_key'] = self::$default_gkey;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_additional_css_'.$i] ) )
                            {
                            if ( trim ( $this->my_http_vars['BMLTPlugin_option_sheet_additional_css_'.$i] ) )
                                {
                                $options['additional_css'] = trim ( $this->my_http_vars['BMLTPlugin_option_sheet_additional_css_'.$i] );
                                }
                            else
                                {
                                $options['additional_css'] = self::$default_additional_css;
                                }
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_sheet_push_down_'.$i] ) )
                            {
                            $options['push_down_more_details'] = $this->my_http_vars['BMLTPlugin_option_sheet_push_down_'.$i];
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_latitude_'.$i] ) && floatVal ( $this->my_http_vars['BMLTPlugin_option_latitude_'.$i] ) )
                            {
                            $options['map_center_latitude'] = floatVal ( $this->my_http_vars['BMLTPlugin_option_latitude_'.$i] );
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_longitude_'.$i] ) && floatVal ( $this->my_http_vars['BMLTPlugin_option_longitude_'.$i] ) )
                            {
                            $options['map_center_longitude'] = floatVal ( $this->my_http_vars['BMLTPlugin_option_longitude_'.$i] );
                            }
                        
                        if ( isset ( $this->my_http_vars['BMLTPlugin_option_zoom_'.$i] ) && intval ( $this->my_http_vars['BMLTPlugin_option_zoom_'.$i] ) )
                            {
                            $options['map_zoom'] = floatVal ( $this->my_http_vars['BMLTPlugin_option_zoom_'.$i] );
                            }
                        
                        if ( !$this->setBMLTOptions ( $options, $i ) )
                            {
                            $ret = 0;
                            break;
                            }
                        }
                    }
                }
            
            ob_end_clean(); // Just in case we are in an OB
            die ( strVal ( $ret ) );
            }
        elseif ( isset ( $this->my_http_vars['BMLTPlugin_AJAX_Call'] ) )
            {
            $ret = '0';
            
            if ( isset ( $this->my_http_vars['BMLTPlugin_AJAX_Call_Check_Root_URI'] ) )
                {
                $uri = trim ( $this->my_http_vars['BMLTPlugin_AJAX_Call_Check_Root_URI'] );
                
                $test = new bmlt_satellite_controller ( $uri );
                
                if ( $uri && ($uri != self::$local_options_no_root_server_string ) && $test instanceof bmlt_satellite_controller )
                    {
                    if ( !$test->get_m_error_message() )
                        {
                        $ret = trim($test->get_server_version());
                        }
                    }
                }
            
            ob_end_clean(); // Just in case we are in an OB
            die ( $ret );
            }
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function standard_head ( )
        {
        $load_head = false;   // This is a throwback. It prevents the GM JS from being loaded if there is no directly specified settings ID.
        $head_content = "<!-- Added by the BMLT plugin 2.0. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
       // This is how we figure out which options we'll be using.
        
        if ( !$this->my_option_id ) // If a setting was not already applied, we search for a custom field.
            {
            $page_id = null;
            $page = get_page($page_id);
            $this->my_option_id = intval ( preg_replace ( '/\D/', '', trim ( get_post_meta ( $page->ID, 'bmlt_settings_id', true ) ) ) );
            }

        if ( !$this->my_option_id ) // If a setting was not already applied, we search for a custom field.
            {
            if ( isset ( $this->my_http_vars['bmlt_settings_id'] ) && intval ( $this->my_http_vars['bmlt_settings_id'] ) )
                {
                $this->my_option_id = intval ( $this->my_http_vars['bmlt_settings_id'] );
                }
            }
        
        if ( !$this->my_option_id ) // All else fails, we use the first setting (default).
            {
            $options = $this->getBMLTOptions ( 1 );
            $this->my_option_id = $options['id'];
            global $wp_query;
            $page_obj_id = $wp_query->get_queried_object_id();
            if ( $page_obj_id ) // In the old version, the standard BMLT window could not be shown in posts; only pages.
                {
                $page_obj = get_page ( $page_obj_id );
                if ( $page_obj && (preg_match ( "/\[\[\s?BMLT\s?\]\]/", $page_obj->post_content ) || preg_match ( "/\<\!\-\-\s?BMLT\s?\-\-\>/", $page_obj->post_content )) )
                    {
                    $load_head = true;
                    }
                }
            }
        else
            {
            $load_head = true;
            }
        
        // If you specify the bmlt_mobile custom field in this page (not post), then it can force the browser to redirect to a mobile handler.
        // The value of bmlt_mobile must be the settings ID of the server you want to handle the mobile content.
        // Post redirectors are also handled, but at this point, only the page will be checked.
        $page_id = null;
        $page = get_page($page_id);
        $support_mobile = intval ( preg_replace ( '/\D/', '', trim ( get_post_meta ( $page->ID, 'bmlt_mobile', true ) ) ) );
        if ( $support_mobile && !isset ( $this->my_http_vars['BMLTPlugin_mobile'] ) && (self::mobile_sniff_ua ($this->my_http_vars) != 'xhtml') )
            {
            $mobile_options = $this->getBMLTOptions_by_id ( $support_mobile );
            }
        else
            {
            $support_mobile = null;
            }
        
        $options = $this->getBMLTOptions_by_id ( $this->my_option_id );

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
        
        $url = self::get_plugin_path();
        
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

//die ( '<pre>'.htmlspecialchars ( print_r ( $head_content, true ) ).'</pre>' );        
        echo $head_content;
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function admin_head ( )
        {
        $this->standard_head ( );   // We start with the standard stuff.
        
        $head_content = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';  // Load the Google Maps stuff for our map.
        
        if ( function_exists ( 'plugins_url' ) )
            {
            $head_content .= '<link rel="stylesheet" type="text/css" href="';
            
            $url = self::get_plugin_path();
            
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

        $in_the_content = $this->display_old_popup_search ( $in_the_content, $count );
        
        if ( !$count )
            {
            $in_the_content = $this->display_old_search ( $in_the_content, $count );
            }
        
        return $in_the_content;
        }
        
    /************************************************************************************//**
    *   \brief This is a function that filters the content, and replaces a portion with the *
    *   "classic" search.                                                                   *
    *                                                                                       *
    *   \returns a string, containing the content.                                          *
    ****************************************************************************************/
    function display_old_search ($in_content,      ///< This is the content to be filtered.
                                 &$out_count       ///< This is set to 1, if a substitution was made.
                                 )
        {
        if ( preg_match ( "/(<p[^>]*>)*?\[\[\s?BMLT\s?\]\](<\/p[^>]*>)*?/i", $in_content ) || preg_match ( "/(<p[^>]*>)*?\<\!\-\-\s?BMLT\s?\-\-\>(<\/p[^>]*>)*?/i", $in_content ) )
            {
            $display = '';

            
            $options = $this->getBMLTOptions_by_id ( $this->my_option_id );
            
            $root_server_root = $options['root_server'];

            if ( $root_server_root && $options['gmaps_api_key'] )
                {
                $root_server = $root_server_root."/client_interface/xhtml/index.php";
    
                $pid = get_page_uri(get_the_ID());
                
                $plink = get_permalink ( get_the_ID() );
                
                $menu = '';
                    
                if ( $pid && !isset ( $this->my_http_vars['search_form'] ) )
                    {
                    if ( $options['bmlt_new_search_url'] )
                        {
                        $plink = $options['bmlt_new_search_url'];
                        }
                    $menu = '<div class="bmlt_menu_div no_print"><a rel="nofollow" href="'.htmlspecialchars($plink).'">'.self::process_text ( self::$local_menu_new_search_text ).'</a></div>';
                    }
                
                if ( isset ( $this->my_http_vars['search_form'] ) )
                    {
                    $map_center = "&search_spec_map_center=".$options['map_center_latitude'].",".$options['map_center_longitude'].",".$options['map_zoom'];
                    $uri = "$root_server?switcher=GetSimpleSearchForm$this->my_params$map_center";
                    $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                    }
                elseif ( isset ( $this->my_http_vars['single_meeting_id'] ) && $this->my_http_vars['single_meeting_id'] )
                    {
                    $uri = "$root_server?switcher=GetOneMeeting&single_meeting_id=".$this->my_params;
                    $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                    }
                elseif ( isset ( $this->my_http_vars['do_search'] ) )
                    {
                    $uri = "$root_server?switcher=GetSearchResults".$this->my_params;
                    $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                    }
 
                $the_new_content = str_replace ( '###ROOT_SERVER###', $root_server_root, self::$local_no_js_warning ).$the_new_content;
                $the_new_content = '<div id="bmlt_container_div" class="bmlt_container_div"><table id="bmlt_container" cellspacing="0" cellpadding="0" class="bmlt_container_table"><tbody class="BMLTPlugin_container_tbody"><tr class="BMLTPlugin_container_tr"><td class="BMLTPlugin_container_td">'.$menu.'<div class="bmlt_content_div">'.$the_new_content.'</div>'.$menu.'</td></tr></tbody></table></div>';
                // We only allow one instance per page.
                $count = 0;

                $in_content = preg_replace ( "/(<p[^>]*>)*?\<\!\-\-\s?BMLT\s?\-\-\>(<\/p[^>]*>)*?/i", $the_new_content, $in_content, 1, $count );
                
                if ( !$count )
                    {
                    $in_content = preg_replace ( "/(<p[^>]*>)*?\[\[\s?BMLT\s?\]\](<\/p[^>]*>)*?/i", $the_new_content, $in_content, 1 );
                    }
                
                $url = self::get_plugin_path();
                
                $head_content .= htmlspecialchars ( $url.'themes/'.$options['theme'].'/' );
                
	            $in_content = str_replace ( $options['root_server'].'/client_interface/xhtml/../../themes/default/html/images/Throbber.gif', "$url/themes/".$options['theme']."/images/Throbber.gif", $in_content);
                }
            else
                {
                $the_new_content = self::process_text ( self::$local_not_enough_for_old_style );
                $count = 0;

                $in_content = preg_replace ( "/(<p[^>]*>)*?\<\!\-\-\s?BMLT\s?\-\-\>(<\/p[^>]*>)*?/i", $the_new_content, $in_content, 1, $count );
                
                if ( !$count )
                    {
                    $in_content = preg_replace ( "/(<p[^>]*>)*?\[\[\s?BMLT\s?\]\](<\/p[^>]*>)*?/i", $the_new_content, $in_content, 1 );
                    }
                }
            }
        
        return $in_content;
        }
        
    /************************************************************************************//**
    *   \brief This is a function that filters the content, and replaces a portion with the *
    *   "simple" search                                                                     *
    *                                                                                       *
    *   \returns a string, containing the content.                                          *
    ****************************************************************************************/
    function display_simple_search ($in_content      ///< This is the content to be filtered.
                                    )
        {
        $options = $this->getBMLTOptions_by_id ( $this->my_option_id );
        
        $root_server_root = $options['root_server'];

        if ( $root_server_root )
            {
            // This stupid thing is because WP is nice enough to mess up the ampersands.
            $in_content = str_replace ( '&#038;', '&', $in_content );
            $matches = array();
            while ( preg_match ( '|\<!\-\-\s?BMLT_SIMPLE\s?\((.*?)\)\s?\-\-\>|i', $in_content, $matches ) )
                {
				$uri = $root_server_root."/client_interface/simple/index.php?".$matches[1];
                $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                $in_content = preg_replace('|\<!\-\-\s?BMLT_SIMPLE\s?\((.*?)\)\s?\-\-\>|i', $the_new_content, $in_content, 1 );
                $matches = array();
                }
            
            while ( preg_match ( '|\[\[\s?BMLT_SIMPLE\s?\((.*?)\)\s?\]\]|i', $in_content, $matches ) )
                {
				$uri = $root_server_root."/client_interface/simple/index.php?".$matches[1];
                $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                $in_content = preg_replace('|\[\[\s?BMLT_SIMPLE\s?\((.*?)\)\s?\]\]|i', $the_new_content, $in_content, 1 );
                $matches = array();
                }
            }
        
        return $in_content;
        }
        
    /************************************************************************************//**
    *   \brief This is a function that filters the content, and replaces a portion with the *
    *   "popup" search, if provided by the 'bmlt_simple_searches' custom field.             *
    *                                                                                       *
    *   \returns a string, containing the content.                                          *
    ****************************************************************************************/
    function display_old_popup_search ( $in_content,      ///< This is the content to be filtered.
                                        &$out_count       ///< This is set to 1, if a substitution was made.
                                        )
        {
        if ( preg_match ( "/(<p[^>]*>)*?\[\[\s?SIMPLE_SEARCH_LIST\s?\]\](<\/p[^>]*>)*?/i", $in_content ) || preg_match ( "/(<p[^>]*>)*?\<\!\-\-\s?SIMPLE_SEARCH_LIST\s?\-\-\>(<\/p[^>]*>)*?/i", $in_content ) )
            {
            $text = get_post_meta ( get_the_ID(), 'bmlt_simple_searches', true );
            $display .= '';
            if ( $text )
                {
                $text_ar = explode ( "\n", $text );
                
                if ( is_array ( $text_ar ) && count ( $text_ar ) )
                    {
                    $display .= '<noscript>'.self::process_text ( self::$local_noscript ).'</noscript>';
                    $display .= '<div id="interactive_form_div" class="interactive_form_div" style="display:none"><form action="#" onsubmit="return false"><div>';
                    $display .= '<label class="meeting_search_select_label" for="meeting_search_select">Find Meetings:</label> ';
                    $display .= '<select id="meeting_search_select"class="simple_search_list" onchange="BMLTPlugin_simple_div_filler (this.value,this.options[this.selectedIndex].text);this.options[this.options.length-1].disabled=(this.selectedIndex==0)">';
                    $display .= '<option disabled="disabled" selected="selected">'.self::process_text ( self::$local_select_search ).'</option>';
                    $lines_max = count ( $text_ar );
                    $lines = 0;
                    while ( $lines < $lines_max )
                        {
                        $line['parameters'] = trim($text_ar[$lines++]);
                        $line['prompt'] = trim($text_ar[$lines++]);
                        if ( $line['parameters'] && $line['prompt'] )
                            {
                            $uri = $_SERVER['PHP_SELF'].'?bmlt_settings_id='.$this->my_option_id.'&amp;direct_simple&amp;search_parameters='.urlencode ( $line['parameters'] );
                            $display .= '<option value="'.$uri.'">'.__($line['prompt']).'</option>';
                            }
                        }
                    $display .= '<option disabled="disabled"></option>';
                    $display .= '<option disabled="disabled" value="">'.self::process_text ( self::$local_clear_search ).'</option>';
                    $display .= '</select></div></form>';
                    
                    $display .= '<script type="text/javascript">';
                    $display .= 'document.getElementById(\'interactive_form_div\').style.display=\'block\';';
                    $display .= 'document.getElementById(\'meeting_search_select\').selectedIndex=0;';

                    $options = $this->getBMLTOptions_by_id ( $this->my_option_id );
                    $url = self::get_plugin_path();
                    $img_url .= htmlspecialchars ( $url.'themes/'.$options['theme'].'/images/' );
                    
                    $display .= "var c_g_BMLTPlugin_images = '$img_url';";
                    $display .= '</script>';
                    $display .= '<div id="simple_search_container"></div></div>';
                    }
                }
            
            // We only allow one instance per page.
            $count = 0;
            
            $in_content = preg_replace ( "/(<p[^>]*>)*?\<\!\-\-\s?SIMPLE_SEARCH_LIST\s?\-\-\>(<\/p[^>]*>)*?/i", $display, $in_content, 1, $count );
            
            if ( !$count )
                {
                $in_content = preg_replace ( "/(<p[^>]*>)*?\[\[\s?SIMPLE_SEARCH_LIST\s?\]\](<\/p[^>]*>)*?/i", $display, $in_content, 1 );
                }
            }
        
        return $in_content;
        }
       
    /************************************************************************************//**
    *   \brief Presents the admin menu options.                                             *
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
    *   \brief This does any admin actions necessary.                                       *
    ****************************************************************************************/
    function process_admin_page ( &$out_option_number   ///< If an option number needs to be selected, it is set here.
                                )
        {
        $out_option_number = 1;
        
        $timing = self::$local_options_success_time;    // Success is a shorter fade, but failure is longer.
        $ret = '<div id="BMLTPlugin_Message_bar_div" class="BMLTPlugin_Message_bar_div">';
            if ( isset ( $this->my_http_vars['BMLTPlugin_create_option'] ) )
                {
                $out_option_number = $this->make_new_options ( );
                if ( $out_option_number )
                    {
                    $new_options = $this->getBMLTOptions ( $out_option_number );
                    $def_options = $this->getBMLTOptions ( 1 );
                    
                    $new_options = $def_options;
                    unset ( $new_options['setting_name'] );
                    unset ( $new_options['id'] );
                    unset ( $new_options['theme'] );
                    $this->setBMLTOptions ( $new_options, $out_option_number );
                    
                    $ret .= '<h2 id="BMLTPlugin_Fader" class="BMLTPlugin_Message_bar_success">';
                        $ret .= self::process_text ( self::$local_options_create_success );
                    $ret .= '</h2>';
                    }
                else
                    {
                    $timing = self::$local_options_failure_time;
                    $ret .= '<h2 id="BMLTPlugin_Fader" class="BMLTPlugin_Message_bar_fail">';
                        $ret .= self::process_text ( self::$local_options_create_failure );
                    $ret .= '</h2>';
                    }
                }
            elseif ( isset ( $this->my_http_vars['BMLTPlugin_delete_option'] ) )
                {
                $option_index = intval ( $this->my_http_vars['BMLTPlugin_delete_option'] );
        
                if ( $this->delete_options ( $option_index ) )
                    {
                    $ret .= '<h2 id="BMLTPlugin_Fader" class="BMLTPlugin_Message_bar_success">';
                        $ret .= self::process_text ( self::$local_options_delete_success );
                    $ret .= '</h2>';
                    }
                else
                    {
                    $timing = self::$local_options_failure_time;
                    $ret .= '<h2 id="BMLTPlugin_Fader" class="BMLTPlugin_Message_bar_fail">';
                        $ret .= self::process_text ( self::$local_options_delete_failure );
                    $ret .= '</h2>';
                    }
                }
            else
                {
                $ret .= '<h2 id="BMLTPlugin_Fader" class="BMLTPlugin_Message_bar_fail">&nbsp;</h2>';
                }
            $ret .= '<script type="text/javascript">g_BMLTPlugin_TimeToFade = '.$timing.';BMLTPlugin_StartFader()</script>';
        $ret .= '</div>';
        return $ret;
        }
        
    /************************************************************************************//**
    *   \brief Presents the admin page.                                                     *
    ****************************************************************************************/
    function admin_page ( )
        {
        $selected_option = 1;
        $process_html = $this->process_admin_page($selected_option);
        $options_coords = array();

        $html = '<div class="BMLTPlugin_option_page" id="BMLTPlugin_option_page_div">';
            $html .= '<noscript>'.self::process_text ( self::$local_noscript ).'</noscript>';
            $html .= '<div id="BMLTPlugin_options_container" style="display:none">';    // This is displayed using JavaScript.
                $html .= '<h1 class="BMLTPlugin_Admin_h1">'.self::process_text ( self::$local_options_title ).'</h1>';
                $html .= $process_html;
                $html .= '<form id="BMLTPlugin_sheet_form" action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $this->my_http_vars['page']).'" method="get" onsubmit="function(){return false}">';
                    $html .= '<fieldset class="BMLTPlugin_option_fieldset" id="BMLTPlugin_option_fieldset">';
                        $html .= '<legend id="BMLTPlugin_legend" class="BMLTPlugin_legend">';
                            $count = $this->get_num_options();
                                
                            if ( $count > 1 )
                                {
                                $html .= '<select id="BMLTPlugin_legend_select" onchange="BMLTPlugin_SelectOptionSheet(this.value,'.$count.')">';
                                    for ( $i = 1; $i <= $count; $i++ )
                                        {
                                        $options = $this->getBMLTOptions ( $i );
                                        
                                        if ( is_array ( $options ) && count ( $options ) && isset ( $options['id'] ) )
                                            {
                                            $options_coords[$i] = array ( 'lat' => $options['map_center_latitude'], 'lng' => $options['map_center_longitude'], 'zoom' => $options['map_zoom'] );
                                            
                                            $html .= '<option id="BMLTPlugin_option_sel_'.$i.'" value="'.$i.'"';
                                            
                                            if ( $i == $selected_option )
                                                {
                                                $html .= ' selected="selected"';
                                                }
                                            
                                            $html .= '>';
                                                if ( isset ( $options['setting_name'] ) && $options['setting_name'] )
                                                    {
                                                    $html .= htmlspecialchars ( $options['setting_name'] );
                                                    }
                                                else
                                                    {
                                                    $html .= self::process_text ( self::$local_options_prefix ).$i;
                                                    }
                                            $html .= '</option>';
                                            }
                                        else
                                            {
                                            echo "<!-- BMLTPlugin ERROR (admin_page)! Options not found for $i! -->";
                                            }
                                        }
                                $html .= '</select>';
                                }
                            elseif ( $count == 1 )
                                {
                                $options = $this->getBMLTOptions ( 1 );
                                $options_coords[1] = array ( 'lat' => $options['map_center_latitude'], 'lng' => $options['map_center_longitude'], 'zoom' => $options['map_zoom'] );
                                if ( isset ( $options['setting_name'] ) && $options['setting_name'] )
                                    {
                                    $html .= htmlspecialchars ( $options['setting_name'] );
                                    }
                                else
                                    {
                                    $html .= self::process_text ( self::$local_options_prefix ).'1';
                                    }
                                }
                            else
                                {
                                echo "<!-- BMLTPlugin ERROR (admin_page)! No options! -->";
                                }
                        $html .= '</legend>';
                        for ( $i = 1; $i <= $count; $i++ )
                            {
                            $html .= $this->display_options_sheet ( $i, (($i == $selected_option) ? 'block' : 'none') );
                            }
                    $html .= '</fieldset>';
                $html .= '</form>';
                $html .= '<div class="BMLTPlugin_toolbar_line_bottom">';
                    $html .= '<form action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $this->my_http_vars['page']).'" method="get" onsubmit="function(){return false}">';
                    if ( $count > 1 )
                        {
                        $html .= '<div class="BMLTPlugin_toolbar_button_line_left">';
                            $html .= '<script type="text/javascript">';
                                $html .= "var c_g_delete_confirm_message='".self::process_text ( self::$local_options_delete_option_confirm )."';";
                            $html .= '</script>';
                            $html .= '<input type="button" id="BMLTPlugin_toolbar_button_del" class="BMLTPlugin_delete_button" value="'.self::process_text ( self::$local_options_delete_option ).'" onclick="BMLTPlugin_DeleteOptionSheet()" />';
                        $html .= '</div>';
                        }
                    
                    $html .= '<div class="BMLTPlugin_toolbar_button_line_right">';
                        $html .= '<input id="BMLTPlugin_toolbar_button_save" type="button" value="'.self::process_text ( self::$local_options_save ).'" onclick="BMLTPlugin_SaveOptions()" />';
                    $html .= '</div>';
                    $html .= '</form>';
                    $html .= '<form action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $this->my_http_vars['page']).'" method="get">';
                    $html .= '<input type="hidden" name="page" value="'.htmlspecialchars ( $this->my_http_vars['page'] ).'" />';
                    $html .= '<input type="submit" id="BMLTPlugin_toolbar_button_new" class="BMLTPlugin_create_button" name="BMLTPlugin_create_option" value="'.self::process_text ( self::$local_options_add_new ).'" />';
                    $html .= '</form>';
                $html .= '</div>';
                $html .= '<div class="BMLTPlugin_toolbar_line_map">';
                    $html .= '<h2 class="BMLTPlugin_map_label_h2">'.self::process_text ( self::$local_options_map_label ).'</h2>';
                    $html .= '<div class="BMLTPlugin_Map_Div" id="BMLTPlugin_Map_Div"></div>';
                    $html .= '<script type="text/javascript">';
                        $html .= "BMLTPlugin_DirtifyOptionSheet(true);";    // This sets up the "Save Changes" button as disabled.
                        // This is a trick I use to hide irrelevant content from non-JS browsers. The element is drawn, hidden, then uses JS to show. No JS, no element.
                        $html .= "document.getElementById('BMLTPlugin_options_container').style.display='block';";
                        $html .= "var c_g_BMLTPlugin_no_name = '".self::process_text ( self::$local_options_no_name_string )."';";
                        $html .= "var c_g_BMLTPlugin_no_root = '".self::process_text ( self::$local_options_no_root_server_string )."';";
                        $html .= "var c_g_BMLTPlugin_no_search = '".self::process_text ( self::$local_options_no_new_search_string )."';";
                        $html .= "var c_g_BMLTPlugin_root_canal = '".self::$local_options_url_bad."';";
                        $html .= "var c_g_BMLTPlugin_success_message = '".self::process_text ( self::$local_options_save_success )."';";
                        $html .= "var c_g_BMLTPlugin_failure_message = '".self::process_text ( self::$local_options_save_failure )."';";
                        $html .= "var c_g_BMLTPlugin_success_time = ".intval ( self::$local_options_success_time ).";";
                        $html .= "var c_g_BMLTPlugin_failure_time = ".intval ( self::$local_options_failure_time ).";";
                        $html .= "var c_g_BMLTPlugin_unsaved_prompt = '".self::process_text ( self::$local_options_unsaved_message )."';";
                        $html .= "var c_g_BMLTPlugin_test_server_success = '".self::process_text ( self::$local_options_test_server_success )."';";
                        $html .= "var c_g_BMLTPlugin_test_server_failure = '".self::process_text ( self::$local_options_test_server_failure )."';";
                        $html .= "var c_g_BMLTPlugin_coords = new Array();";
                        $html .= "var g_BMLTPlugin_TimeToFade = ".intval ( self::$local_options_success_time ).";";
                        $html .= "var g_BMLTPlugin_no_gkey_string = '".self::process_text (self::$local_options_no_gkey_string)."';";
                        if ( is_array ( $options_coords ) && count ( $options_coords ) )
                            {
                            foreach ( $options_coords as $value )
                                {
                                $html .= 'c_g_BMLTPlugin_coords[c_g_BMLTPlugin_coords.length] = {';
                                $f = true;
                                foreach ( $value as $key2 => $value2 )
                                    {
                                    if ( $f )
                                        {
                                        $f = false;
                                        }
                                    else
                                        {
                                        $html .= ',';
                                        }
                                    $html .= "'".htmlspecialchars ( $key2 )."':";
                                    $html .= "'".htmlspecialchars ( $value2 )."'";
                                    }
                                $html .= '};';
                                }
                            }
                        $url = self::get_plugin_path();
                        $url = htmlspecialchars ( $url.'google_map_images' );
                        $html .= "var c_g_BMLTPlugin_admin_google_map_images = '$url';";
                        $html .= 'BMLTPlugin_admin_load_map();';
                    $html .= '</script>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
        
        echo $html;
        }
    
    /************************************************************************************//**
    *                              FAST MOBILE LOOKUP ROUTINES                              *
    *                                                                                       *
    *   Our mobile support is based on the fast mobile client. It has been adapted to fit   *
    *   into a WordPress environment.                                                       *
    ****************************************************************************************/

    /************************************************************************************//**
    *   \brief Checks the UA of the caller, to see if it should return XHTML Strict or WML. *
    *                                                                                       *
    *   NOTE: This is very, very basic. It is not meant to be a studly check, like WURFL.   *
    *                                                                                       *
    *   \returns A string. The DOCTYPE to be displayed.                                     *
    ****************************************************************************************/
    static function BMLTPlugin_select_doctype(  $in_http_vars   ///< The query variables
                                            )
    {
        $ret = '';
        
        function isDeviceWML1()
        {
            return BMLTPlugin::mobile_sniff_ua($in_http_vars) == 'wml';
        }
    
        function isDeviceWML2()
        {
            return BMLTPlugin::mobile_sniff_ua($in_http_vars) == 'xhtml_mp';
        }
            
        function isMobileDevice()
        {
            $language = BMLTPlugin::mobile_sniff_ua($in_http_vars);
            return ($language != 'xhtml') && ($language != 'smartphone');
        }
        
        // If we aren't deliberately forcing an emulation, we figure it out for ourselves.
        if ( !isset ( $in_http_vars['WML'] ) )
            {
            if ( isDeviceWML1() )
                {
                $in_http_vars['WML'] = 1;
                }
            elseif ( isDeviceWML2() )
                {
                $in_http_vars['WML'] = 2;
                }
            elseif ( isMobileDevice() )
                {
                $in_http_vars['WML'] = 1;
                }
            }
        
        // We may specify a mobile XHTML (WML 2) manually.
        if ( isset ( $in_http_vars['WML'] ) )
            {
            if ( $in_http_vars['WML'] == 2 )    // Use the XHTML MP header
                {
                $ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">';
                }
            else    // Default is WAP
                {
                $ret = '<'; // This is because some servers are dumb enough to interpret the embedded prolog as PHP delimiters.
                $ret .= '?xml version="1.0"?';
                $ret .= '><!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" "http://www.wapforum.org/DTD/wml_1.1.xml">';
                }
            }
        else
            {
            // We return a fully-qualified XHTML 1.0 Strict page.
            $ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            }
        
        if ( !isset ( $in_http_vars['WML'] ) || ($in_http_vars['WML'] != 1) )
            {
            $ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"';
            if ( !isset ( $in_http_vars['WML'] ) )
                {
                $ret .= ' lang="en"';
                }
            $ret .= '>';
            }
        else
            {
            $ret .= '<wml>';
            }
        
        $ret .= '<head>';
    
        return $ret;
    }
    
    /************************************************************************************//**
    *   \brief Output the necessary Javascript. This is only called for a "pure javascript" *
    *   do_search invocation (smartphone interactive map).                                  *
    *                                                                                       *
    *   \returns A string. The XHTML to be displayed.                                       *
    ****************************************************************************************/
    function BMLTPlugin_fast_mobile_lookup_javascript_stuff( $in_sensor = true  ///< A Boolean. If false, then we will invoke the API with the sensor set false. Default is true.
                                                            )
    {
        $ret = '';
        $sensor = $in_sensor ? 'true' : 'false';

        // Include the Google Maps API V3 files.
        $ret .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor='.$sensor.'"></script>';
        
        // Declare the various globals and display strings. This is how we pass strings to the JavaScript, as opposed to the clunky way we do it in the root server.
        $ret .= '<script type="text/javascript">';
        $ret .= 'var c_g_cannot_determine_location = \''.self::process_text ( self::$local_cannot_determine_location ).'\';';
        $ret .= 'var c_g_no_meetings_found = \''.self::process_text ( self::$local_mobile_fail_no_meetings ).'\';';
        $ret .= 'var c_g_server_error = \''.self::process_text ( self::$local_server_fail ).'\';';
        $ret .= 'var c_g_address_lookup_fail = \''.self::process_text ( self::$local_cant_find_address ).'\';';
        $ret .= 'var c_g_map_link_text = \''.self::process_text ( self::$local_map_link ).'\';';
        $ret .= 'var c_g_weekdays = [';
        $ret .= "'".self::process_text ( join ( "','", self::$local_weekdays ) )."'";
        $ret .= '];';
        $ret .= 'var c_g_formats = \''.self::process_text ( self::$local_formats ).'\';';
        $ret .= 'var c_g_Noon = \''.self::process_text ( self::$local_noon ).'\';';
        $ret .= 'var c_g_Midnight = \''.self::process_text ( self::$local_midnight ).'\';';
        $ret .= 'var c_g_debug_mode = '.( defined ( 'DEBUG_MODE' ) ? 'true' : 'false' ).';';
        $h = null;
        $m = null;
        list ( $h, $m ) = explode ( ':', date ( "G:i", time() - (self::$mobile_grace_time * 60) ) );
        $ret .= 'var c_g_hour = '.intval ( $h ).';';
        $ret .= 'var c_g_min = '.intval ( $m ).';';
        $ret .= 'var c_g_distance_prompt = \''.self::process_text ( self::$local_mobile_distance ).'\';';
        $ret .= 'var c_g_distance_units_are_km = '.((strtolower (self::$distance_units) == 'km' ) ? 'true' : 'false').';';
        $ret .= 'var c_g_distance_units = \''.((strtolower (self::$distance_units) == 'km' ) ? self::process_text ( self::$local_mobile_kilometers ) : self::process_text ( self::$local_mobile_miles ) ).'\';';
        $ret .= 'var c_BMLTPlugin_files_uri = \''.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?\';';
        $ret .= 'var c_bmlt_settings_id='.$this->my_http_vars['bmlt_settings_id'].';';        
        $url = self::get_plugin_path();

        $img_url = "$url/google_map_images";

        $img_url = htmlspecialchars ( $img_url );
        
        $ret .= "var c_g_BMLTPlugin_images = '$img_url';";
        $ret .= '</script>';
       
        if ( defined ( '_DEBUG_MODE_' ) ) // In debug mode, we use unoptimized versions of these files for easier tracking.
            {
            $ret .= '<script src="'.htmlspecialchars ( $url ).'fast_mobile_lookup.js" type="text/javascript"></script>';
            }
        else
            {
            $ret .= '<script src="'.htmlspecialchars ( $url ).'js_stripper.php?filename=fast_mobile_lookup.js" type="text/javascript"></script>';
            }

        return $ret;
    }
    
    /************************************************************************************//**
    *   \brief Output whatever header stuff is necessary for the available UA               *
    *                                                                                       *
    *   \returns A string. The XHTML to be displayed.                                       *
    ****************************************************************************************/
    function BMLTPlugin_fast_mobile_lookup_header_stuff()
    {
        $ret = '';
        $url = self::get_plugin_path();
            
        $ret .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';    // WML 1 only cares about the charset and cache.
        $ret .= '<meta http-equiv="Cache-Control" content="max-age=300"  />';               // Cache for 5 minutes.
        $ret .= '<meta http-equiv="Cache-Control" content="no-transform"  />';              // No Transforms.

        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )   // If full XHTML
            {
            // Various meta tags we need.
            $ret .= '<meta http-equiv="Content-Script-Type" content="text/javascript" />';      // Set the types for inline styles and scripts.
            $ret .= '<meta http-equiv="Content-Style-Type" content="text/css" />';
            $ret .= '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />'; // Make sure iPhone screens stay humble.
            
            $url = self::get_plugin_path();
            
            $options = $this->getBMLTOptions_by_id ( $this->my_http_vars['bmlt_settings_id'] );
            
            $url = htmlspecialchars ( $url.'themes/'.$options['theme'].'/' );
            
            if ( defined ( '_DEBUG_MODE_' ) ) // In debug mode, we use unoptimized versions of these files for easier tracking.
                {
                $ret .= '<link rel="stylesheet" media="all" href="'.$url.'fast_mobile_lookup.css" type="text/css" />';
                }
            else
                {
                $ret .= '<link rel="stylesheet" media="all" href="'.htmlspecialchars($url).'style_stripper.php?filename=fast_mobile_lookup.css" type="text/css" />';
                }
            
            // If we have a shortcut icon, set it here.
            if ( defined ('_SHORTCUT_LOC_' ) )
                {
                $ret .= '<link rel="SHORTCUT ICON" href="'.self::process_text ( _SHORTCUT_LOC_ ).'" />';
                }
            
            // Set the appropriate page title.
            if ( isset ( $this->my_http_vars['do_search'] ) )
                {
                $ret .= '<title>'.self::process_text ( $local_mobile_results_page_title ).'</title>';
                }
            else
                {
                $ret .= '<title>'.self::process_text ( $local_mobile_results_form_title ).'</title>';
                }
            }
        
        $ret .= '</head>';

        return $ret;
    }
    
    /************************************************************************************//**
    *   \brief Returns the XHTML/WML for the Map Search form. These are the three "fast     *
    *   lookup" links displayed at the top (Note display:none" in the style).               *
    *   This is to be revealed by JavaScript.                                               *
    *                                                                                       *
    *   \returns A string. The XHTML to be displayed.                                       *
    ****************************************************************************************/
    function BMLTPlugin_draw_map_search_form ()
    {
        $ret = '<div class="search_intro" id="hidden_until_js" style="display:none">';
            $ret .= '<h1 class="banner_h1">'.self::process_text ( self::$local_GPS_banner ).'</h1>';
            $ret .= '<h2 class="banner_h2">'.self::process_text ( self::$local_GPS_banner_subtext ).'</h2>';
            $ret .= '<div class="link_one_line"><a rel="nofollow" accesskey="1" href="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?BMLTPlugin_mobile&amp;do_search&amp;bmlt_settings_id='.intval($this->my_http_vars['bmlt_settings_id']).'">'.self::process_text ( self::$local_search_all ).'</a></div>';
            $ret .= '<div class="link_one_line"><a rel="nofollow" accesskey="2" href="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?BMLTPlugin_mobile&amp;do_search&amp;qualifier=today&amp;bmlt_settings_id='.intval($this->my_http_vars['bmlt_settings_id']).'">'.self::process_text ( self::$local_search_today ).'</a></div>';
            $ret .= '<div class="link_one_line"><a rel="nofollow" accesskey="3" href="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?BMLTPlugin_mobile&amp;do_search&amp;qualifier=tomorrow&amp;bmlt_settings_id='.intval($this->my_http_vars['bmlt_settings_id']).'">'.self::process_text ( self::$local_search_tomorrow ).'</a></div>';
            $ret .= '<hr class="meeting_divider_hr" />';
        $ret .= '</div>';
        
        return $ret;
    }
    
    /************************************************************************************//**
    *   \brief Returns the XHTML/WML for the Address Entry form                             *
    *                                                                                       *
    *   \returns A string. The XHTML to be displayed.                                       *
    ****************************************************************************************/
    function BMLTPlugin_draw_address_search_form ()
    {
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret = '<form class="address_input_form" method="get" action="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'"';
            if ( !isset ( $this->my_http_vars['WML'] ) )
                {
                // This fills the form with a "seed" text (standard accessibility practice). We do it this way, so we don't clutter the form if no JavaScript is available.
                $ret .= ' onsubmit="if((document.getElementById(\'address_input\').value==\'';
                $ret .= self::process_text ( self::$local_enter_an_address );
                $ret .= '\')||!document.getElementById(\'address_input\').value){alert(\''.self::process_text ( self::$local_enter_address_alert ).'\');document.getElementById(\'address_input\').focus();return false}else{if(document.getElementById(\'hidden_until_js\').style.display==\'block\'){document.getElementById(\'do_search\').value=\'1\'}}"';
                }
            $ret .= '>';
            $ret .= '<div class="search_address">';
            // The default, is we return a list. This is changed by JavaScript.
            $ret .= '<input type="hidden" name="BMLTPlugin_mobile" />';
            $ret .= '<input type="hidden" name="bmlt_settings_id" value="'.intval($this->my_http_vars['bmlt_settings_id']).'" />';
            $ret .= '<input type="hidden" name="do_search" id="do_search" value="the hard way" />';
            $ret .= '<h1 class="banner_h2">'.self::process_text ( self::$local_search_address_single ).'</h1>';
            if ( !isset ( $this->my_http_vars['WML'] ) )  // This is here to prevent WAI warnings.
                {
                $ret .= '<label for="address_input" style="display:none">'.self::process_text ( self::$local_enter_address_alert ).'</label>';
                }
            if ( isset ( $this->my_http_vars['WML'] ) )
                {
                $ret .= '<input type="hidden" name="WML" value="2" />';
                }
            }
        else
            {
            $ret = '<p>';   // WML rides the short bus.
            }
        
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret .= '<div class="address_top" id="address_input_line_wrapper">';
            $ret .= '<div class="link_one_line input_line_div" id="address_input_line_div">';
            if ( !isset ( $this->my_http_vars['WML'] ) )
                {
                $ret .= '<div class="link_one_line" id="hidden_until_js2" style="display:none">';
                $ret .= '<input type="checkbox" id="force_list_checkbox"';
                $ret .= ' onchange="if(this.checked){document.getElementById ( \'hidden_until_js\' ).style.display = \'none\';document.getElementById(\'address_input\').focus();}else{document.getElementById ( \'hidden_until_js\' ).style.display = \'block\'}" /><label for="force_list_checkbox"';
                $ret .= '> '.self::process_text ( self::$local_list_check ).'</label>';
                $ret .= '</div>';
                }
            $ret .= '</div>';
            }
            
        $ret .= '<input type="text" name="address"';
        
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret .= ' id="address_input" class="address_input" size="64" value=""';
            if ( !isset ( $this->my_http_vars['WML'] ) )
                {
                $ret .= ' onfocus="if(!this.value||(this.value==\''.self::process_text ( self::$local_enter_an_address ).'\'))this.value=\'\'"';
                $ret .= ' onkeydown="if(!this.value||(this.value==\''.self::process_text ( self::$local_enter_an_address ).'\'))this.value=\'\'"';
                $ret .= ' onblur="if(!this.value)this.value=\''.self::process_text ( self::$local_enter_an_address ).'\'"';
                }
            }
        else
            {
            $ret .= ' size="32" format="*m"';
            }
        
        $ret .= ' />';
        
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret .= '</div>';
            }
        else
            {
            $ret .= '</p>';
            }
        
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret .= '<div class="link_form_elements">';
            $ret .= '<div class="link_one_line">';
            $ret .= '<input checked="checked" id="search_all_days" type="radio" name="qualifier" value="" />';
            $ret .= '<label for="search_all_days"> '.self::process_text ( self::$local_search_all_address ).'</label>';
            $ret .= '</div>';
            $ret .= '<div class="link_one_line">';
            $ret .= '<input id="search_today" type="radio" name="qualifier" value="today" />';
            $ret .= '<label for="search_today"> '.self::process_text ( self::$local_search_today ).'</label>';
            $ret .= '</div>';
            $ret .= '<div class="link_one_line">';
            $ret .= '<input id="search_tomorrow" type="radio" name="qualifier" value="tomorrow" />';
            $ret .= '<label for="search_tomorrow"> '.self::process_text ( self::$local_search_tomorrow ).'</label>';
            $ret .= '</div>';
            $ret .= '</div>';
            $ret .= '<div class="link_one_line_submit">';
            if ( !isset ( $this->my_http_vars['WML'] ) )  // This silly thing is to prevent WAI warnings.
                {
                $ret .= '<label for="submit_button" style="display:none">'.self::process_text ( _SEARCH_SUBMIT_ ).'</label>';
                }
            $ret .= '<input id="submit_button" type="submit" value="'.self::process_text ( self::$local_search_submit_button ).'"';
            if ( !isset ( $this->my_http_vars['WML'] ) )
                {
                $ret .= ' onclick="if((document.getElementById(\'address_input\').value==\'';
                $ret .= self::process_text ( self::$local_enter_an_address );
                $ret .= '\')||!document.getElementById(\'address_input\').value){alert(\''.self::process_text ( self::$local_enter_address_alert ).'\');document.getElementById(\'address_input\').focus();return false}else{if(document.getElementById(\'hidden_until_js\').style.display==\'block\'){document.getElementById(\'do_search\').value=\'1\'}}"';
                }
            $ret .= ' />';
            $ret .= '</div>';
            $ret .= '</div>';
            $ret .= '</form>';
            }
        else
            {
            $ret .= '<p>';
            $ret .= '<select name="qualifier" value="">';
            $ret .= '<option value="">'.self::process_text ( self::$local_search_all_address ).'</option>';
            $ret .= '<option value="today">'.self::process_text ( self::$local_search_today ).'</option>';
            $ret .= '<option value="tomorrow">'.self::process_text ( self::$local_search_tomorrow ).'</option>';
            $ret .= '</select>';
            $ret .= '<anchor>';
            $ret .= '<go href="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'" method="get">';
            $ret .= '<postfield name="address" value="$(address)"/>';
            $ret .= '<postfield name="qualifier" value="$(qualifier)"/>';
            $ret .= '<postfield name="do_search" value="the hard way" />';
            $ret .= '<postfield name="WML" value="1" />';
            $ret .= '<postfield name="BMLTPlugin_mobile" value="1" />';
            $ret .= '<postfield name="bmlt_settings_id" value="'.intval($this->my_http_vars['bmlt_settings_id']).'" />';
            $ret .= '</go>';
            $ret .= self::process_text ( $local_search_submit_button );
            $ret .= '</anchor>';
            $ret .= '</p>';
            }
        
        return $ret;
    }

    /************************************************************************************//**
    *   \brief Renders one WML card                                                         *
    *                                                                                       *
    *   \returns A string. The WML 1.1 to be displayed.                                     *
    ****************************************************************************************/

    function BMLTPlugin_render_card (   $ret,                   ///< The current XHTML tally (so we can count it).
                                        $index,                 ///< The page index of the meeting.
                                        $count,                 ///< The total number of meetings.
                                        $meeting                ///< The meeting data.
                                    )
                            
    {
        $ret .= '<card id="card_'.$index.'" title="'.htmlspecialchars($meeting['meeting_name']).'">';
        

        if ( $count > 1 )
            {
            $next_card = null;
            $prev_card = null;
            $myself = null;
            $vars = array();
            
            unset ( $_REQUEST['access_card'] );
            
            foreach ( $_REQUEST as $name => $val )
                {
                $text = urlencode ( $name ).'='.urlencode ( $val );
                array_push ( $vars, $text );
                }
            
            $myself = htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?'.join ( '&amp;', $vars ).'&amp;access_card=';
        
            if ( $index < $count )
                {
                $next_card = $myself.strval($index + 1);
                }
            
            if ( $index > 1 )
                {
                $prev_card = $myself.strval($index - 1);
                }

            $ret .= '<p><table columns="3"><tr>';
            $ret .= '<td>';
            if ( $prev_card )
                {
                $ret .= '<small><anchor>'.self::process_text(self::$local_prev_card).'<go href="'.$prev_card.'"/></anchor></small>';
                }
            $ret .= '</td><td>&nbsp;</td><td>';
            if ( $next_card )
                {
                $ret .= '<small><anchor>'.self::process_text(self::$local_next_card).'<go href="'.$next_card.'"/></anchor></small>';
                }
            
            $ret .= '</td></tr></table></p>';
            }
    
        $ret .= '<p><big><strong>'.htmlspecialchars($meeting['meeting_name']).'</strong></big></p>';
        $ret .= '<p>'.self::process_text ( self::$local_weekdays[$meeting['weekday_tinyint']] ).' '.htmlspecialchars ( date ( 'g:i A', strtotime ( $meeting['start_time'] ) ) ).'</p>';
        if ( $meeting['location_text'] )
            {
            $ret .= '<p><b>'.htmlspecialchars ( $meeting['location_text'] ).'</b></p>';
            }
        
        $ret .= '<p>';
        if ( $meeting['location_street'] )
            {
            $ret .= htmlspecialchars ( $meeting['location_street'] );
            }
        
        if ( $meeting['location_neighborhood'] )
            {
            $ret .= ' ('.htmlspecialchars ( $meeting['location_neighborhood'] ).')';
            }
        $ret .= '</p>';
        
        if ( $meeting['location_municipality'] )
            {
            $ret .= '<p>'.htmlspecialchars ( $meeting['location_municipality'] );
        
            if ( $meeting['location_province'] )
                {
                $ret .= ', '.htmlspecialchars ( $meeting['location_province'] );
                }
            
            if ( $meeting['location_postal_code_1'] )
                {
                $ret .= ' '.htmlspecialchars ( $meeting['location_postal_code_1'] );
                }
            $ret .= '</p>';
            }
        
        $distance = null;
        
        if ( $meeting['distance_in_km'] )
            {
            $distance = round ( ((strtolower (self::$distance_units) == 'km') ? $meeting['distance_in_km'] : $meeting['distance_in_miles']), 1 );
            
            $distance = strval ($distance).' '.((strtolower (self::$distance_units) == 'km' ) ? self::process_text ( self::$local_mobile_kilometers ) : self::process_text ( self::$local_mobile_miles ) );

            $ret .= '<p><b>'.self::process_text ( self::$local_mobile_distance ).':</b> '.htmlspecialchars ( $distance ).'</p>';
            }
        
        $ret .= '<p><b>'.self::process_text ( self::$local_formats ).':</b> '.htmlspecialchars ( $meeting['formats'] ).'</p>';
        
        $ret .= '</card>';
        
        return $ret;
    }
    
    /************************************************************************************//**
    *   \brief Sorting Callback                                                             *
    *                                                                                       *
    *   This will sort meetings by weekday, then by distance, so the first meeting of any   *
    *   given weekday is the closest one, etc.                                              *
    *                                                                                       *
    *   \returns -1 if a < b, 1, otherwise.                                                 *
    ****************************************************************************************/
    function BMLTPlugin_sort_meetings_callback (    $in_a_meeting,  ///< These are meeting data arrays. The elements we'll be checking will be 'weekday_tinyint' and 'distance_in_XX'.
                                                    $in_b_meeting
                                                    )
    {
        $ret = 0;
        
        if ( $in_a_meeting['weekday_tinyint'] != $in_b_meeting['weekday_tinyint'] )
            {
            $ret = ($in_a_meeting['weekday_tinyint'] < $in_b_meeting['weekday_tinyint']) ? -1 : 1;
            }
        else
            {
            $dist_a = intval ( round ((self::$distance_units == 'M') ? $in_a_meeting['distance_in_miles'] : $in_a_meeting['distance_in_km'], 1) * 10 );
            $dist_b = intval ( round ((self::$distance_units == 'M') ? $in_b_meeting['distance_in_miles'] : $in_b_meeting['distance_in_km'], 1) * 10 );

            if ( $dist_a != $dist_b )
                {
                $ret = ($dist_a < $dist_b) ? -1 : 1;
                }
            else
                {
                $time_a = preg_replace ( '|:|', '', $in_a_meeting['start_time']);
                $time_b = preg_replace ( '|:|', '', $in_b_meeting['start_time']);
                $ret = ($time_a < $time_b) ? -1 : 1;
                }
            }
        
        return $ret;
    }
    

    /************************************************************************************//**
    *   \brief Runs the lookup.                                                             *
    *                                                                                       *
    *   \returns A string. The XHTML to be displayed.                                       *
    ****************************************************************************************/
    function BMLTPlugin_fast_mobile_lookup()
    {
        $ret = self::BMLTPlugin_select_doctype($this->my_http_vars);
        $ret .= $this->BMLTPlugin_fast_mobile_lookup_header_stuff();   // Add styles and/or JS, depending on the UA.
        $options = $this->getBMLTOptions_by_id ( $this->my_http_vars['bmlt_settings_id'] );
        
        // If we are running XHTML, then JavaScript works. Let's see if we can figure out where we are...
        // If the client can handle JavaScript, then the whole thing can be done with JS, and there's no need for the driver.
        // Also, if JS does not work, the form will ask us to do it "the hard way" (i.e. on the server).
        if ( $this->my_http_vars['address'] && isset ( $this->my_http_vars['do_search'] ) && (($this->my_http_vars['do_search'] == 'the hard way') || (isset ( $this->my_http_vars['WML'] ) && ($this->my_http_vars['WML'] == 1))) )
            {
            if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )   // Regular XHTML requires a body element.
                {
                $ret .= '<body>';
                }
            
            $this->my_driver->set_m_root_uri ( $options['root_server'] );
            $error = $this->my_driver->get_m_error_message();
            
            if ( $error )
                {
                ob_end_clean(); // Just in case we are in an OB
                die ( '<h1>ERROR (BMLTPlugin_fast_mobile_lookup: '.htmlspecialchars ( $error ).')</h1>' );
                }
            
            $qualifier = strtolower ( trim ( $this->my_http_vars['qualifier'] ) );
            
            // Do the search.
            
            if ( $this->my_http_vars['address'] )
                {
                $this->my_driver->set_current_transaction_parameter ( 'SearchString', $this->my_http_vars['address'] );
                $error_message = $this->my_driver->get_m_error_message();
                if ( $error_message )
                    {
                    $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                    }
                else
                    {
                    $this->my_driver->set_current_transaction_parameter ( 'StringSearchIsAnAddress', true );
                    $error_message = $this->my_driver->get_m_error_message();
                    if ( $error_message )
                        {
                        $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                        }
                    else
                        {
                        if ( $qualifier )
                            {
                            $weekdays = '';
                            $h = 0;
                            $m = 0;
                            $today = intval(date ( "w" )) + 1;
                            // We set the current time, minus the grace time. This allows us to be running late, yet still have the meeting listed.
                            list ( $h, $m ) = explode ( ':', date ( "G:i", time() - (self::$mobile_grace_time * 60) ) );
                            if ( $qualifier == 'today' )
                                {
                                $weekdays = strval ($today);
                                }
                            else
                                {
                                $weekdays = strval ( ($today < 7) ? $today + 1 : 1 );
                                }
                            $this->my_driver->set_current_transaction_parameter ( 'weekdays', array($weekdays) );
                            $error_message = $this->my_driver->get_m_error_message();
                            if ( $error_message )
                                {
                                $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                                }
                            else
                                {
                                if ( $h || $m )
                                    {
                                    $this->my_driver->set_current_transaction_parameter ( 'StartsAfterH', $h );
                                    $error_message = $this->my_driver->get_m_error_message();
                                    if ( $error_message )
                                        {
                                        $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                                        }
                                    else
                                        {
                                        $this->my_driver->set_current_transaction_parameter ( 'StartsAfterM', $m );
                                        $error_message = $this->my_driver->get_m_error_message();
                                        if ( $error_message )
                                            {
                                            $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                                            }
                                        }
                                    }
                                }
                            }
                        
                        if ( $error_message )
                            {
                            $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                            }
                        else
                            {
                            $this->my_driver->set_current_transaction_parameter ( 'SearchStringRadius', -10 );
                            $error_message = $this->my_driver->get_m_error_message();
                            if ( $error_message )
                                {
                                $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                                }
                            else    // The search is set up. Throw the switch, Igor! ...yeth...mawther....
                                {
                                $search_result = $this->my_driver->meeting_search();

                                $error_message = $this->my_driver->get_m_error_message();
                                if ( $error_message )
                                    {
                                    $ret .= self::process_text ( self::$local_server_fail ).' "'.htmlspecialchars ( $error_message ).'"';
                                    }
                                elseif ( isset ( $search_result ) && is_array ( $search_result ) && isset ( $search_result['meetings'] ) )
                                    {
                                    // Yes! We have valid search data!
                                    if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )   // Regular XHTML
                                        {
                                        $ret .= '<div class="multi_meeting_div">';
                                        }
                                    
                                    $index = 1;
                                    $count = count ( $search_result['meetings'] );
                                    usort ( $search_result['meetings'], 'BMLTPlugin_sort_meetings_callback' );
                                    if ( isset ( $_REQUEST['access_card'] ) && intval ( $_REQUEST['access_card'] ) )
                                        {
                                        $index = intval ( $_REQUEST['access_card'] );
                                        }
                                        
                                    if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )   // Regular XHTML
                                        {
                                        $index = 1;
                                        foreach ( $search_result['meetings'] as $meeting )
                                            {
                                            $ret .= '<div class="single_meeting_div">';
                                            $ret .= '<h1 class="meeting_name_h2">'.htmlspecialchars($meeting['meeting_name']).'</h1>';
                                            $ret .= '<p class="time_day_p">'.self::process_text ( self::$local_weekdays[$meeting['weekday_tinyint']] ).' ';
                                            $time = explode ( ':', $meeting['start_time'] );
                                            $am_pm = ' AM';
                                            $distance = null;
                                            
                                            if ( $meeting['distance_in_km'] )
                                                {
                                                $distance = round ( ((strtolower (self::$distance_units) == 'km') ? $meeting['distance_in_km'] : $meeting['distance_in_miles']), 1 );
                                                
                                                $distance = strval ($distance).' '.((strtolower (self::$distance_units) == 'km' ) ? self::process_text ( self::$local_mobile_kilometers ) : self::process_text ( self::$local_mobile_miles ) );
                                                }

                                            $time[0] = intval ( $time[0] );
                                            $time[1] = intval ( $time[1] );
                                            
                                            if ( ($time[0] == 23) && ($time[1] > 50) )
                                                {
                                                $ret .= self::process_text ( self::$local_midnight );
                                                }
                                            elseif ( ($time[0] == 12) && ($time[1] == 0) )
                                                {
                                                $ret .= self::process_text ( self::$local_noon );
                                                }
                                            else
                                                {
                                                if ( ($time[0] > 12) || (($time[0] == 12) && ($time[1] > 0)) )
                                                    {
                                                    $am_pm = ' PM';
                                                    }
                                                
                                                if ( $time[0] > 12 )
                                                    {
                                                    $time[0] -= 12;
                                                    }
                                            
                                                if ( $time[1] < 10 )
                                                    {
                                                    $time[1] = "0$time[1]";
                                                    }
                                                
                                                $ret .= htmlspecialchars ( $time[0].':'.$time[1].$am_pm );
                                                }
                                            
                                            $ret .= '</p>';
                                            if ( $meeting['location_text'] )
                                                {
                                                $ret .= '<p class="locations_text_p">'.htmlspecialchars ( $meeting['location_text'] ).'</p>';
                                                }
                                            
                                            $ret .= '<p class="street_p">';
                                            if ( $meeting['location_street'] )
                                                {
                                                $ret .= htmlspecialchars ( $meeting['location_street'] );
                                                }
                                            
                                            if ( $meeting['location_neighborhood'] )
                                                {
                                                $ret .= '<span class="neighborhood_span"> ('.htmlspecialchars ( $meeting['location_neighborhood'] ).')</span>';
                                                }
                                            $ret .= '</p>';
                                            
                                            if ( $meeting['location_municipality'] )
                                                {
                                                $ret .= '<p class="town_p">'.htmlspecialchars ( $meeting['location_municipality'] );
                                            
                                                if ( $meeting['location_province'] )
                                                    {
                                                    $ret .= '<span class="state_span">, '.htmlspecialchars ( $meeting['location_province'] ).'</span>';
                                                    }
                                                
                                                if ( $meeting['location_postal_code_1'] )
                                                    {
                                                    $ret .= '<span class="zip_span"> '.htmlspecialchars ( $meeting['location_postal_code_1'] ).'</span>';
                                                    }
                                                $ret .= '</p>';
                                                if ( !isset ( $this->my_http_vars['WML'] ) )
                                                    {
                                                    $ret .= '<p id="maplink_'.intval($meeting['id_bigint']).'" style="display:none">';
                                                    $url = '';

                                                    $comma = false;
                                                    if ( $meeting['meeting_name'] )
                                                        {
                                                        $url .= urlencode($meeting['meeting_name']);
                                                        $comma = true;
                                                        }
                                                        
                                                    if ( $meeting['location_text'] )
                                                        {
                                                        $url .= ($comma ? ',+' : '').urlencode($meeting['location_text']);
                                                        $comma = true;
                                                        }
                                                    
                                                    if ( $meeting['location_street'] )
                                                        {
                                                        $url .= ($comma ? ',+' : '').urlencode($meeting['location_street']);
                                                        $comma = true;
                                                        }
                                                    
                                                    if ( $meeting['location_municipality'] )
                                                        {
                                                        $url .= ($comma ? ',+' : '').urlencode($meeting['location_municipality']);
                                                        $comma = true;
                                                        }
                                                        
                                                    if ( $meeting['location_province'] )
                                                        {
                                                        $url .= ($comma ? ',+' : '').urlencode($meeting['location_province']);
                                                        }
                                                    
                                                    $url = 'http://maps.google.com/maps?q='.urlencode($meeting['latitude']).','.urlencode($meeting['longitude']) . '+(%22'.str_replace ( "%28", '-', str_replace ( "%29", '-', $url )).'%22)';
                                                    $url .= '&ll='.urlencode($meeting['latitude']).','.urlencode($meeting['longitude']);
                                                    $ret .= '<a rel="external nofollow" accesskey="'.$index.'" href="'.htmlspecialchars ( $url ).'" title="'.htmlspecialchars($meeting['meeting_name']).'">'.self::process_text ( self::$local_map_link ).'</a>';
                                                    $ret .= '<script type="text/javascript">document.getElementById(\'maplink_'.intval($meeting['id_bigint']).'\').style.display=\'block\';var c_BMLTPlugin_settings_id = '.htmlspecialchars ( $this->my_http_vars['bmlt_settings_id'] ).';</script>';

                                                    $ret .= '</p>';
                                                    }
                                                }
                                            
                                            if ( $distance )
                                                {
                                                $ret .= '<p class="distance_p"><strong>'.self::process_text ( self::$local_mobile_distance ).':</strong> '.htmlspecialchars ( $distance ).'</p>';
                                                }
                                                
                                            $ret .= '<p class="formats_p"><strong>'.self::process_text ( self::$local_formats ).':</strong> '.htmlspecialchars ( $meeting['formats'] ).'</p>';
                                            $ret .= '</div>';
                                            if ( $index++ < $count )
                                                {
                                                if ( !isset ( $this->my_http_vars['WML'] ) )
                                                    {
                                                    $ret .= '<hr class="meeting_divider_hr" />';
                                                    }
                                                else
                                                    {
                                                    $ret .= '<hr />';
                                                    }
                                                }
                                            }
                                        }
                                    else    // WML 1 (yuch) We do this, because we need to limit the size of the pages to fit simple phones.
                                        {
                                        $meetings = $search_result['meetings'];
                                        $indexed_array = array_values($meetings);
                                        $ret = $this->BMLTPlugin_render_card ( $ret, $index, $count, $indexed_array[$index - 1], false );
                                        }
                                    
                                    if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )   // Regular XHTML
                                        {
                                        $ret .= '</div>';
                                        }
                                    }
                                else
                                    {
                                    $ret .= '<h1 class="failed_search_h1';
                                    if ( isset ( $this->my_http_vars['WML'] ) && $this->my_http_vars['WML'] )   // We use a normally-positioned element in WML.
                                        {
                                        $ret .= '_wml';
                                        }
                                    $ret .= '">'.self::process_text(self::$local_mobile_fail_no_meetings).'</h1>';
                                    }
                                }
                            }
                        }
                    }
                }
            else
                {
                $ret .= '<h1 class="failed_search_h1">'.self::process_text(self::$local_enter_address_alert).'</h1>';
                }
            }
        elseif ( isset ( $this->my_http_vars['do_search'] ) && !((($this->my_http_vars['do_search'] == 'the hard way') || (isset ( $this->my_http_vars['WML'] ) && ($this->my_http_vars['WML'] == 1)))) )
            {
            $ret .= '<body id="search_results_body"';
            if ( !isset ( $this->my_http_vars['WML'] ) )
                {
                $ret .= ' onload="WhereAmI (\''.htmlspecialchars ( strtolower ( trim ( $this->my_http_vars['qualifier'] ) ) ).'\',\''.htmlspecialchars ( trim ( $this->my_http_vars['address'] ) ).'\')"';
                }
            $ret .= '>';

            $ret .= $this->BMLTPlugin_fast_mobile_lookup_javascript_stuff();

            $ret .= '<div id="location_finder" class="results_map_div">';
            
            $url = self::get_plugin_path();
            
            $throbber_loc .= htmlspecialchars ( $url.'themes/'.$options['theme'].'/images/Throbber.gif' );
            
            $ret .= '<div class="throbber_div"><img id="throbber" src="'.htmlspecialchars ( $throbber_loc ).'" alt="AJAX Throbber" /></div>';
            $ret .= '</div>';
            }
        else
            {
            if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
                {
                $ret .= '<body id="search_form_body"';
                if ( !isset ( $this->my_http_vars['WML'] ) )
                    {
                    $ret .= ' onload="if( (typeof ( navigator ) == \'object\' &amp;&amp; typeof ( navigator.geolocation ) == \'object\') || (window.blackberry &amp;&amp; blackberry.location.GPSSupported) || (typeof ( google ) == \'object\' &amp;&amp; typeof ( google.gears ) == \'object\') ){document.getElementById ( \'hidden_until_js\' ).style.display = \'block\';document.getElementById ( \'hidden_until_js2\' ).style.display = \'block\';};document.getElementById(\'address_input\').value=\''.self::process_text ( self::$local_enter_an_address ).'\'"';
                    }
                $ret .= '>';
                $ret .= '<div class="search_div"';
                if ( !isset ( $this->my_http_vars['WML'] ) )
                    {
                    $ret .= ' cellpadding="0" cellspacing="0" border="0"';
                    }
                $ret .= '>';
                $ret .= '<div class="GPS_lookup_row_div"><div>';
                if ( !isset ( $this->my_http_vars['WML'] ) )
                    {
                    $ret .= $this->BMLTPlugin_draw_map_search_form ();
                    }
                $ret .= '</div></div>';
                $ret .= '<div><div>';
                $ret .= $this->BMLTPlugin_draw_address_search_form();
                $ret .= '</div></div></div>';
                }
            else
                {
                $ret .= '<card title="'.self::process_text(_FORM_TITLE_).'">';
                $ret .= $this->BMLTPlugin_draw_address_search_form();
                $ret .= '</card>';
                }
            }
        
        if ( !isset ( $this->my_http_vars['WML'] ) || ($this->my_http_vars['WML'] != 1) )
            {
            $ret .= '</body>';  // Wrap up the page.
            $ret .= '</html>';
            if ( isset ( $this->my_http_vars['WML'] ) && ($this->my_http_vars['WML'] == 2) )
                {
                $ret = "<"."?xml version='1.0' encoding='UTF-8' ?".">".$ret;
                header ( 'Content-type: application/xhtml+xml' );
                }
            }
        else
            {
            $ret .= '</wml>';
            header ( 'Content-type: text/vnd.wap.wml' );
            }
        
        return $ret;
    }
};

/****************************************************************************************//**
*                                   MAIN CODE CONTEXT                                       *
********************************************************************************************/
global $BMLTPluginOp;

if ( !isset ( $BMLTPluginOp ) && class_exists ( "BMLTPlugin" ) )
    {
    $BMLTPluginOp = new BMLTPlugin();
    }
?>