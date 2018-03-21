<?php
/*
Plugin Name: CAHNRS Pagebuilder 3.0
Version: 3.0.0
Description: Builds customizable page layouts
Author: washingtonstateuniversity, cahnrscommunications, Danial Bleile
Author URI: http://cahnrs.wsu.edu/communications
Plugin URI: https://github.com/washingtonstateuniversity/cahnrswp-plugin-pagebuilder
Text Domain: cahnrswp-pagebuilder
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// This plugin uses namespaces and requires PHP 5.3 or greater.
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	add_action( 'admin_notices', create_function( '', // @codingStandardsIgnoreLine
	"echo '<div class=\"error\"><p>" . __( 'WSUWP Plugin Skeleton requires PHP 5.3 to function properly. Please upgrade PHP or deactivate the plugin.', 'cahnrswp-pagebuilder' ) . "</p></div>';" ) );
	return;
} else {

	// Class to handle init of plugin
	include_once __DIR__ . '/lib/includes/class-cahnrswp-pagebuilder.php';

} // End if
