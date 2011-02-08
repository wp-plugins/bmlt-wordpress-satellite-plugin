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
                                    method      ///< The method ('get' or 'post')
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
    req.onreadystatechange = function ( )
        {
        if ( req.readyState != 4 ) return;
        if( req.status != 200 ) return;
        callback ( req );
        req = null;
        };
    req.open ( method,url, true );
    req.send ( null );
    
    return req;
};
