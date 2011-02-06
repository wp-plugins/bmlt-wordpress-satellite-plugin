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
Plugin Name: BMLT WordPress Satellite
Plugin URI: http://magshare.org/bmlt
Description: This is a WordPress plugin satellite of the Basic Meeting List Toolbox.
Version: 2.0
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
********************************************************************************************/

// Include the satellite driver class.
require_once ( 'bmlt_satellite_controller.class.php' );

/****************************************************************************************//**
*	\class BMLTPlugin																		*
*																							*
*	\brief This is the class that implements and encapsulates the plugin functionality.		*
*	A single instance of this is created, and manages the plugin.							*
*																							*
*	This plugin registers errors by echoing HTML comments, so look at the source code of	*
*	the page if things aren't working right.												*
********************************************************************************************/

class BMLTPlugin
{
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
	static	$adminOptionsName = "BMLTAdminOptions";									///< The name, in the database, for the version 1 options for this plugin.
	static	$admin2OptionsName = "BMLT2AdminOptions";								///< These options are for version 2.
	
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
	*								OPTIONS MANAGEMENT										*
	*****************************************************************************************
	*	This takes some 'splainin'.															*
	*																						*
	*	The admin2 options track how many servers we're tracking, and allow the admin to	*
	*	increment by 1. The first options don't have a number. "Numbered" options begin at	*
	*	2. You are allowed to save new options at 1 past the current number of options. You	*
	*	delete options by decrementing the number in the admin2 options (the index). If you	*
	*	re-increment the options, you will see the old values. It is possible to reset to	*
	*	default, and you do that by specifying an option number less than 0 (-1).			*
	*																						*
	*	The reason for this funky, complex game, is so we can have multiple options, and we	*
	*	don't ignore old options from previous versions.									*
	*																						*
	*	I considered setting up an abstracted, object-based system for managing these, but	*
	*	it's complex enough without the added overhead, and, besides, that would give a lot	*
	*	more room for bugs. It's kinda hairy already, and the complexity is not great		*
	*	enough to justify designing a whole object subsystem for it.						*
	****************************************************************************************/
	
	/************************************************************************************//**
	*	\brief This gets the admin options from the database.								*
	*																						*
	*	\returns an associative array, with the option settings.							*
	****************************************************************************************/
	function getBMLTOptions ( $in_option_number = null	/**<	It is possible to store multiple options.
																If there is a number here (>1), that will be used.
																If <0, a new option will be returned (not saved).
														*/
							)
		{
		$BMLTOptions = null;
		
		if ( function_exists ( 'get_option' ) )
			{
			$BMLTOptions = array (	'root_server' => self::$default_rootserver,
									'map_center_latitude' => self::$default_map_center_latitude,
									'map_center_longitude' => self::$default_map_center_longitude,
									'map_zoom' => self::$default_map_zoom,
									'bmlt_language' => self::$default_language,
									'bmlt_new_search_url' => self::$default_new_search,
									'additional_css' => self::$default_additional_css,
									'id' => (function_exists ( 'microtime' ) ? microtime() : time())	// This gives the option a unique slug
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
						if ( isset ( $BMLTOptions[$key] ) )	// We deliberately ignore old settings that no longer apply.
							{
							$BMLTOptions[$key] = $value;
							}
						}
					}
				
				// If the option number is out of range (shouldn't be, but just to make sure), we set it to 1 if it is out of range.
				if ( (intval ( $BMLTOptions['option_index'] ) < 1) || (intval ( $BMLTOptions['option_index'] ) > $this->get_num_options ( )) )
					{
					echo "<!-- BMLTPlugin ERROR! Option index out of range! -->";
					$in_option_number = 1;
					$BMLTOptions['option_index'] = 1;
					}
			
				// Strip off the trailing slash.
				$BMLTOptions['root_server'] = preg_replace ( "#\/$#", "", trim($BMLTOptions['root_server']), 1 );
				}
			
