<?php
/****************************************************************************************//**
* \file unit_test.php																		*
* \brief A unit test harness for the BMLTPlugin class.										*
* \version 1.0.0																			*
* \license Public Domain -No restrictions at all.											*
********************************************************************************************/

/********************************************************************************************
*									UNIT TESTING HARNESS									*
*																							*
* This code is used for testing the class by allowing a direct call of the file.			*
********************************************************************************************/
/// This is the URI to resolve a test root server (remote). Default is the public trunk test (not stable).
define ('U_TEST_REMOTE_URI', 'http://bmlt.magshare.net/trunk/main_server' );

/// If running on localhost, you can specify a local root URI. Comment this out to always use remote.
define ('U_TEST_LOCAL_URI', 'http://localhost/test/bmlt_trunk' );

/// This allows the JavaScript to be more easily debugged.
define ('_DEBUG_MODE__', 1 );

/// These hold the various callbacks.

global	$callbacks;

$callbacks = null;

/// These hold the options

global	$options;

$options = null;

require_once ( 'bmlt-wordpress-satellite-plugin.php' );

/****************************************************************************************//**
*	\brief Simulates the "plugins_url" from WordPress										*
********************************************************************************************/
function plugins_url ( )
{
	return "";
}

/****************************************************************************************//**
*	\brief Simulates the "__" from WordPress												*
********************************************************************************************/
function __ ($in_string		///< The string to process
			)
{
	return $in_string;
}

/****************************************************************************************//**
*	\brief Simulates the "add_action" from WordPress										*
********************************************************************************************/
function add_action (	$in_action_key,		///< The key for the action
						$in_action_function	///< The callback. If an array, it uses an object reference, with [0] being the object, and [1] being the function.
					)
{
	global	$callbacks;
	
	$callbacks[$in_action_key] = $in_action_function;
}

/****************************************************************************************//**
*	\brief Simulates the "add_filter" from WordPress										*
********************************************************************************************/
function add_filter (	$in_filter_key,		///< The key for the filter
						$in_filter_function	///< The callback. If an array, it uses an object reference, with [0] being the object, and [1] being the function.
					)
{
	global	$callbacks;
	
	$callbacks[$in_filter_key] = $in_filter_function;
}

/****************************************************************************************//**
*	\brief Simulates the "add_options_page" from WordPress									*
********************************************************************************************/
function add_options_page (	$in_title,
							$in_menu_string,
							$in_num,
							$in_file,
							$in_function
							)
{
	global	$callbacks;
	
	// We ignore everything except the callback.
	$callbacks['options_page'] = $in_function;
}

/****************************************************************************************//**
*	\brief Simulates the "get_option" from WordPress										*
*																							*
*	NOTE: This does not save the settings persistently! It is only here to simulate the		*
*	WordPress functionality.																*
*																							*
*	\returns An associative array of the saved option.										*
********************************************************************************************/
function get_option (	$in_option_name	///< The name of the option to get.
						)
{
	global	$options;
	$ret = null;
	
	if ( isset ( $options[$in_option_name] ) )
		{
		$ret = $options[$in_option_name];
		}
	
	return $ret;
}

/****************************************************************************************//**
*	\brief Simulates the "update_option" from WordPress										*
********************************************************************************************/
function update_option (	$in_option_name,	///< The name of the option to update.
							$in_option_value	///< What you want to set to the option.
						)
{
	global	$options;
	
	$options[$in_option_name] = $in_option_value;
}

/****************************************************************************************//**
*	\brief Simulates the "delete_option" from WordPress										*
********************************************************************************************/
function delete_option ( 	$in_option_name	///< The name of the option to delete.
						)
{
	global	$options;
	
	unset ( $options[$in_option_name] );
}

/****************************************************************************************//**
*	\brief Supplies "raw" content to be filtered.											*
*																							*
*	\returns A string. The content to be used												*
********************************************************************************************/
function get_seed_content (	$in_extra	///< You can add extra content to be appended to the end.
							)
{
	$the_content = '[[BMLT]]';
	
	return $the_content.$in_extra;
}

