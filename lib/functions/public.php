<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc get the plugin base path
* @since 3.0.0
* 
* @param string $path Optional appended path
* 
* @return string Full path
*/
function cpb_get_plugin_path( $path = '' ){

    $path = CWPPAGEBUILDERPATH . $path;

    return $path;

} // End cpb_get_plugin_path


/*
* @desc get the plugin base url
* @since 3.0.0
* 
* @param string $path Optional appended path
* 
* @return string Full URL
*/
function cpb_get_plugin_url( $path = '' ){

    $path = CWPPAGEBUILDERURL . $path;

    return $path;

} // End cpb_get_plugin_path