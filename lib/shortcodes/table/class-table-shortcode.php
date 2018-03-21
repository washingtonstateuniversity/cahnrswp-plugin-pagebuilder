<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Table Shortcode
* @since 3.0.0 
*/
class Table_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'img_src'   => '',
        'img_id'    => '',
        'caption'   => '',
        'alt'       => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register table shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'cpbtable', array( $this, 'get_rendered_shortcode') );

        cpb_register_shortcode( 
            'cpbtable', 
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Table', // Label of the item
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'cpbtable' );

        $img_src = $atts['img_src'];

        $caption = $atts['caption'];

        $alt = $atts['alt'];

        if ( ! empty( $img_src ) ) {

            ob_start();

            include  __DIR__ . '/table.php';

            $html .= ob_get_clean();

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
    public function get_shortcode_form( $id, $settings, $content ) {

        $cpb_form = cpb_get_form_class();

        $form = $cpb_form->insert_media( cpb_get_input_name( $id, true ), $settings );

		$form .= '<hr/>';

		$form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'caption'), $settings['caption'], 'Caption' );

		return $form; 

    } // End get_shortcode_form

} // End Table

$cpb_shortcode_table = new Table_Shortcode();