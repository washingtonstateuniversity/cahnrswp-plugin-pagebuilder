<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Tabs Shortcode
* @since 3.0.0
*/
class Tabs_Shortcode {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'display'    => '',
		'csshook'    => '',
		'textcolor'  => '',
		'min_height' => '',
		'tag'        => '',
		'anchor'     => '',
	);


	public function __construct() {

		$fields = array();

		for ( $i = 1; $i < 6; $i++ ) {

			$fields[ 'tab' . $i . '_title' ] = '';
			$fields[ 'tab' . $i . '_url' ] = '';
			$fields[ 'tab' . $i . '_posts' ] = '';
			$fields[ 'tab' . $i . '_bgcolor' ] = '';
			$fields[ 'tab' . $i . '_img_src' ] = '';
			$fields[ 'tab' . $i . '_img_id' ] = '';

		} // End for

		$this->default_settings = array_merge( $this->default_settings, $fields );

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register cpbtabs shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'cpbtabs', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'cpbtabs',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'Tabs', // Label of the item
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

		// Check default settings
		$atts = \shortcode_atts( $this->default_settings, $atts, 'cpbtabs' );

		$html = '<div class="cpb-item-tabs display-' . $atts['display'] . '">';

		$tabs = array();

		for ( $i = 1; $i < 6; $i++ ) {

			$prefix = 'tab' . $i;

			if ( ! empty( $atts[ $prefix . '_title' ] ) ) {

				$tab = array(
					'tag'     => ( ! empty( $atts['tag'] ) ) ? $atts['tag'] : 'h2',
					'title'   => $atts[ $prefix . '_title' ],
					'url'     => ( ! empty( $atts[ $prefix . '_url' ] ) ) ? $atts[ $prefix . '_url' ] : '',
					'posts'   => ( ! empty( $atts[ $prefix . '_posts' ] ) ) ? $atts[ $prefix . '_posts' ] : '',
					'bgcolor' => ( ! empty( $atts[ $prefix . '_bgcolor' ] ) ) ? $atts[ $prefix . '_bgcolor' ] : '',
					'bgimage' => ( ! empty( $atts[ $prefix . '_img_src' ] ) ) ? $atts[ $prefix . '_img_src' ] : '',
				);

				$tabs[ $prefix ] = $tab;

			} // End if
		} // End for

		$display = ( ! empty( $atts['display'] ) ) ? $atts['display'] : 'basic';

		switch ( $display ) {

			case 'columns':
				$html .= $this->get_display_columns( $tabs, $atts, $content );
				break;

			default:
				break;

		} // End switch

		return $html . '</div>';

	} // End get_rendered_shortcode


	protected function get_display_columns( $tabs, $settings, $content ) {

		\ob_start();

		include __DIR__ . '/column-tabs.php';

		$html = \ob_get_clean();

		return $html;

	} // End get_display_columns


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

		$posts = cpb_get_public_posts( array(), true, true );

		$html = '';

		$tags = $cpb_form->get_header_tags();

		unset( $tags['strong'] );

		$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tag' ), $settings['tag'], $tags, 'Tag Type' );

		for ( $i = 1; $i < 6; $i++ ) {

			$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'tab' . $i . '_title' ), $settings[ 'tab' . $i . '_title' ], 'Tab ' . $i . ' Title' );

			$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'tab' . $i . '_url' ), $settings[ 'tab' . $i . '_url' ], 'Tab ' . $i . ' Link' );

			$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tab' . $i . '_posts' ), $settings[ 'tab' . $i . '_posts' ], $posts, 'Tab ' . $i . ' Content' );

			$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tab' . $i . '_bgcolor' ), $settings[ 'tab' . $i . '_bgcolor' ], $cpb_form->get_wsu_colors(), 'Tab ' . $i . ' Background Color' );

			$html .= $cpb_form->insert_media( cpb_get_input_name( $id, true ), $settings, '', 'tab' . $i . '_' );

		} // End for

		$displays = array(
			'basic' => 'Basic',
			'columns' => 'Columns',
		);

		$adv = '';

		$adv .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'display' ), $settings['display'], $displays, 'Display' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'min_height' ), $settings['min_height'], 'Minimum Height' );

		$adv .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'textcolor' ), $settings['textcolor'], $cpb_form->get_wsu_colors(), 'Text Color' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'anchor' ), $settings['anchor'], 'Anchor Name' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		return array(
			'Basic' => $html,
			'Advanced' => $adv,
		);

	} // End get_shortcode_form

} // End Tabs

$cpb_shortcode_cpbtabs = new Tabs_Shortcode();
