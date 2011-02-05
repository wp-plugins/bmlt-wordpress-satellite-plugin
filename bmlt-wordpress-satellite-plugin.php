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
*	\brief	This is a simple WordPress plugin of a BMLT satellite client.					*
*	\version 2.0.0																			*
*																							*
*	Plugin Name: BMLT Satellite Server														*
*	Plugin URI: http://magshare.org/bmlt													*
*	Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.	*
*	Version: 2.0																			*
*	Install: Drop this directory into the "wp-content/plugins/" directory and activate it.	*
********************************************************************************************/

// Include the satellite driver class.
require_once ( 'bmlt_satellite_controller.class.php' );

/****************************************************************************************//**
*	\class BMLTPlugin																		*
*																							*
*	\brief This is the class that implements and encapsulates the plugin functionality.		*
*	A single instance of this is created, and manages the plugin.							*
********************************************************************************************/

class BMLTPlugin
{
	/************************************************************************************//**
	*							STATIC DATA MEMBERS (SINGLETON)								*
	****************************************************************************************/
	
	/// This is a SINGLETON pattern. There can only be one...
	static	$g_s_there_can_only_be_one = null;									///< This is a static variable that holds the single instance.
	
	/************************************************************************************//**
	*							STATIC DATA MEMBERS (DEFAULTS)								*
	****************************************************************************************/
	
	// These are the old settings that we still care about.
	static	$adminOptionsName = "BMLTAdminOptions";								///< The name, in the database, for the options for this plugin.
	static	$default_rootserver = 'http://bmlt.magshare.net/stable/main_server';	///< This is the default root BMLT server URI.
	static	$default_map_center_latitude = 29.764377375163125;					///< This is the default basic search map center latitude
	static	$default_map_center_longitude = -95.4931640625;						///< This is the default basic search map center longitude
	static	$default_map_zoom = 9;												///< This is the default basic search map zoom level
	static	$default_language = 'en';											///< This is the default language for the server.
	static	$default_new_search = '';											///< If this is set to something, then a new search uses the exact URI.
	static	$default_additional_css = '';										///< The admin can add arbitrary CSS here (NOTE: Version 1 CSS will no longer apply to Version 2).
	
	// In Version 2, these are all ignored.
	// 	var $default_gkey = null;
	// 	var $default_bmlt_fullscreen = null;
	// 	var $default_support_old_browsers = null;
	// 	var $default_initial_view = null;
	// 	var	$default_sb_array = null;
	// 	var $default_push_down_more_details = null;
	
	/************************************************************************************//**
	*							STATIC DATA MEMBERS (LOCALIZABLE)							*
	****************************************************************************************/
	
	/// These are used internal to the class, but can be localized
	static	$local_options_title = 'Basic Meeting List Toolbox Options';		///< This is the title that is displayed over the options.
	static	$local_menu_string = 'BMLT Options';								///< The name of the menu item.
	
	/************************************************************************************//**
	*									FUNCTIONS/METHODS									*
	****************************************************************************************/
	
	/************************************************************************************//**
	*	\brief Constructor. Enforces the SINGLETON, and sets up the callbacks.				*
	****************************************************************************************/
	function __construct ()
		{
		if ( !isset ( self::$g_s_there_can_only_be_one ) || (self::$g_s_there_can_only_be_one === null) )
			{
			self::$g_s_there_can_only_be_one = $this;
			
			if ( function_exists ( 'add_filter' ) )
				{
				add_filter ( 'the_content', array ( self::$g_s_there_can_only_be_one, 'content_filter')  );
				add_filter ( 'wp_head', array ( self::$g_s_there_can_only_be_one, 'head' ) );
				}
			
			if ( function_exists ( 'add_action' ) )
				{
				add_action ( 'init', array ( self::$g_s_there_can_only_be_one, 'init' ) );
				add_action ( 'admin_menu', array ( self::$g_s_there_can_only_be_one, 'option_menu' ) );
				}
			
			if ( function_exists ( 'add_options_page' ) && isset ( self::$g_s_there_can_only_be_one ) )
				{
				add_options_page ( self::$local_options_title, self::$local_menu_string, 9, basename ( __FILE__ ), array ( self::$g_s_there_can_only_be_one, 'printAdminPage' ) );
				}
			}
		}
		
	/************************************************************************************//**
	*	\brief This gets the admin options from the database.								*
	*																						*
	*	\returns an associative array, with the option settings.							*
	****************************************************************************************/
	function getAdminOptions ( )
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
			$old_BMLTOptions = get_option ( $this->adminOptionsName );
			
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
	
			if ( function_exists ( 'get_option' ) )
				{
				update_option ( $this->adminOptionsName, $BMLTOptions );
				}
			}

		return $BMLTOptions;
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