<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Content Feed Shortcode
* @since 3.0.0
*/
class Content_Feed_Shortcode {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'feed_type'         => '',
		'tag'               => '',
		'display'           => '',
		'unset_excerpt'     => '0',
		'unset_title'       => '0',
		'unset_link'        => '0',
		'as_lightbox'       => '0',
		'csshook'           => '',
	);


	public function __construct() {

		$local_query_defaults = cpb_get_local_query_defaults();

		$this->default_settings = array_merge( $this->default_settings, $local_query_defaults );

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register action shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'content_feed', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'content_feed',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'Content Feed', // Label of the item
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
		$atts = \shortcode_atts( $this->default_settings, $atts, 'content_feed' );

		$post_items = array();

		if ( ! empty( $atts['feed_type'] ) ) {

			switch ( $atts['feed_type'] ) {

				case 'feed':
					$post_items = $this->get_post_items_feed( $atts );
					break;

			} // end switch
		} // end if

		switch ( $atts['display'] ) {

			case 'accordion':
				$html .= $this->get_accordion_display( $post_items, $atts );
				break;
			case 'list':
				$html .= $this->get_list_display( $post_items, $atts );

		} // End switch

		if ( $html ) {

			$html = '<div class="cpb-item cpb-content-feed-wrap ' . esc_html( $atts['csshook'] ) . '">' . wp_kses_post( $html ) . '</div>';

		} // end if

		return $html;

	} // End get_rendered_shortcode


	protected function get_accordion_display( $post_items, $settings ) {

		$html = '';

		$tag = $settings['tag'];

		foreach ( $post_items as $index => $post_item ) {

			$title = $post_item['title'];

			$content = apply_filters( 'cpb_the_content', \do_shortcode( $post_item['content'] ) );

			\ob_start();

			include cpb_get_plugin_path( '/lib/displays/items/accordion/accordion.php' );

			$html .= \ob_get_clean();

		} // End foreach

		return $html;

	} // End get_accordion_display


	protected function get_list_display( $post_items, $settings ) {

		$html = '<ul class="cpb-post-list">';

		$tag = $settings['tag'];

		foreach ( $post_items as $index => $post_item ) {

			$link_class = ( ! empty( $post_item['link'] ) )? 'has-link' : '';

			$title = $post_item['title'];

			$excerpt = $post_item['excerpt'];

			$link = ( ! empty( $post_item['link'] ) ) ? $post_item['link'] : '';

			\ob_start();

			include cpb_get_plugin_path( '/lib/displays/items/list/list.php' );

			$html .= \ob_get_clean();

		} // End foreach

		$html = '</ul>';

		return $html;

	} // End get_list_display


	protected function get_post_items_feed( $atts ) {

		$query = cpb_get_query_class();

		$post_items = $query->get_local_items( $atts );

		return $post_items;

	} // End get_items_feed


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

		$displays = array(
			'list'          => 'List',
			'accordion'     => 'Accordion',
		);

		$feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'feed_type' ),
			'value'   => 'feed',
			'selected' => $settings['feed_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $cpb_form->get_form_local_query( cpb_get_input_name( $id, true ), $settings ),
		);

		$html = $cpb_form->multi_form( array( $feed_form ) );

		$tags = $cpb_form->get_header_tags();

		$display = $cpb_form->select_field( cpb_get_input_name( $id, true, 'display' ), $settings['display'], $displays, 'Display As' );

		$display .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tag' ), $settings['tag'], $tags, 'Tag Type' );

		$display .= '<hr/>';

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_title' ), 1, $settings['unset_title'], 'Hide Title' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_excerpt' ), 1, $settings['unset_excerpt'], 'Hide Summary' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_link' ), 1, $settings['unset_link'], 'Remove Link' );

		$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'as_lightbox' ), 1, $settings['as_lightbox'], 'Display Lightbox' );

		$adv = $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		return array( 
			'Source'   => $html,
			'Display'  => $display,
			'Advanced' => $adv,
		);

	} // End get_shortcode_form

} // End Content Feed

$cpb_shortcode_action = new Content_Feed_Shortcode();
