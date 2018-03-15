<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle init of the plugin
* @sicne 3.0.0 
*/
class CAHNRSWP_Pagebuilder {


    public function __construct(){

        $this->set_constants();

        $this->init_plugin();

    } // End construct


    /*
    * @desc Set constants use in plugin funcitons. These should never be used direclty
    * @since 3.0.0
    */
    protected function set_constants(){

        // Set plugin path constant
        \define( 'CWPPAGEBUILDERPATH', dirname( dirname( __DIR__ ) ) );

        // Set plugin url cinstant
        \define( 'CWPPAGEBUILDERURL', \plugin_dir_url( __FILE__ ) );

    } // End set_constants


    /* 
    * @desc Start plugin script and load admin classes
    * @since 3.0.0
    */
    protected function init_plugin(){

        // Include global functions
        include CWPPAGEBUILDERPATH . '/lib/functions/public.php';

        // Check if is WP admin
        if ( \is_admin() ){

            // Add Pagebuilder Editor
            //include cpb_get_plugin_path('includes/class-editor.php');

        } // End if

        // Add Pagebuilder shortcodes
        include cpb_get_plugin_path('/lib/includes/class-shortcodes.php');

        // Add CSS & JS
        //include cpb_get_plugin_path('includes/class-scripts.php');

        // Add Customizer Script
        //include cpb_get_plugin_path('includes/class-customizer.php');

    } // End init_plugin


} // End CAHNRSWP_Pagebuilder

$cahnrswp_pagebuilder = new CAHNRSWP_Pagebuilder();
