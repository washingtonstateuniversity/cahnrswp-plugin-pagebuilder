<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Image Shortcode
* @since 3.0.0 
*/
class Image_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'img_src'   => '',
        'img_id'    => '',
        'url'       => '',
        'alt'       => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register image shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'image', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode( 
            'image', 
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Image', // Label of the item
                'render_callback'       => array( $this, 'get_rendered_shortcode' ), // Callback to render shortcode
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'image' );

        if ( ! empty( $atts['img_id'] ) ) {

            $image_array = cpb_get_image_properties_array( $atts['img_id'] );

            if ( ! empty( $atts['alt'] ) ) {

                $image_array['alt'] = $atts['alt'];

            } // End if

            $url = $atts['url'];

            $img_src = $atts['img_src'];

            $alt = $image_array['alt'];

            ob_start();

			include  __DIR__ . '/image.php';

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

		$form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'alt' ), $settings['alt'], 'Image Alt Text' );

		$form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'url' ), $settings['url'], 'Link Image To:' );

        return $form;

    } // End get_shortcode_form

} // End Image

$cpb_shortcode_image = new Image_Shortcode();