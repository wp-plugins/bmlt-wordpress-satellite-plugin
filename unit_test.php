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


require_once ( 'bmlt-wordpress-satellite-plugin.php' );

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
*	\brief Runs the unit tests.																*
*																							*
*	\returns A string. The XHTML to be displayed.											*
********************************************************************************************/
function u_test()
{
	// We return a fully-qualified XHTML 1.0 Strict page.
	$ret = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><title>bmlt_satellite_controller Class Unit Test (###-SERVER-URI-###)</title>';
	$ret .= '<style type="text/css">';
	$ret .= '*{margin:0;padding:0}';
	$ret .= 'body{font-family:Courier;font-size:small}';
	$ret .= '.test_container_div{padding-left:20px}';
	$ret .= '</style>';
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

	$ret .= '<div class="test_container_div">';
	$ret .= '</div>';
	$ret .= '</div>';
	
	$ret .= '<h1>END UNIT TEST</h1>';
	
	//#######################
	//### 	END TEST		#
	//#######################

	$ret .= '</body></html>';	// Wrap up the page.
	
	return $ret;
}

/********************************************************************************************
*										UNIT TESTING MAIN									*
/*******************************************************************************************/

// This calls the unit test.
echo u_test();

?>