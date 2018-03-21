<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Scripts
* @since 3.0.0 
*/
class Scripts {


    public function __construct() {

        \add_action( 'admin_enqueue_scripts', array( $this, 'add_edit_post_scripts'), 10, 1 );

        \add_action( 'wp_enqueue_scripts', array( $this, 'add_public_scripts'), 10, 1 );

    } // End 


    /*
    * @desc Handle edit post scripts
    * @since 3.0.0
    */
    public function add_edit_post_scripts( $hook ) {

        global $post;

        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

            \wp_enqueue_style(  'cpb-admin-style', cpb_get_plugin_url( 'lib/css/admin.css'), array(), '0.0.1' );

            \wp_enqueue_script(  'cpb-admin-script', cpb_get_plugin_url( 'lib/js/admin.js'), array(), '0.0.1', true );

        } // End if

    } // End add_edit_post_scripts


    /*
    * @desc Handle public scripts
    * @since 3.0.0
    */
    public function add_public_scripts() {

            \wp_enqueue_style(  'cpb-public-style', cpb_get_plugin_url( 'lib/css/public.css'), array(), '0.0.1' );

            \wp_enqueue_script(  'cpb-publicscript', cpb_get_plugin_url( 'lib/js/public.js'), array(), '0.0.1', true );

    } // End add_edit_post_scripts

} // End Scripts

$cpb_scripts = new Scripts();