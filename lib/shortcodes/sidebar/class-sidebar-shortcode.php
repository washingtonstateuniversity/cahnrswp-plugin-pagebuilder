<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Sidebar Shortcode
* @since 3.0.0 
*/
class Sidebar_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'sidebar_id' => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register sidebar shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'sidebar', array( $this, 'get_rendered_shortcode') );

        cpb_register_shortcode( 
            'sidebar', 
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Sidebar (Widgets)', // Label of the item
                'render_callback'       => array( $this, 'get_rendered_shortcode'), // Callback to render shortcode
                'default_atts'          => $this->default_settings,
                'in_column'             => true, // Allow in column
            ) 
        );

    } // End register_shortcode


    /*
    * @desc Render the shortcode
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode output
    */
    public function get_rendered_shortcode( $atts, $content ) {

        $html = '';

        // Check default settings 
        $atts = \shortcode_atts( $this->default_settings, $atts, 'sidebar' );

		if ( ! empty( $atts['sidebar_id'] ) ) {

			ob_start();

			dynamic_sidebar( $atts['sidebar_id'] );

			$sidebar = ob_get_clean();

			$html = do_shortcode( $sidebar );

		} // end if

		return $html;

    } // End get_rendered_shortcode


    /*
    * @desc Get HTML for shortcode form
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode form output
    */
    public function get_shortcode_form( $id, $settings, $content ) {

        $cpb_form = cpb_get_form_class();

        global $wp_registered_sidebars;

		$sidebars = array( 0 => 'None' );

		foreach( $wp_registered_sidebars as $sidebar ) {

			$sidebars[ $sidebar['id'] ] = $sidebar['name'];

		} // end foreach

		$form = $cpb_form->select_field( cpb_get_input_name( $id, true, 'sidebar_id'), $settings['sidebar_id'], $sidebars, 'Select Sidebar' );

		return $form;

    } // End get_shortcode_form

} // End Sidebar

$cpb_shortcode_sidebar = new Sidebar_Shortcode();