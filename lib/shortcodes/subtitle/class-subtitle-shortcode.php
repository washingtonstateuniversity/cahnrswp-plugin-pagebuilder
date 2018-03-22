<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Subtitle Shortcode
* @since 3.0.0
*/
class Subtitle_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'tag'           => 'h2',
        'title'         => '',
        'textcolor'     => '',
        'csshook'       => '',
        'anchor'        => '',
        'link'          => '',
        'style'         => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register subtitle shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'subtitle', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode(
            'subtitle',
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Subtitle', // Label of the item
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'subtitle' );

		$classes_array = array( 'cpb-subtitle' );

		if ( ! empty( $atts['style'] ) ) {

			$classes_array[] = $atts['style'];

		} // End if

		if ( ! empty( $atts['csshook'] ) ) {

			$classes_array[] = $atts['csshook'];

		} // End if

		if ( ! empty( $atts['textcolor'] ) ) {

			$classes_array[] = $atts['textcolor'] . '-text';

        } // End if

        $classes = implode( ' ', $classes_array );

        $tag = $atts['tag'];

        $title = $atts['title'];

        $anchor = $atts['anchor'];

        $link = $atts['link'];

        \ob_start();

		include  __DIR__ . '/subtitle.php';

		$html .= \ob_get_clean();

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

        $styles = array(
			'' 									=> 'None',
			'underline-heading' 				=> 'Underlined Heading',
			'underline-heading small-heading' 	=> 'Underlined Heading (small font)',
		);

		$html = $cpb_form->text_field( cpb_get_input_name( $id, true, 'title' ), $settings['title'], 'Title' );

		$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tag' ), $settings['tag'], $cpb_form->get_header_tags(), 'Tag Type' );

		$adv = $cpb_form->select_field( cpb_get_input_name( $id, true, 'textcolor' ), $settings['textcolor'], $cpb_form->get_wsu_colors(), 'Text Color' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'link' ), $settings['link'], 'Link' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'anchor' ), $settings['anchor'], 'Anchor Name' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		$adv .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'style' ), $settings['style'], $styles, 'Style' );

        return array( 'Basic' => $html, 'Advanced' => $adv );

    } // End get_shortcode_form

} // End Subtitle

$cpb_shortcode_subtitle = new Subtitle_Shortcode();