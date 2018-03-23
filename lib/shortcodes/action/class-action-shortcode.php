<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Action Shortcode
* @since 3.0.0
*/
class Action {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'label'         => '',
		'link'          => '#',
		'textcolor'     => '',
		'csshook'       => '',
		'style'         => '',
		'caption'       => '',
	);


	public function __construct() {

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register action shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'action', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'action',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'Action Button', // Label of the item
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
		$atts = \shortcode_atts( $this->default_settings, $atts, 'action' );

		$class_array = array( 'cpb-action-button', 'cpb-action-button-item' );

		if ( ! empty( $atts['style'] ) ) {

			$class_array[] = $atts['style'];

		} // End if

		if ( ! empty( $atts['caption'] ) ) {

			$class_array[] = 'has-caption';

		} // End if

		if ( ! empty( $atts['csshook'] ) ) {

			$class_array[] = $atts['csshook'];

		} // End if

		$classes = implode( ' ', $class_array );

		$link = $atts['link'];

		$label = $atts['label'];

		$caption = $atts['caption'];

		if ( ! empty( $label ) ) {

			\ob_start();

			include __DIR__ . '/action.php';

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
	public function get_shortcode_form( $id, $settings, $content ) {

		$cpb_form = cpb_get_form_class();

		$styles = array(
			''                => 'None',
			'in-page-action'  => 'In Page Button',
		);

		$html = '';

		$adv = '';

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'label' ), $settings['label'], 'Label' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'link' ), $settings['link'], 'Link' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'style' ), $settings['style'], $styles, 'Style' );

		$adv .= $cpb_form->textarea_field( cpb_get_input_name( $id, true, 'caption' ), $settings['caption'], 'Link Description' );

		return array(
			'Basic'    => $html,
			'Advanced' => $adv,
		);

	} // End get_shortcode_form

} // End Action

$cpb_shortcode_action = new Action();
