<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Shortcodes
* @since 3.0.0 
*/
class Shortcodes {


	public function __construct(){

		$this->add_shortcodes();

		\add_action( 'init', array( $this, 'register_shortcodes'), 99 );

	} // End __construct


	/*
	* @desc Register shortcodes use in cpb
	* @since 3.0.0
	*/
	public function register_shortcodes(){

		// Set shortcodes as global scope
		global $pagebuilder_shortcodes;

		// Shortcodes are added via cpb_shortcode filter
		$pagebuilder_shortcodes = \apply_filters('cpb_shortcodes', array() );

	} // End add_shortcodes

	/*
	* @desc Add built in Shortcodes
	* @since 3.0.0
	*/
	protected function add_shortcodes(){

		// Add row Shortcode
		include_once cpb_get_plugin_path( '/lib/shortcodes/row/class-row-shortcode.php' );

		// Add column Shortcode
		include_once cpb_get_plugin_path( '/lib/shortcodes/column/class-column-shortcode.php' );

		// Add textblock Shortcode
		include_once cpb_get_plugin_path( '/lib/shortcodes/textblock/class-textblock-shortcode.php' );

	} // End add_shortcodes


} // End Editor

$cpb_shortcodes = new Shortcodes();