/****************************************************************************************//**
*	\brief Returns the URI for the test server. The default is the public trunk test server	*
*	and you can specify a local server if running on a localhost machine.					*
*																							*
*	NOTE: The caller can override the URI, by providing one in the 'test_uri' query param.	*
*	Example: unit_test.php?test_uri=http://bmlt.magshare.net/stable/main_server				*
*																							*
*	\returns A string. The URI to be presented to the class.								*
********************************************************************************************/
function u_test_get_test_server_uri ( )
{
	// First, establish our working URIs.
	$ret = U_TEST_REMOTE_URI;	// We return the remote by default.
	$local = ( defined ( 'U_TEST_LOCAL_URI' ) ? U_TEST_LOCAL_URI : null);

	// If we are running local, and have a local URI, we return that, instead.
	if ( $local && preg_match ( '|localhost|', $_SERVER['SERVER_NAME'] ) )
		{
		$ret = $local;
		}
	
	$override_uri = (isset ( $_GET['test_uri'] ) && $_GET['test_uri']) ? $_GET['test_uri'] : null;	// The caller can specify a URI that will override the presets.
	
	if ( $override_uri )
		{
		$ret = $override_uri;
		}
	
	return $ret;
}

/****************************************************************************************//**
*	\brief Abuses the options functionality, to make sure they work.						*
*																							*
*	\returns A string. The XHTML to be displayed.											*
********************************************************************************************/
function u_test_options()
{
	global	$options;
	$plugin_object =& BMLTPlugin::get_plugin_object();
	$ret = '';
	$ret .= '<h2>BEGIN OPTIONS TEST</h2>';
	$ret .= '<div class="test_container_div">';
	
	// First, we check for the first options (Should be none).
	$ret .= '<h3>BEGIN START FROM SCRATCH TEST</h3>';
	$ret .= '<div class="test_container_div">';
		$num = $plugin_object->get_num_options();
		
		$ret .= '<div class="test_container_div">';
			$ret .= "There are $num options stored.";
		$ret .= '</div>';
		
		$opt = $plugin_object->getBMLTOptions();
		$ret .= '<div class="test_container_div">';
			$ret .= 'Options returned: <pre>'.htmlspecialchars(print_r($opt,true)).'</pre>';
		$ret .= '</div>';
		$ret .= '<div class="test_container_div">';
			$ret .= 'Options actually stored: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
		$ret .= '</div>';
		$opt['root_server'] = 'THIS HAS BEEN CHANGED';
		if ( !$plugin_object->setBMLTOptions ($opt) )
			{
			$ret .= '<h3>ERROR! The options were not saved by ID!</h3>';
			}
		else
			{
			$ret .= '<div class="test_container_div">';
				$ret .= 'Options actually stored: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
			$ret .= '</div>';
			}
	$ret .= '</div>';
	$ret .= '<h3>END START FROM SCRATCH TEST</h3>';
	
	$ret .= '<h3>BEGIN START FROM OLDER SETTINGS TEST</h3>';
	$ret .= '<div class="test_container_div">';
	$options = array ( 'BMLTAdminOptions' => array (
		"root_server" => "http://localhost/test/bmlt_trunk/",
		"gmaps_api_key" => "ABQIAAAABCC8PsaKPPEsC3k649kYPRTayKsye0hTYG-iMuljzAHNM4JcxhSlV55ZKpjgC9b-QsLtlkYPMO6omg",
		"bmlt_fullscreen" => 0,
		"map_center_latitude" => "40.66188943992168",
		"map_center_longitude" => "-73.96991729736328",
		"map_zoom" => "12",
		"bmlt_language" => "en",
		"support_old_browsers" => 0,
		"bmlt_initial_view" => "map",
		"bmlt_new_search_url" => "",
		"bmlt_service_body_filters" => null,
		"push_down_more_details" => "1",
		"additional_css" => 'table#bmlt_container div#c_comdef_search_results_single_ajax_throbber_div{margin: 0;top: 50% !important;left: 50% !important;margin-left: -50px;margin-top: -50px;}'
		));
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$opt = $plugin_object->getBMLTOptions();
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options returned: <pre>'.htmlspecialchars(print_r($opt,true)).'</pre>';
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$ret .= '</div>';
	$ret .= '<h3>END START FROM OLDER SETTINGS TEST</h3>';
	
	$ret .= '<h3>BEGIN CREATE NEW SETTINGS TEST</h3>';
	$ret .= '<div class="test_container_div">';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$opt = $plugin_object->make_new_options();
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we made (index: '.$opt.'): <pre>'.htmlspecialchars(print_r($plugin_object->getBMLTOptions($opt),true)).'</pre>';
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$opt = $plugin_object->make_new_options();
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we made (index: '.$opt.'): <pre>'.htmlspecialchars(print_r($plugin_object->getBMLTOptions($opt),true)).'</pre>';
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$opt = $plugin_object->make_new_options();
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we made (index: '.$opt.'): <pre>'.htmlspecialchars(print_r($plugin_object->getBMLTOptions($opt),true)).'</pre>';
	$ret .= '</div>';
	$opt = $plugin_object->make_new_options();
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we made (index: '.$opt.'): <pre>'.htmlspecialchars(print_r($plugin_object->getBMLTOptions($opt),true)).'</pre>';
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$ret .= '</div>';
	$ret .= '<h3>END CREATE NEW SETTINGS TEST</h3>';
	
	$ret .= '<h3>BEGIN CHANGE INDEXED SETTINGS TEST</h3>';
	$ret .= '<div class="test_container_div">';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$opt = $plugin_object->getBMLTOptions(1);
	$opt['root_server'] = 'The ID of This Setting is '.$opt['id'];
	$plugin_object->setBMLTOptions($opt);
	$opt = $plugin_object->getBMLTOptions(2);
	$opt['root_server'] = 'The ID of This Setting is '.$opt['id'];
	$plugin_object->setBMLTOptions($opt);
	$opt = $plugin_object->getBMLTOptions(3);
	$opt['root_server'] = 'The ID of This Setting is '.$opt['id'];
	$plugin_object->setBMLTOptions($opt, 3);
	$opt = $plugin_object->getBMLTOptions(4);
	$opt['root_server'] = 'The ID of This Setting is '.$opt['id'];
	$plugin_object->setBMLTOptions($opt);
	$opt = $plugin_object->getBMLTOptions(5);
	$opt['root_server'] = 'The ID of This Setting is '.$opt['id'];
	$plugin_object->setBMLTOptions($opt,5);
	$ret .= '</div>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$ret .= '<h3>END CHANGE INDEXED SETTINGS TEST</h3>';
	
	$ret .= '<h3>BEGIN DELETE INDEXED SETTINGS TEST</h3>';
	$ret .= '<div class="test_container_div">';
	$ret .= '<h4>Starting Point</h4>';
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	
	$ret .= '<h4>Delete the first one</h4>';
	$plugin_object->delete_options(1);
	
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	
	$ret .= '<h4>Delete the third one</h4>';
	$plugin_object->delete_options(3);
	
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	
	$ret .= '<h4>Delete the last one</h4>';
	$plugin_object->delete_options(3);
	
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	
	$ret .= '<h4>Delete the first one</h4>';
	$plugin_object->delete_options(1);
	
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	
	$ret .= '<h4>This one should fail. We can\'t delete the last one.</h4>';
	// This should fail
	$plugin_object->delete_options(1);
	
	$ret .= '<div class="test_container_div">';
	$ret .= 'Options we have stored now: <pre>'.htmlspecialchars(print_r($options,true)).'</pre>';
	$ret .= '</div>';
	$ret .= '</div>';
	$ret .= '<h3>END DELETE INDEXED SETTINGS TEST</h3>';

	$ret .= '</div>';
	$ret .= '<h2>END OPTIONS TEST</h2>';
	
	return $ret;
}

