<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Figure Shortcode
* @since 3.0.0 
*/
class Figure_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'img_src'         => '',
        'img_id'          => '',
        'caption'         => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register figure shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'figure', array( $this, 'get_rendered_shortcode') );

        cpb_register_shortcode( 
            'figure', 
            $args = array(
                'label'                 => 'Figure/Caption', // Label of the item
                'render_callback'       => array( $this, 'get_rendered_shortcode'), // Callback to render shortcode
                'form_callback'         => array( $this, 'get_shortcode_form' ),
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

        $img_src = $atts['img_src'];

        $caption = $atts['caption'];

        if ( ! empty( $img_src ) ) {

            \ob_start();

            include cpb_get_plugin_path('/lib/displays/items/figure/figure.php');

            $html .= \ob_get_clean();

        } // End if

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
    public function get_shortcode_form( $id, $atts, $content ) {

        $cpb_form = cpb_get_form_class();

        $form = $cpb_form->insert_media( cpb_get_input_name( $id, true ), $atts );

		$form .= '<hr/>';

        $form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'caption'), $atts['caption'], 'Caption' );

        return array( 'Basic' => $form );

    } // End get_shortcode_form


} // End Figure

$cpb_shortcode_figure = new Figure_Shortcode();