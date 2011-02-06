<?php
/****************************************************************************************//**
* \file unit_test.php																		*
* \brief A unit test harness for the BMLTPlugin class.										*
* \version 1.0.0																			*
* \license Public Domain -No restrictions at all.											*
********************************************************************************************/
/****************************************************************************************//**
*	\file	bmlt-wordpress-satellite-plugin.php												*
*																							*
*	\brief	This is a WordPress plugin of a BMLT satellite client.							*
*	\version 2.0.0																			*
*																							*
*	Plugin Name: BMLT Satellite Server														*
*	Plugin URI: http://magshare.org/bmlt													*
*	Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.	*
*	Version: 2.0																			*
*	Install: Drop this directory into the "wp-content/plugins/" directory and activate it.	*
********************************************************************************************/

/****************************************************************************************//**
*	\class BMLTPlugin																		*
*																							*
*	\brief This is the class that implements and encapsulates the plugin functionality.		*
*	A single instance of this is created, and manages the plugin.							*
********************************************************************************************/

class BMLTPlugin
{
	// Include the satellite driver class.
	require_once ( 'bmlt_satellite_controller.class.php' );

	/************************************************************************************//**
	*							STATIC DATA MEMBERS (SINGLETON)								*
	****************************************************************************************/
	
	/// This is a SINGLETON pattern. There can only be one...
	static	$g_s_there_can_only_be_one = null;										///< This is a static variable that holds the single instance.
	
	/************************************************************************************//**
	*							STATIC DATA MEMBERS (DEFAULTS)								*
	*	In Version 2, these are all ignored:												*
	*		$default_gkey																	*
	*		$default_bmlt_fullscreen														*
	*		$default_support_old_browsers													*
	*		$default_initial_view															*
	*		$default_sb_array																*
	*		$default_push_down_more_details													*
	****************************************************************************************/
	
	// These are the old settings that we still care about.
	static	$adminOptionsName = "BMLTAdminOptions";									///< The name, in the database, for the options for this plugin.
	static	$default_rootserver = 'http://bmlt.magshare.net/stable/main_server';	///< This is the default root BMLT server URI.
	static	$default_map_center_latitude = 29.764377375163125;						///< This is the default basic search map center latitude
	static	$default_map_center_longitude = -95.4931640625;							///< This is the default basic search map center longitude
	static	$default_map_zoom = 9;													///< This is the default basic search map zoom level
	static	$default_language = 'en';												///< This is the default language for the server.
	static	$default_new_search = '';												///< If this is set to something, then a new search uses the exact URI.
	static	$default_additional_css = '';											///< The admin can add arbitrary CSS here (NOTE: Version 1 CSS will no longer apply to Version 2).
		
	/************************************************************************************//**
	*							STATIC DATA MEMBERS (LOCALIZABLE)							*
	****************************************************************************************/
	
	/// These are used internal to the class, but can be localized
	static	$local_options_title = 'Basic Meeting List Toolbox Options';			///< This is the title that is displayed over the options.
	static	$local_menu_string = 'BMLT Options';									///< The name of the menu item.
	
	/************************************************************************************//**
	*									DYNAMIC DATA MEMBERS								*
	****************************************************************************************/
	
	var	$my_driver = null;															///< This will contain an instance of the BMLT satellite driver class.
	
	/************************************************************************************//**
	*									FUNCTIONS/METHODS									*
	****************************************************************************************/
	
	/************************************************************************************//**
	*	\brief Get the instance																*
	*																						*
	*	\return An instance  of BMLTPlugin													*
	****************************************************************************************/
	static function get_plugin_object ()
		{
		return self::$g_s_there_can_only_be_one;
		}
	
	/************************************************************************************//**
	*	\brief Constructor. Enforces the SINGLETON, and sets up the callbacks.				*
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
					add_filter ( 'wp_head', array ( self::get_plugin_object(), 'head' ) );
					}
				else
					{
					echo "<!-- BMLTPlugin ERROR! No add_filter()! -->";
					}
				
				if ( function_exists ( 'add_action' ) )
					{
					add_action ( 'init', array ( self::get_plugin_object(), 'init' ) );
					add_action ( 'admin_menu', array ( self::get_plugin_object(), 'option_menu' ) );
					}
				else
					{
					echo "<!-- BMLTPlugin ERROR! No add_action()! -->";
					}
				}
			else
				{
				echo "<!-- BMLTPlugin ERROR! Can't Instantiate the Satellite Driver! Please reinstall the plugin! -->";
				}
			}
		else
			{
			echo "<!-- BMLTPlugin Warning: __construct() called multiple times! -->";
			}
		}
		
	/************************************************************************************//**
	*	\brief Accessor: This gets the driver object.										*
	*																						*
	*	\returns a reference to the bmlt_satellite_controller driver object					*
	****************************************************************************************/
	function &get_my_driver ()
		{
		return $this->my_driver;
		}
		
