/****************************************************************************************//**
* \file javascript.js																		*
* \brief The javascript for the BMLTPlugin class.											*
* \version 1.0.0																			*
* \license Public Domain -No restrictions at all.											*
********************************************************************************************/
	
/****************************************************************************************//**
*										AJAX HANDLER										*
********************************************************************************************/

var	g_BMLTPlugin_AjaxRequest_object = null;

/****************************************************************************************//**
*	\brief A simple, generic AJAX request function.											*
*																							*
*	\returns a new XMLHTTPRequest object.													*
********************************************************************************************/
	
function BMLTPlugin_AjaxRequest (	url,		///< The URI to be called
									callback,	///< The success callback
									method		///< The method ('get' or 'post')
									)
{
	if ( g_BMLTPlugin_AjaxRequest_object )
		{
		g_BMLTPlugin_AjaxRequest_object.abort();
		g_BMLTPlugin_AjaxRequest_object = null;
		};
	
	/************************************************************************************//**
	*	\brief Create a generic XMLHTTPObject.												*
	*																						*
	*	This will account for the various flavors imposed by different browsers.			*
	*																						*
	*	\returns a new XMLHTTPRequest object.												*
	****************************************************************************************/
	
	function createXMLHTTPObject()
	{
		var XMLHttpArray = [
			function() {return new XMLHttpRequest()},
			function() {return new ActiveXObject("Msxml2.XMLHTTP")},
			function() {return new ActiveXObject("Msxml2.XMLHTTP")},
			function() {return new ActiveXObject("Microsoft.XMLHTTP")}
			];
			
		var xmlhttp = false;
		
		for ( var i=0; i < XMLHttpArray.length; i++ )
			{
			try
				{
				xmlhttp = XMLHttpArray[i]();
				}
			catch(e)
				{
				continue;
				};
			break;
			};
		
		return xmlhttp;
	};
	
	g_BMLTPlugin_AjaxRequest_object = createXMLHTTPObject();
	g_BMLTPlugin_AjaxRequest_object.finalCallback = callback;
	g_BMLTPlugin_AjaxRequest_object.onreadystatechange = function ( )
		{
		if ( g_BMLTPlugin_AjaxRequest_object.readyState != 4 ) return;
		if( g_BMLTPlugin_AjaxRequest_object.status != 200 ) return;
		callback ( g_BMLTPlugin_AjaxRequest_object );
		g_BMLTPlugin_AjaxRequest_object = null;
		};
	g_BMLTPlugin_AjaxRequest_object.open ( method,url, true );
	g_BMLTPlugin_AjaxRequest_object.send ( null );
	
	return g_BMLTPlugin_AjaxRequest_object;
};
