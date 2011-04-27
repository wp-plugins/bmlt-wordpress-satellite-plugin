/****************************************************************************************//**
* \file javascript.js                                                                       *
* \brief The javascript for the BMLTPlugin class.                                           *
* \version 1.0.0                                                                            *
* \license Public Domain -No restrictions at all.                                           *
********************************************************************************************/
    
/****************************************************************************************//**
*                                       AJAX HANDLER                                        *
********************************************************************************************/

/****************************************************************************************//**
*   \brief A simple, generic AJAX request function.                                         *
*                                                                                           *
*   \returns a new XMLHTTPRequest object.                                                   *
********************************************************************************************/
    
function BMLTPlugin_AjaxRequest (   url,        ///< The URI to be called
                                    callback,   ///< The success callback
                                    method,     ///< The method ('get' or 'post')
                                    extra_data  ///< If supplied, extra data to be delivered to the callback.
                                    )
{
    /************************************************************************************//**
    *   \brief Create a generic XMLHTTPObject.                                              *
    *                                                                                       *
    *   This will account for the various flavors imposed by different browsers.            *
    *                                                                                       *
    *   \returns a new XMLHTTPRequest object.                                               *
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
    
    var req = createXMLHTTPObject();
    req.finalCallback = callback;
    var sVars = null;
    method = method.toString().toUpperCase();
    
    // Split the URL up, if this is a POST.
    if ( method == "POST" )
        {
        var rmatch = /^([^\?]*)\?(.*)$/.exec ( url );
        url = rmatch[1];
        sVars = rmatch[2];
        };
    if ( extra_data )
        {
        req.extra_data = extra_data;
        };
    req.open ( method, url, true );
	if ( method == "POST" )
        {
        req.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        };
    req.onreadystatechange = function ( )
        {
        if ( req.readyState != 4 ) return;
        if( req.status != 200 ) return;
        callback ( req );
        req = null;
        };
    req.send ( sVars );
    
    return req;
};

/****************************************************************************************//**
*   \brief Call to initiate a simple popup search.                                          *
********************************************************************************************/
function BMLTPlugin_simple_div_filler ( in_uri,     ///< The URI to call.
                                        in_header   ///< The text for the header.
                                    )
{
    if ( !in_uri )
        {
        var option_list=document.getElementById ( 'meeting_search_select' ).options;
        document.getElementById ( 'simple_search_container' ).innerHTML='';
        document.getElementById ( 'meeting_search_select' ).selectedIndex=0;
        option_list[option_list.length-1].disabled=true;
        }
    else
        {
        document.getElementById('simple_search_container').innerHTML='<div class="BMLTPlugin_simple_throbber_container_div"><div class="BMLTPlugin_simple_throbber_div"><img class="bmlt_simple_throbber_img" alt="throbber" src="'+c_g_BMLTPlugin_images+'Throbber.gif" /></div></div>';
        BMLTPlugin_AjaxRequest ( in_uri, BMLTPlugin_simple_div_filler_callback, 'get', in_header );
        };
};

/****************************************************************************************//**
*   \brief AJAX callback for the simple popup search.                                       *
********************************************************************************************/
function BMLTPlugin_simple_div_filler_callback ( in_req ///< The HTTPRequest object for this call.
                                                )
{
    document.getElementById('simple_search_container').innerHTML='<h2 class="bmlt_simple_header">'+in_req.extra_data+'</h2>'+in_req.responseText;
};
