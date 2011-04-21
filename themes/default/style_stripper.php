<?php
/***********************************************************************/
/**     \file   style_stripper.php

    \brief  This file reads in a CSS file, and optimizes it by stripping
    out comments and whitespace. It will also try to GZ compress the output
    using the standard OB functions. It can make a HUGE difference in size.
    
    The way it works is that you call it from the <link/> element (don't
    specify a "type" attribute), and give it a GET parameter of filename,
    which will equal the file path to the CSS file.
    
    For security purposes, the file must always be a ".css" file, and you can't
    go out of the directory in which this file is located.
*/
    $pathname = $_GET['filename'];
    if ( !preg_match ( "|/|", $pathname ) )
        {
        if ( preg_match ( "|.*?\.css$|", $pathname ) )
            {
            $pathname = dirname ( __FILE__ )."/$pathname";
            $opt = file_get_contents ( $pathname );
            $opt = preg_replace( "|\/\*.*?\*\/|s", "", $opt );
            $opt = preg_replace( "|\s+|s", " ", $opt );
            
            header ( "Content-type: text/css" );
            
            $handler = null;
            
            if ( zlib_get_coding_type() === false )
                {
                $handler = 'ob_gzhandler';
                }
            
            ob_start($handler);
            echo $opt;
            ob_end_flush();
            }
        else
            {
            echo "FILE MUST BE A .CSS FILE!";
            }
        }
    else
        {
        echo "YOU CANNOT LEAVE THE DIRECTORY!";
        }
?>