			$this->setBMLTOptions ( $in_option_number, $BMLTOptions );
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No get_option()! -->";
			}
		
		return $BMLTOptions;
		}
	
	/************************************************************************************//**
	*	\brief This gets the admin options from the database, but by using the option id.	*
	*																						*
	*	\returns an associative array, with the option settings.							*
	****************************************************************************************/
	function getBMLTOptions_by_id ( $in_option_id,				///< The option ID. It cannot be optional.
									&$out_option_number = null	///< This can be optional. A reference to an integer that will be given the option number.
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
				
				if ( $i > 1 )	// We do this, for compatibility with older options.
					{
					$option_number = "_$i";
					}
				
				$temp_BMLTOptions = get_option ( self::$adminOptionsName.$option_number );
				
				if ( $temp_BMLTOptions['id'] == $in_option_id )
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
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No get_option()! -->";
			}
		
		return $BMLTOptions;
		}
	
	/************************************************************************************//**
	*	\brief This updates the database with the given options, using the ID in the 		*
	*	options array. The options must already be saved. If the id is not found, they		*
	*	will not be saved.																	*
	*																						*
	*	\returns a boolean. true if success.												*
	****************************************************************************************/
	function setBMLTOptions_by_id (	$in_options		///< An array. The options to be stored.
									)
		{
		$ret = false;
		$id = $in_options['id'];
		$index = 0;
		
		$this->getBMLTOptions_by_id ( $id, $index );
		
		if ( $index > 0 )
			{
			$ret = $this->setBMLTOptions ( $index, $in_options );
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! ID not found! -->";
			}
			
		return $ret;
		}
	
	/************************************************************************************//**
	*	\brief This updates the database with the given options.							*
	*																						*
	*	\returns a boolean. true if success.												*
	****************************************************************************************/
	function setBMLTOptions (	$in_option_number = null,	///< It is possible to store multiple options. If there is a number here (>1), that will be used.
								$in_options					///< An array. The options to be stored.
							)
		{
		$ret = false;
		
		if ( function_exists ( 'update_option' ) )
			{
			$option_number = null;
			// If they want a certain option number, then it needs to be greater than 1, and within the number we have assigned (We can also increase by 1).
			if ( (intval ( $in_option_number ) > 1) && (intval ( $in_option_number ) <= ($this->get_num_options ( ) + 1)) )
				{
				$option_number = '_'.intval( $in_option_number );
				}
			$in_option_number = (intval ( $in_option_number ) > 1) ? intval ( $in_option_number ) : 1;
			
			update_option ( $this->adminOptionsName.$option_number, $in_options );
			
			// If this is a new option, then we also update the admin 2 options, incrementing the number of servers.
			if ( intval ( $in_option_number ) == ($this->get_num_options ( ) + 1) )
				{
				$admin2Options = array ('num_servers' => intval( $in_option_number ));

				$this->setAdmin2Options ( $admin2Options );
				}
			
			$ret = true;
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! No update_option()! -->";
			}
			
		return $ret;
		}
		
	/************************************************************************************//**
	*	\brief This gets the admin 2 options from the database.								*
	*																						*
	*	\returns an associative array, with the option settings.							*
	****************************************************************************************/
	function getAdmin2Options ( )
		{
		$bmlt2_BMLTOptions = null;
		
		if ( function_exists ( 'get_option' ) )
			{
			$bmlt2_BMLTOptions = array ('num_servers' => 1	///< This is how many servers we start with (1)
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
			echo "<!-- BMLTPlugin ERROR! No get_option()! -->";
			}
			
		return $bmlt2_BMLTOptions;
		}
	
	/************************************************************************************//**
	*	\brief This updates the database with the given options (Admin2 options).			*
	*																						*
	*	\returns a boolean. true if success.												*
	****************************************************************************************/
	function setAdmin2Options (	$in_options	///< An array. The options to be stored.
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
			echo "<!-- BMLTPlugin ERROR! No update_option()! -->";
			}
		
		return $ret;
		}
	
	/************************************************************************************//**
	*	\brief Gets the number of active options.											*
	*																						*
	*	\returns an integer. The number of options.											*
	****************************************************************************************/
	function get_num_options ( )
		{
		$ret = 1;
		$opts = $this->getAdmin2Options();
		if ( isset ( $opts['num_servers'] ) )
			{
			$ret = intval ( $opts['num_servers'] );
			}
		else	// If the options weren't already set, we create them now.
			{
			$opts = array ( 'num_servers' => 1 );
			$this->setAdmin2Options ( $opts );
			}
		return $ret;
		}
	
	/************************************************************************************//**
	*	\brief Makes a new set of options, set as default.									*
	*																						*
	*	\returns An integer. The index of the options (It will always be the number of		*
	*	initial options, plus 1. Null if failed.											*
	****************************************************************************************/
	function make_new_options ( )
		{
		$opt = $this->getBMLTOptions ( -1 );
		$ret = null;
		
		// If we successfully get the options, we save them, in order to 
		if ( is_array ( $opt ) && count ( $opt ) )
			{
			$ret = $this->get_num_options ( ) + 1;
			$this-.setBMLTOptions ( $ret, $opt );
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! Failed to create new options! -->";
			}
		
		return $ret;
		}
	
	/************************************************************************************//**
	*	\brief Deletes the options by ID.													*
	*																						*
	*	\returns a boolean. true if success.												*
	****************************************************************************************/
	function delete_options_by_id ( $in_option_id	///< The ID of the option to delete.
									)
		{
		$ret = false;
		
		$option_num = 0;
		$this->getBMLTOptions_by_id ( $in_option_id, $option_num );	// We just want the option number.
		
		if ( $option_num > 0 )	// If it's 1, we'll let the next function register the error.
			{
			$ret = $this->delete_options ( $option_num );
			}
		
		return $ret;
		}
	
	/************************************************************************************//**
	*	\brief Deletes the indexed options.													*
	*																						*
	*	This is a bit of a delicate operation, because we need to re-index all of the other	*
	*	options, beyond the one being deleted.												*
	*																						*
	*	You cannot delete the first options (1).											*
	*																						*
	*	\returns a boolean. true if success.												*
	****************************************************************************************/
	function delete_options ( $in_option_number /**<	The number of the option to delete.
														It can be 2 -> the number of available options.
														For safety's sake, this cannot be optional.
														We cannot delete the first (primary) option.
												*/
							)
		{
		$first_num = intval ( $in_option_number );
		$ret = false;
		
		if ( function_exists ( 'delete_option' ) )
			{
			$last_num = $this->get_num_options ( );
			
			if ( ($first_num > 1) && ($first_num <= $last_num ) )
				{
				/*
					OK. At this point, we know which option we'll be deleting. The way we "delete"
					the option is to cascade all the ones after it down, and then we delete the last one.
					If this is the last one, then there's no need for a cascade, and we simply delete it.
				*/
				
				
				for ( $i = $first_num; $i < $last_num; $i++ )
					{
					$opt = $this->getBMLTOptions ( $i + 1 );
					$this->setBMLTOptions ( $i, $opt );
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
				echo "<!-- BMLTPlugin ERROR! Option request number out of range! It must be between 2 and $last_num -->";
				}
			}
		else
			{
			echo "<!-- BMLTPlugin ERROR! no delete_option()! -->";
			}
		
		return $ret;
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
			$head_content = "<!-- Added by the BMLTPlugin -->";
			
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