/****************************************************************************************//**
*	\brief Runs the unit tests.																*
*																							*
*	\returns A string. The XHTML to be displayed.											*
********************************************************************************************/
function u_test()
{
	global	$callbacks;
	$ret = null;
	
	// This is part of the test. We call the plugin's init() function here.
	
	// First, we need to make sure the plugin object was created (Happens automatically when we include the file).
	if ( class_exists ( 'BMLTPlugin' ) && (BMLTPlugin::get_plugin_object() instanceof BMLTPlugin) )
		{
		// First thing we do, is call the init() handler.
		if ( $callbacks['init'][0] instanceof BMLTPlugin )
			{
			// The init function echoes, so we need to trap the output in an output buffer.
			ob_start();
			call_user_func ( $callbacks['init'] );
			$ret .= ob_get_contents();
			ob_end_clean();
			
			// We return a fully-qualified XHTML 1.0 Strict page.
			$ret .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>bmlt_satellite_controller Class Unit Test (###-SERVER-URI-###)</title>';
			$ret .= '<style type="text/css">';
			$ret .= '*{margin:0;padding:0}';
			$ret .= 'body{font-family:Courier;font-size:small}';
			$ret .= '.test_container_div{padding-left:20px}';
			$ret .= '</style>';
			
			if ( $callbacks['wp_head'][0] instanceof BMLTPlugin )
				{
				// The head function echoes, so we need to trap the output in an output buffer.
				ob_start();
				call_user_func ( $callbacks['wp_head'] );
				$ret .= ob_get_contents();
				ob_end_clean();
				
				$ret .= '</head><body>';
			
				// Start by getting the main URI
				$uri = u_test_get_test_server_uri();
				
				// The URI goes in the page title.
				$ret = str_replace ( '###-SERVER-URI-###', htmlspecialchars ( $uri ), $ret );
				
				//#######################
				//### 	START TEST		#
				//#######################
				
				$ret .= '<h1>BEGIN UNIT TEST</h1>';
				$ret .= '<div class="test_container_div">';
				
				$ret .= '<strong>USAGE:</strong> Override URI by "test_uri=<em>XXX</em>"<br />';
				$ret .= '<strong>USING:</strong> '.htmlspecialchars ( $uri );
			
				$ret .= '<h2>BEGIN FILTER TEST</h2>';
					
				if ( $callbacks['the_content'][0] instanceof BMLTPlugin )
					{
					$ret .= '<div class="test_container_div">';
					ob_start();
					$cont = call_user_func ( $callbacks['the_content'], get_seed_content ( ) );
					$ret .= ob_get_contents();
					$ret .= $cont;
					ob_end_clean();
					$ret .= '</div>';
					}
				else
					{
					$ret .= '<h3>ERROR! The the_content() function is not callable!</h3>';
					}
				
				$ret .= '<h2>END FILTER TEST</h2>';
			
				$ret .= '<h2>BEGIN ADMIN TEST</h2>';
					
				if ( $callbacks['admin_menu'][0] instanceof BMLTPlugin )
					{
					$ret .= u_test_options();
					global $options;

					// Make a bunch of options for the form to display.
					$plugin_object =& BMLTPlugin::get_plugin_object();
					$opt = $plugin_object->make_new_options();
					$opt = $plugin_object->make_new_options();
					$opt = $plugin_object->make_new_options();
					$opt = $plugin_object->make_new_options();
					$opt = $plugin_object->make_new_options();

					$ret .= '<div class="test_container_div">';
					ob_start();
					call_user_func ( $callbacks['admin_menu'] );
					$ret .= ob_get_contents();
					ob_end_clean();
					$ret .= '</div>';
					
					// If the above function was called correctly, the admin page should now be installed. We call that.
					if ( $callbacks['options_page'][0] instanceof BMLTPlugin )
						{
						$ret .= '<div class="test_container_div">';
						$ret .= '<h3>BEGIN ADMIN OPTIONS TEST</h3>';
						$ret .= '<div class="test_container_div">';
						ob_start();
						call_user_func ( $callbacks['options_page'] );
						$ret .= ob_get_contents();
						ob_end_clean();
						$ret .= '</div>';
						$ret .= '<h3>END ADMIN OPTIONS TEST</h3>';
						$ret .= '</div>';
						}
					else
						{
						$ret .= '<h3>ERROR! The options_page() function is not callable!</h3>';
						}
					}
				else
					{
					$ret .= '<h3>ERROR! The admin_menu() function is not callable!</h3>';
					}
				
				$ret .= '<h2>END ADMIN TEST</h2>';

				$ret .= '</div>';
				
				$ret .= '<h1>END UNIT TEST</h1>';
				
				//#######################
				//### 	END TEST		#
				//#######################
				}
			else
				{
				$ret .= '</head><body>';
				$ret .= '<h1>ERROR! The wp_head() function is not callable!</h1>';
				$ret .= '</body></html>';
				}
			}
		else
			{
			$ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
			$ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
			$ret .= '<head>';
			$ret .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
			$ret .= '<title>ERROR!</title>';
			$ret .= '</head><body>';
			$ret .= '<h1>ERROR! The init() function is not callable!</h1>';
			$ret .= '</body></html>';
			}
		
		$ret .= '</body></html>';	// Wrap up the page.
		}
	else
		{
		$ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		$ret .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
		$ret .= '<head>';
		$ret .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
		$ret .= '<title>ERROR!</title>';
		$ret .= '</head><body>';
		if ( !class_exists ( 'BMLTPlugin' ) )
			{
			$ret .= '<h1>ERROR! The class does not exist!</h1>';
			}
		elseif ( !(BMLTPlugin::get_plugin_object() instanceof BMLTPlugin) )
			{
			$ret .= '<h1>ERROR! The object is not the proper class!</h1>';
			}
		else
			{
			$ret .= '<h1>ERROR! The object was not instantiated!</h1>';
			}
		$ret .= '</body></html>';
		}
	
	return $ret;
}

/********************************************************************************************
*										UNIT TESTING MAIN									*
/*******************************************************************************************/

// This calls the unit test.
echo u_test();

?>