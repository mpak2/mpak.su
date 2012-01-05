<?php
/**
 * Damn Small Rich Text Editor v0.2.4 for jQuery
 * by Roi Avidan <roi@avidansoft.com>
 * Demo: http://www.avidansoft.com/dsrte/
 * Released under the GPL License
 *
 * Includes a minified version of AjaxFileUpload plugin for jQuery, taken from: http://www.phpletter.com/DOWNLOAD/
 *
 * This file handles the Server Side file upload logic.
 *
 */

/**
 * This function is used to translate strings, so your Editor may appear multi-lingual.
 * Currently, that logic is not implemented - I'm leaving it to you.
 * And yes... the name's inspirted by Drupal ;)
 *
 * @param $str
 *   String to translate.
 * @return
 *   Translated string.
 */
function t( $str )
{
    return $str;
}

/**
 * Helper function to convert arrays into JavaScript structures (JSON).
 * Uses PHP's function if available.
 *
 * @param $arr
 *   Array to convert to JSON.
 * @return
 *   JSON string representation for given array.
 */
function to_json( $arr )
{
    if ( function_exists( 'json_encode' ) )
        return json_encode( $arr );

    $str = array();
    foreach ( $arr as $key => $val )
        $str[] = is_bool( $val ) ? "\"$key\":".($val ? "true" : "false") : "\"$key\":\"$val\"";

    return "{".implode( ",", $str )."}";
}

// where you want to save your uploaded images
$uploadPath = 'uploadedimages';

// get the one and only file element from the FILES array
$file = current( $_FILES );

// verify upload was successful...
if ( $file['error'] === 0 )
{
    // and move the file to it's destination
    if ( !@move_uploaded_file( $file['tmp_name'], "$uploadPath/$file[name]" ) )
    {
        $error = t( "Can't move uploaded file into the $uploadPath directory" );
    }
}
else
{
    switch ( $file['error'] )
    {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $error = t( 'File is too big.' );
            break;

        case UPLOAD_ERR_PARTIAL:
            $error = t( 'File was only partially uploaded.' );
            break;

        case UPLOAD_ERR_NO_FILE:
            $error = t( 'No file was uploaded.' );
            break;

        case UPLOAD_ERR_NO_TMP_DIR:
            $error = t( 'Missing a temporary upload folder.' );
            break;

        case UPLOAD_ERR_CANT_WRITE:
            $error = t( 'Failed to write file to disk.' );
            break;

        case UPLOAD_ERR_EXTENSION:
            $error = t( 'File upload stopped by extension.' );
            break;
    }
}
// print results for AJAX handler
echo to_json(
	array(
		'error' => $error,
		'path' => $uploadPath,
		'file' => $file['name'],
		'tmpfile' => $file['tmp_name'],
		'size' => $file['size']
	)
);
?>
