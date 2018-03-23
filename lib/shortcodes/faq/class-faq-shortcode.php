<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle FAQ Shortcode
* @since 3.0.0
*/
class FAQ_Shortcode {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'title'     => '',
		'tag'       => 'span',
		'textcolor' => '',
	);


	public function __construct() {

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register faq shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'faq', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'faq',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'FAQ', // Label of the item
				'render_callback'       => array( $this, 'get_rendered_shortcode' ), // Callback to render shortcode
				'default_atts'          => $this->default_settings,
				'in_column'             => true, // Allow in column
				'uses_wp_editor'        => true, // Uses WP Editor
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
		$atts = \shortcode_atts( $this->default_settings, $atts, 'faq' );

		$tag = $atts['tag'];

		$title = $atts['title'];

		$content = apply_filters( 'cpb_the_content', \do_shortcode( $content ) );

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/items/faq/faq.php' );

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

		$html = $cpb_form->text_field( cpb_get_input_name( $id, true, 'title' ), $settings['title'], 'Title' );

		$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tag' ), $settings['tag'], $cpb_form->get_header_tags( true ), 'Tag Type' );

		ob_start();

		wp_editor( $content, '_cpb_content_' . $id );

		$html .= ob_get_clean();

		$adv = $cpb_form->select_field(
			cpb_get_input_name( $id, true, 'textcolor' ),
			$settings['textcolor'],
			$cpb_form->get_wsu_colors(),
			'Text Color'
		);

		return array(
			'Basic' => $html,
			'Advanced' => $adv,
		);

	} // End get_shortcode_form

} // End _Shortcode

$cpb_shortcode_faq = new FAQ_Shortcode();