	/************************************************************************************//**
	*	\brief This gets the admin options from the database.								*
	*																						*
	*	\returns an associative array, with the option settings.							*
	****************************************************************************************/
	function getAdminOptions ( $in_option_number = null	///< It is possible to store multiple options. If there is a number here (>0), that will be used.
							)
		{
		$BMLTOptions = array (	'root_server' => $this->default_rootserver,
								'map_center_latitude' => $this->default_map_center_latitude,
								'map_center_longitude' => $this->default_map_center_longitude,
								'map_zoom' => $this->default_map_zoom,
								'bmlt_language' => $this->default_language,
								'bmlt_new_search_url' => $this->default_new_search,
								'additional_css' => $this->default_additional_css
								);

		if ( function_exists ( 'get_option' ) )
			{
			$option_number = null;
			if ( intval ( $in_option_number ) > 0 )
				{
				$option_number = '_'.intval( $in_option_number );
				}
			
			$old_BMLTOptions = get_option ( $this->adminOptionsName.$option_number );
			
			if ( is_array ( $old_BMLTOptions ) && count ( $old_BMLTOptions ) )
				{
				foreach ( $old_BMLTOptions as $key => $value )
					{
					if ( isset ( $BMLTOptions[$key] ) )	// We deliberately ignore old settings that no longer apply.
						{
						$BMLTOptions[$key] = $value;
						}
					}
				}
			
			// Strip off the trailing slash.
			$BMLTOptions['root_server'] = preg_replace ( "#\/+?$#", "", trim($BMLTOptions['root_server']), 1 );
			
			$this->setAdminOptions ( $in_option_number, $BMLTOptions );
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No get_option()! -->";
			}
		}
	
	/************************************************************************************//**
	*	\brief This updates the database with the given options.							*
	****************************************************************************************/
	function setAdminOptions (	$in_option_number = null,	///< It is possible to store multiple options. If there is a number here (>0), that will be used.
								$in_options					///< An array. The options to be stored.
							)
		{
		if ( function_exists ( 'update_option' ) )
			{
			$option_number = null;
			if ( intval ( $in_option_number ) > 0 )
				{
				$option_number = '_'.intval( $in_option_number );
				}
			update_option ( $this->adminOptionsName.$option_number, $BMLTOptions );
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No update_option()! -->";
			}
		}
	
	/************************************************************************************//**
	*								THE WORDPRESS CALLBACKS									*
	****************************************************************************************/
		
	/************************************************************************************//**
	*	\brief Called before anything else is run.											*
	*																						*
	*	This function will check for AJAX rerouters and for mobile rerouters. If it sees a	*
	*	need for one, it dies the script with the appropriate response. Otherwise, it just	*
	*	does nothing.																		*
	****************************************************************************************/
	function init ( )
		{
		}
		
	/************************************************************************************//**
	*	\brief Echoes any necessary head content.											*
	****************************************************************************************/
	function head ( )
		{
			$head_content = "";
			
			echo $head_content;
		}
		
	/************************************************************************************//**
	*	\brief Massages the page content.													*
	*																						*
	*	\returns a string, containing the "massaged" content.								*
	****************************************************************************************/
	function content_filter ( $in_the_content	///< The content in need of filtering.
							)
		{
		return $in_the_content;
		}
		
	/************************************************************************************//**
	*	\brief Presents the admin menu options.												*
	*																						*
	****************************************************************************************/
	function option_menu ( )
		{
		if ( function_exists ( 'add_options_page' ) && (self::get_plugin_object() instanceof BMLTPlugin) )
			{
			add_options_page ( self::$local_options_title, self::$local_menu_string, 9, basename ( __FILE__ ), array ( self::get_plugin_object(), 'admin_page' ) );
			}
		elseif ( !function_exists ( 'add_options_page' ) )
			{
			echo "<!-- BMLTPlugin ERROR! No add_options_page()! -->";
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No BMLTPlugin Object! -->";
			}
		}
		
	/************************************************************************************//**
	*	\brief Presents the admin page.														*
	*																						*
	****************************************************************************************/
	function admin_page ( )
		{
		}
};

/****************************************************************************************//**
*									MAIN CODE CONTEXT										*
********************************************************************************************/
global $BMLTPluginOp;

if ( !isset ( $BMLTPluginOp ) && class_exists ( "BMLTPlugin" ) )
	{
	$BMLTPluginOp = new BMLTPlugin();
	}
?>