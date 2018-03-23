<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Textblock Shortcode
* @since 3.0.0
*/
class Textblock {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'is_callout'         => '',
		'bgcolor'           => '',
		'textcolor'         => '',
		'csshook'           => '',
		'list_style'        => '',
		'title'             => '',
	);


	public function __construct() {

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register textblock shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'textblock', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'textblock',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'sanitize_callback'     => array( $this, 'get_sanitize_shortcode_atts' ),
				'label'                 => 'Textblock', // Label of the item
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

		// Check default settings
		$settings = \shortcode_atts( $this->default_settings, $atts, 'textblock' );

		$content = do_shortcode( $this->get_more_content( $content, $settings ) );

		$content = apply_filters( 'cpb_the_content', $content );

		//TO DO: Need to work out applying the content filter here

		// Set textblock classes
		$classes = $this->get_textblock_classes( $settings );

		$prefix = $this->prefix;

		\ob_start();

		include __DIR__ . '/textblock.php';

		$html = \ob_get_clean();

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

		$style_array = array(
			''                      => 'Default',
			'list-style-arrows'     => 'Arrows',
			'list-style-drop-down'  => 'Accordion',
		);

		$html = $cpb_form->text_field( cpb_get_input_name( $id, true, 'title' ), $settings['title'], 'Title' );

		ob_start();

		wp_editor( $content, '_cpb_content_' . $id );

		$html .= ob_get_clean();

		$adv = $cpb_form->select_field( cpb_get_input_name( $id, true, 'textcolor' ), $settings['textcolor'], $cpb_form->get_wsu_colors(), 'Text Color' );

		$adv .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'bgcolor' ), $settings['bgcolor'], $cpb_form->get_wsu_colors(), 'Background Color' );

		$adv .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'list_style' ), $settings['list_style'], $style_array, 'List Style' );

		$adv .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'is_callout' ), 1, $settings['is_callout'], 'Is Callout' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		return array(
			'Basic' => $html,
			'Advanced' => $adv,
		);

	} // End get_shortcode_form


	/*
	* @desc Get stanitized output for $atts
	* @since 3.0.0
	*
	* @param array $atts Shortcode attributes
	* @param string $content Shortcode content
	*
	* @return array Sanitized shortcode $atts
	*/
	public function get_sanitize_shortcode_atts( $atts ) {

	} // End sanitize_shortcode


	/*
	* @desc Get shortcode for use in save
	* @since 3.0.0
	*
	* @param array $atts Shortcode attributes
	* @param string $content Shortcode content
	*
	* @return string Shortcode for saving in content
	*/
	public function get_to_shortcode( $atts, $content ) {

	} // End


	/*
	* @desc Get textblock classes
	* @since 3.0.0
	*
	* @param array $settings Textblock attributes
	*
	* @return string Textblock classes
	*/
	private function get_textblock_classes( $settings ) {

		$class = array();

		if ( ! empty( $settings['textcolor'] ) ) {

			$class[] = $settings['textcolor'] . '-text';

		} // End if

		if ( ! empty( $settings['is_callout'] ) ) {

			$class[] = 'is-callout';

		} // End if

		if ( ! empty( $settings['csshook'] ) ) {

			$class[] = $settings['csshook'];

		} // End if

		if ( ! empty( $settings['bgcolor'] ) ) {

			$class[] = $settings['bgcolor'] . '-back';

		} // End if

		if ( ! empty( $settings['list_style'] ) ) {

			$class[] = $settings['list_style'];

		} // End if

		return implode( ' ', $class );

	} // End get_item_class


	/*
	* @desc Split content by more span
	* @since 3.0.0
	*
	* @param array $settings Textblock attributes
	*
	* @return array Textblock css
	*/
	private function get_more_content( $content, $settings ) {

		if ( strpos( $content, '<span id="more-' ) !== false ) {

			$content_parts = preg_split( '/<span id="more-.*?"><\/span>/', $content );

			$link = '<div id="' . $this->get_id() . '" class="cpb-more-button"><a href="#"><span>Continue Reading</span></a></div>';

			$new_content = '<div class="cpb-more-content">';

			$new_content .= '<div class="cpb-more-content-intro">' . $content_parts[0] . '</div>';

			$new_content .= '<div class="cpb-more-content-continue">' . $content_parts[1] . '</div>';

			$new_content .= $link . '</div>';

			$content = $new_content;

		} // end if

		return $content;

	} // end get_more_content

} // End Textblock

$cpb_shortcode_textblock = new Textblock();
