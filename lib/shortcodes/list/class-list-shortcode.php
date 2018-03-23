<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle List Shortcode
* @since 3.0.0
*/
class List_Shortcode {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'source_type'       => '',
		'columns'           => '4',
		'unset_excerpt'     => '0',
		'unset_title'       => '0',
		'unset_img'         => '0',
		'unset_link'        => '0',
		'excerpt_length'    => 'medium',
	);


	public function __construct() {

		$local_query_defaults = cpb_get_local_query_defaults();

		$remote_query_defaults = cpb_get_remote_query_defaults();

		$select_query_defautls = cpb_get_select_query_defaults();

		$this->default_settings = array_merge(
			$this->default_settings,
			$local_query_defaults,
			$remote_query_defaults,
			$select_query_defautls
		);

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register list shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'list', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'list',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'Post/Page List', // Label of the item
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
		$atts = \shortcode_atts( $this->default_settings, $atts, 'list' );

		$post_items = array();

		if ( ! empty( $atts['source_type'] ) ) {

			$query = cpb_get_query_class();

			switch ( $atts['source_type'] ) {

				case 'feed':
					$post_items = $query->get_local_items( $atts, '' );
					break;
				case 'remote_feed':
					$post_items = $query->get_remote_items_feed( $atts, '' );
					break;
				default:
					$post_items = array();
					break;

			} // end switch

			if ( ! empty( $post_items ) ) {

				$html .= '<ul class="cpb-list cpb-item">';

				foreach ( $post_items as $index => $post_item ) {

					$link = ( ! empty( $post_item['link'] ) ) ? $post_item['link'] : '';

					$title = ( ! empty( $post_item['title'] ) ) ? $post_item['title'] : '';

					$excerpt = ( ! empty( $post_item['excerpt'] ) ) ? $post_item['excerpt'] : '';

					\ob_start();

					include __DIR__ . '/list.php';

					$html .= \ob_get_clean();

				} // end foreach

				$html .= '</ul>';

			} // End if
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

		$select_form = array(
			'name'    => cpb_get_input_name( $id, true, 'source_type' ),
			'value'   => 'select',
			'selected' => $settings['source_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			'form'    => $cpb_form->get_form_select_post( cpb_get_input_name( $id, true ), $settings ),
		);

		$feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'source_type' ),
			'value'   => 'feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $cpb_form->get_form_local_query( cpb_get_input_name( $id, true ), $settings ),
		);

		$remote_feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'source_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Load external content by category or tag',
			'form'    => $cpb_form->get_form_remote_feed( cpb_get_input_name( $id, true ), $settings ),
		);

		$display = $cpb_form->select_field(
			cpb_get_input_name( $id, true, 'columns' ),
			$settings['columns'],
			array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			),
			'Columns'
		);

		$excerpt_length = array(
			'short'  => 'Short',
			'medium' => 'Medium',
			'long'   => 'Long',
			'full'   => 'Full',
		);

		$display = $cpb_form->select_field( cpb_get_input_name( $id, true, 'excerpt_length' ), $settings['excerpt_length'], $excerpt_length, 'Summary Length' );

		$display .= '<hr/>';

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_img' ), 1, $settings['unset_img'], 'Hide Image' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_title' ), 1, $settings['unset_title'], 'Hide Title' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_excerpt' ), 1, $settings['unset_excerpt'], 'Hide Summary' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_link' ), 1, $settings['unset_link'], 'Remove Link' );

		$html = $cpb_form->multi_form( array( $select_form, $feed_form, $remote_feed_form ) );

		return array(
			'Source'  => $html,
			'Display' => $display,
		);

	} // End get_shortcode_form

} // End List

$cpb_shortcode_list = new List_Shortcode();
