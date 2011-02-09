<?php
/****************************************************************************************//**
* \file unit_test.php                                                                       *
* \brief A unit test harness for the BMLTPlugin class.                                      *
* \version 1.0.0                                                                            *
* \license Public Domain -No restrictions at all.                                           *
********************************************************************************************/
/****************************************************************************************//**
*   \file   bmlt-wordpress-satellite-plugin.php                                             *
*                                                                                           *
*   \brief  This is a WordPress plugin of a BMLT satellite client.                          *
*   \version 2.0.0                                                                          *
*                                                                                           *
Plugin Name: BMLT WordPress Satellite
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.
Version: 2.0
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
********************************************************************************************/

define ( '_DEBUG_MODE__', 1 );

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
    *       $default_push_down_more_details                                                 *
    *       $default_additional_css                                                         *
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
        
    /************************************************************************************//**
    *                           STATIC DATA MEMBERS (LOCALIZABLE)                           *
    ****************************************************************************************/
    
    /// These are used internal to the class, but can be localized
    static  $local_noscript = 'This will not work, because you do not have JavaScript active.';             ///< The string displayed in a <noscript> element.

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
    static  $local_options_gkey_caveat = 'This is only necessary for old-style BMLT implementations';  ///< This lets people know that this is not necessary for newer installs.
    
    /// These are for the actual search displays
    static  $local_select_search = 'Select a Quick Search';                 ///< Used for the "filler" in the quick search popup.
    static  $local_clear_search = 'Clear Search Results';                   ///< Used for the "Clear" item in the quick search popup.
    static  $local_menu_new_search_text = 'New Search';                     ///< For the new search menu.
    
    /************************************************************************************//**
    *                               STATIC DATA MEMBERS (MISC)                              *
    ****************************************************************************************/
    
    static  $local_options_success_time = 2000;                             ///< The number of milliseconds a success message is displayed.
    static  $local_options_failure_time = 5000;                             ///< The number of milliseconds a failure message is displayed.

    /************************************************************************************//**
    *                                   DYNAMIC DATA MEMBERS                                *
    ****************************************************************************************/
    
    var $my_driver = null;                                                  ///< This will contain an instance of the BMLT satellite driver class.
    
    /************************************************************************************//**
    *                                   FUNCTIONS/METHODS                                   *
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
    
    /************************************************************************************//**
    *   \brief Constructor. Enforces the SINGLETON, and sets up the callbacks.              *
    ****************************************************************************************/
    function __construct ()
        {
        if ( !isset ( self::$g_s_there_can_only_be_one ) || (self::$g_s_there_can_only_be_one === null) )
            {
            self::$g_s_there_can_only_be_one = $this;
            
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
                    add_action ( 'init', array ( self::get_plugin_object(), 'init' ) );
                    add_action ( 'admin_init', array ( self::get_plugin_object(), 'admin_init' ) );
                    add_action ( 'admin_menu', array ( self::get_plugin_object(), 'option_menu' ) );
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
            $in_string = htmlspecialchars ( __( $in_string ) );
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
            $BMLTOptions = array (  'root_server' => self::$default_rootserver,
                                    'map_center_latitude' => self::$default_map_center_latitude,
                                    'map_center_longitude' => self::$default_map_center_longitude,
                                    'map_zoom' => self::$default_map_zoom,
                                    'bmlt_new_search_url' => self::$default_new_search,
                                    'id' => (function_exists ( 'microtime' ) ? intval(microtime(true) * 10000) : time()),   // This gives the option a unique slug
                                    'gmap_key' => self::$default_gkey,
                                    'setting_name' => ''
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
                    $in_options['id'] = (function_exists ( 'microtime' ) ? intval(microtime(true) * 10000) : time());   // This gives the option a unique slug
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
                $ret .= '<h2 class="BMLTPlugin_option_id_h2">'.self::process_text ( self::$local_options_settings_id_prompt ).htmlspecialchars ( intVal ( $options['id'] ) ).'</h2>';
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
                $ret .= '<div class="BMLTPlugin_option_sheet_line_div">';
                    $ret .= '<div class="BMLTPlugin_gmap_caveat_div">'.self::process_text ( self::$local_options_gkey_caveat ).'</div>';
                    $id = 'BMLTPlugin_option_sheet_gkey_'.$in_options_index;
                    $ret .= '<label for="'.htmlspecialchars ( $id ).'">'.self::process_text ( self::$local_options_gkey_label ).'</label>';
                        $string = (isset ( $options['gmap_key'] ) && $options['gmap_key'] ? $options['gmap_key'] : self::process_text ( self::$local_options_no_gkey_string ) );
                    $ret .= '<input class="BMLTPlugin_option_sheet_line_gkey_text" id="'.htmlspecialchars ( $id ).'" type="text" value="'.htmlspecialchars ( $string ).'"';
                    $ret .= ' onfocus="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_gkey_string).'\',false)"';
                    $ret .= ' onblur="BMLTPlugin_ClickInText(this.id,\''.self::process_text (self::$local_options_no_gkey_string).'\',true)"';
                    $ret .= ' onchange="BMLTPlugin_DirtifyOptionSheet()" onkeyup="BMLTPlugin_DirtifyOptionSheet()" />';
                $ret .= '</div>';
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
    *                               THE WORDPRESS CALLBACKS                                 *
    ****************************************************************************************/
        
    /************************************************************************************//**
    *   \brief Called before anything else is run.                                          *
    *                                                                                       *
    *   This function will check for AJAX rerouters and for mobile rerouters. If it sees a  *
    *   need for one, it dies the script with the appropriate response. Otherwise, it just  *
    *   does nothing.                                                                       *
    ****************************************************************************************/
    function init ( )
        {
        $settings_id = intval ( trim ( get_post_meta ( get_the_ID(), 'bmlt_settings', true ) ) );
        
        if ( !$settings_id )
            {
            $options = $this->getBMLTOptions ( 1 );
            $settings_id = $options['id'];
            }
        
        $options = $this->getBMLTOptions_by_id ( $settings_id );

        $my_params = '';
		$my_http_vars = array_merge_recursive ( $_GET, $_POST );
        foreach ( $my_http_vars as $key => $value )
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
		
		if ( isset ( $my_http_vars['redirect_ajax'] ) && $my_http_vars['redirect_ajax'] )
			{
			$root_server = $options['root_server']."client_interface/xhtml/index.php";
			die ( bmlt_satellite_controller::call_curl ( "$root_server?switcher=RedirectAJAX".$my_params ) );
			}
        elseif ( isset ( $my_http_vars['direct_simple'] ) )
            {
            $settings_id = intval(trim($my_http_vars['direct_simple']));
            $options = $this->getBMLTOptions_by_id ( $settings_id );
            $url = $options['root_server'].'/client_interface/simple/index.php?direct_simple&switcher=GetSearchResults&'.$my_http_vars['search_parameters'];
            die ( bmlt_satellite_controller::call_curl ( $url ) );
            }
        elseif ( isset ( $my_http_vars['BMLTPlugin_AJAX_Call'] ) )
            {
            $ret = '0';
            
            if ( isset ( $my_http_vars['BMLTPlugin_AJAX_Call_Check_Root_URI'] ) )
                {
                $uri = trim ( $my_http_vars['BMLTPlugin_AJAX_Call_Check_Root_URI'] );
                
                $test = new bmlt_satellite_controller ( $uri );
                
                if ( $uri && ($uri != self::$local_options_no_root_server_string ) && $test instanceof bmlt_satellite_controller )
                    {
                    if ( !$test->get_m_error_message() )
                        {
                        $ret = trim($test->get_server_version());
                        }
                    }
                }
            
            die ( $ret );
            }
        }
        
    /************************************************************************************//**
    *   \brief This does any admin actions necessary.                                       *
    ****************************************************************************************/
    function admin_init ( )
        {
        if ( isset ( $_GET['BMLTPlugin_Save_Settings_AJAX_Call'] ) )
            {
            $ret = 0;
            
            if ( isset ( $_GET['BMLTPlugin_set_options'] ) )
                {
                $ret = 1;
                
                $num_options = $this->get_num_options();
                
                for ( $i = 1; $i <= $num_options; $i++ )
                    {
                    $options = $this->getBMLTOptions ( $i );
                    
                    if ( is_array ( $options ) && count ( $options ) )
                        {
                        if ( isset ( $_GET['BMLTPlugin_option_sheet_name_'.$i] ) )
                            {
                            if ( trim ( $_GET['BMLTPlugin_option_sheet_name_'.$i] ) )
                                {
                                $options['setting_name'] = trim ( $_GET['BMLTPlugin_option_sheet_name_'.$i] );
                                }
                            else
                                {
                                $options['setting_name'] = '';
                                }
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_sheet_root_server_'.$i] ) )
                            {
                            if ( trim ( $_GET['BMLTPlugin_option_sheet_root_server_'.$i] ) )
                                {
                                $options['root_server'] = trim ( $_GET['BMLTPlugin_option_sheet_root_server_'.$i] );
                                }
                            else
                                {
                                $options['root_server'] = '';
                                }
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_sheet_new_search_'.$i] ) )
                            {
                            if ( trim ( $_GET['BMLTPlugin_option_sheet_new_search_'.$i] ) )
                                {
                                $options['bmlt_new_search_url'] = trim ( $_GET['BMLTPlugin_option_sheet_new_search_'.$i] );
                                }
                            else
                                {
                                $options['bmlt_new_search_url'] = '';
                                }
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_sheet_gkey_'.$i] ) )
                            {
                            if ( trim ( $_GET['BMLTPlugin_option_sheet_gkey_'.$i] ) )
                                {
                                $options['gmap_key'] = trim ( $_GET['BMLTPlugin_option_sheet_gkey_'.$i] );
                                }
                            else
                                {
                                $options['gmap_key'] = '';
                                }
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_latitude_'.$i] ) && floatVal ( $_GET['BMLTPlugin_option_latitude_'.$i] ) )
                            {
                            $options['map_center_latitude'] = floatVal ( $_GET['BMLTPlugin_option_latitude_'.$i] );
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_longitude_'.$i] ) && floatVal ( $_GET['BMLTPlugin_option_longitude_'.$i] ) )
                            {
                            $options['map_center_longitude'] = floatVal ( $_GET['BMLTPlugin_option_longitude_'.$i] );
                            }
                        
                        if ( isset ( $_GET['BMLTPlugin_option_zoom_'.$i] ) && intVal ( $_GET['BMLTPlugin_option_zoom_'.$i] ) )
                            {
                            $options['map_zoom'] = floatVal ( $_GET['BMLTPlugin_option_zoom_'.$i] );
                            }
                        
                        if ( !$this->setBMLTOptions ( $options, $i ) )
                            {
                            $ret = 0;
                            break;
                            }
                        }
                    }
                }
            
            die ( strVal ( $ret ) );
            }
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function standard_head ( )
        {
        $head_content = "";
        
        if ( function_exists ( 'plugins_url' ) )
            {
            $head_content = "<!-- Added by the BMLTPlugin -->";
            $head_content .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';  // Load the Google Maps stuff for our map.
            $head_content .= '<link rel="stylesheet" type="text/css" href="';
            
            $url = '';
            if ( plugins_url() )
                {
                $url = plugins_url()."/bmlt-wordpress-satellite-plugin/";
                }
            elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
                {
                $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/";
                }
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE__' ) )
                {
                $head_content .= 'style_stripper.php?filename=';
                }
            
            $head_content .= 'styles.css" />';
            
            $head_content .= '<script type="text/javascript" src="';
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE__' ) )
                {
                $head_content .= 'js_stripper.php?filename=';
                }
            
            $head_content .= 'javascript.js"></script>';
            
            
            $settings_id = intval ( trim ( get_post_meta ( get_the_ID(), 'bmlt_settings', true ) ) );
            
            if ( !$settings_id )
                {
                $options = $this->getBMLTOptions ( 1 );
                $settings_id = $options['id'];
                }
            
            $options = $this->getBMLTOptions_by_id ( $settings_id );
            
            $root_server_root = $options['root_server'];

            if ( $root_server_root )
                {
                $root_server = $root_server_root."/client_interface/xhtml/index.php";
                $my_params = '';
                $my_http_vars = array_merge_recursive ( $_GET, $_POST );
                foreach ( $my_http_vars as $key => $value )
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
                
                $head_content .= bmlt_satellite_controller::call_curl ( "$root_server?switcher=GetHeaderXHTML".$my_params );
					
                $additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_ajax_div{position:static;margin:0;width:100%;}';
                $additional_css .= 'table#bmlt_container div.c_comdef_search_results_single_close_box_div{position:relative;left:100%;margin-left:-18px;}';
                $additional_css .= 'table#bmlt_container div#bmlt_contact_us_form_div{position:static;width:auto;margin:0;}';
                
                if ( $additional_css )
                    {
                    $head_content .= '<style type="text/css">'.preg_replace ( "|\s+|", " ", $additional_css ).'</style>';
                    }
                }
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (head)! No plugins_url()! -->";
            }
            
        echo $head_content;
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function admin_head ( )
        {
        $this->standard_head ( );   // We start with the standard stuff.
        
        $head_content = "";
        
        if ( function_exists ( 'plugins_url' ) )
            {
            $head_content .= '<link rel="stylesheet" type="text/css" href="';
            
            $url = '';
            if ( plugins_url() )
                {
                $url = plugins_url()."/bmlt-wordpress-satellite-plugin/";
                }
            elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
                {
                $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/";
                }
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE__' ) )
                {
                $head_content .= 'style_stripper.php?filename=';
                }
            
            $head_content .= 'admin_styles.css" />';
            
            $head_content .= '<script type="text/javascript" src="';
            
            $head_content .= htmlspecialchars ( $url );
            
            if ( !defined ('_DEBUG_MODE__' ) )
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
        $count = 0;

        $in_the_content = $this->display_simple_search ( $in_the_content, $count );
        
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
            
            $settings_id = intval ( trim ( get_post_meta ( get_the_ID(), 'bmlt_settings', true ) ) );
            
            if ( !$settings_id )
                {
                $options = $this->getBMLTOptions ( 1 );
                $settings_id = $options['id'];
                }
            
            $options = $this->getBMLTOptions_by_id ( $settings_id );
            
            $root_server_root = $options['root_server'];

            if ( $root_server_root )
                {
                $root_server = $root_server_root."/client_interface/xhtml/index.php";
    
                $pid = get_page_uri(get_the_ID());
                
                $plink = get_permalink ( get_the_ID() );
                
                $menu = '';
                    
                if ( $pid && !isset ( $_GET['search_form'] ) )
                    {
                    if ( $options['bmlt_new_search_url'] )
                        {
                        $plink = $options['bmlt_new_search_url'];
                        }
                    $menu = '<div class="bmlt_menu_div no_print"><a href="'.htmlspecialchars($plink).'">'.self::process_text ( self::$local_menu_new_search_text ).'</a></div>';
                    }
                
                $my_params = '';
                
                $my_http_vars = array_merge_recursive ( $_GET, $_POST );
                foreach ( $my_http_vars as $key => $value )
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
               
                if ( isset ( $my_http_vars['single_meeting_id'] ) && $my_http_vars['single_meeting_id'] )
                    {
                    $the_new_content = bmlt_satellite_controller::call_curl ( "$root_server?switcher=GetOneMeeting&single_meeting_id=".intVal ( $my_http_vars['single_meeting_id'] ) );
                    }
                elseif ( isset ( $my_http_vars['do_search'] ) )
                    {
                    $uri = "$root_server?switcher=GetSearchResults".$my_params;
                    $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                    }
                else
                    {
                    $map_center = "&search_spec_map_center=".$options['map_center_latitude'].",".$options['map_center_longitude'].",".$options['map_zoom'];
                    $uri = "$root_server?switcher=GetSimpleSearchForm$my_params$map_center";
                    $the_new_content = bmlt_satellite_controller::call_curl ( $uri );
                    }
                
                $the_new_content = '<table id="bmlt_container" class="bmlt_container_table"><tbody><tr><td>'.$menu.'<div class="bmlt_content_div">'.$the_new_content.'</div>'.$menu.'</td></tr></tbody></table>';
    
                // We only allow one instance per page.
                $count = 0;
                
                $in_content = preg_replace ( "/(<p[^>]*>)*?\<\!\-\-\s?BMLT\s?\-\-\>(<\/p[^>]*>)*?/", $the_new_content, $in_content, 1, $count );
                
                if ( !$count )
                    {
                    $in_content = preg_replace ( "/(<p[^>]*>)*?\[\[\s?BMLT\s?\]\](<\/p[^>]*>)*?/", $the_new_content, $in_content, 1 );
                    }
                }
            }
        
        return $in_content;
        }
        
    /************************************************************************************//**
    *   \brief This is a function that filters the content, and replaces a portion with the *
    *   "simple" search, if provided by the 'bmlt_simple_searches' custom field.            *
    *                                                                                       *
    *   \returns a string, containing the content.                                          *
    ****************************************************************************************/
    function display_simple_search ($in_content,      ///< This is the content to be filtered.
                                    &$out_count       ///< This is set to 1, if a substitution was made.
                                    )
        {
        if ( preg_match ( "/(<p[^>]*>)*?\[\[\s?SIMPLE_SEARCH_LIST\s?\]\](<\/p[^>]*>)*?/i", $in_content ) || preg_match ( "/(<p[^>]*>)*?\<\!\-\-\s?SIMPLE_SEARCH_LIST\s?\-\-\>(<\/p[^>]*>)*?/i", $in_content ) )
            {
            $text = get_post_meta ( get_the_ID(), 'bmlt_simple_searches', true );
            $display .= '';
            if ( $text )
                {
                $settings_id = intval ( trim ( get_post_meta ( get_the_ID(), 'bmlt_settings', true ) ) );
                
                if ( !$settings_id )
                    {
                    $options = $this->getBMLTOptions ( 1 );
                    $settings_id = $options['id'];
                    }
                
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
                            $uri = get_bloginfo('home').'/index.php?direct_simple='.htmlspecialchars ( $settings_id ).'&amp;search_parameters='.urlencode ( $line['parameters'] );
                            $display .= '<option value="'.$uri.'">'.__($line['prompt']).'</option>';
                            }
                        }
                    $display .= '<option disabled="disabled"></option>';
                    $display .= '<option disabled="disabled" value="">'.self::process_text ( self::$local_clear_search ).'</option>';
                    $display .= '</select></div></form>';
                    
                     if ( plugins_url() )
                        {
                        $img_url = plugins_url()."/bmlt-wordpress-satellite-plugin/images";
                        }
                    elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
                        {
                        $img_url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/images";
                        }
                    $img_url = htmlspecialchars ( $img_url );
                    
                    $display .= '<script type="text/javascript">';
                    $display .= "var c_g_BMLTPlugin_images = '$img_url';";
                    $display .= 'document.getElementById(\'interactive_form_div\').style.display=\'block\';';
                    $display .= 'document.getElementById(\'meeting_search_select\').selectedIndex=0;';
                    $display .= '</script>';
                    $display .= '<div id="simple_search_container"></div></div>';
                    }
                }
            
            // We only allow one instance per page.
            $count = 0;
            
            $in_content = preg_replace ( "/(<p[^>]*>)*?\<\!\-\-\s?SIMPLE_SEARCH_LIST\s?\-\-\>(<\/p[^>]*>)*?/", $display, $in_content, 1, $count );
            
            if ( !$count )
                {
                $in_content = preg_replace ( "/(<p[^>]*>)*?\[\[\s?SIMPLE_SEARCH_LIST\s?\]\](<\/p[^>]*>)*?/", $display, $in_content, 1 );
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
            if ( isset ( $_GET['BMLTPlugin_create_option'] ) )
                {
                $out_option_number = $this->make_new_options ( );
                if ( $out_option_number )
                    {
                    $new_options = $this->getBMLTOptions ( $out_option_number );
                    $def_options = $this->getBMLTOptions ( 1 );
                    
                    $new_options['root_server'] = $def_options['root_server'];
                    $new_options['map_center_latitude'] = $def_options['map_center_latitude'];
                    $new_options['map_center_longitude'] = $def_options['map_center_longitude'];
                    $new_options['map_zoom'] = $def_options['map_zoom'];
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
            elseif ( isset ( $_GET['BMLTPlugin_delete_option'] ) )
                {
                $option_index = intval ( $_GET['BMLTPlugin_delete_option'] );
        
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
                $html .= '<form id="BMLTPlugin_sheet_form" action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $_GET['page']).'" method="get" onsubmit="function(){return false}">';
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
                    $html .= '<form action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $_GET['page']).'" method="get" onsubmit="function(){return false}">';
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
                    $html .= '<form action ="'.htmlspecialchars ( $_SERVER['PHP_SELF'] ).'?page='.htmlspecialchars ( $_GET['page']).'" method="get">';
                    $html .= '<input type="hidden" name="page" value="'.htmlspecialchars ( $_GET['page'] ).'" />';
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
                        $html .= "var c_g_BMLTPlugin_success_time = ".intVal ( self::$local_options_success_time ).";";
                        $html .= "var c_g_BMLTPlugin_failure_time = ".intVal ( self::$local_options_failure_time ).";";
                        $html .= "var c_g_BMLTPlugin_unsaved_prompt = '".self::process_text ( self::$local_options_unsaved_message )."';";
                        $html .= "var c_g_BMLTPlugin_test_server_success = '".self::process_text ( self::$local_options_test_server_success )."';";
                        $html .= "var c_g_BMLTPlugin_test_server_failure = '".self::process_text ( self::$local_options_test_server_failure )."';";
                        $html .= "var c_g_BMLTPlugin_coords = new Array();";
                        $html .= "var g_BMLTPlugin_TimeToFade = ".intVal ( self::$local_options_success_time ).";";
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
                        $url = '';
                        if ( plugins_url() )
                            {
                            $url = plugins_url()."/bmlt-wordpress-satellite-plugin/google_map_images";
                            }
                        elseif ( !function_exists ( 'plugins_url' ) && defined ('WP_PLUGIN_URL' ) )
                            {
                            $url = WP_PLUGIN_URL."/bmlt-wordpress-satellite-plugin/google_map_images";
                            }
                        $url = htmlspecialchars ( $url );
                        $html .= "var c_g_BMLTPlugin_admin_google_map_images = '$url';";
                        $html .= 'BMLTPlugin_admin_load_map();';
                    $html .= '</script>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
        
        echo $html;
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