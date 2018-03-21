<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to Editor
* @since 3.0.0 
*/
class Editor {


	public function __construct() {

		// Add plugin actions
		$this->add_actions();

		// Add plugin filters
		$this->add_filters();

	} // End __construct


	/*
	* @desc Add WP actions for the plugin
	* @since 3.0.0
	*/
	protected function add_actions() {

		// Add editor to edit post page
		\add_action( 'edit_form_after_title', array( $this, 'add_editor'), 1 );

	} // End add_actions


	/*
	* @desc Add WP filters for the plugin
	* @since 3.0.0
	*/
	protected function add_filters() {

	} // End add_actions


	/*
	* @desc Add Editor to post type
	* @since 3.0.0
	*/
	public function add_editor( $post ) {

		$cpb_shortcodes = cpb_get_shortcodes( false );

		$content_shortcodes = cpb_get_shortcodes_from_content( $post->post_content, array('row'), 'row' );

		$options_editor = $this->get_options_editor( $post );

		$layout_editor = $this->get_layout_editor( $content_shortcodes );

		$form_editor = $this->get_form_editor( $content_shortcodes );

		$excerpt_editor = $this->get_excerpt_editor( $post );

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/editor.php' );

		$html = \ob_get_clean();

		echo $html;

	} // End add_editor


	/*
	* @desc Get HTML for layout editor
	* @since 3.0.0
	*
	* @param array $content_shortcodes Array of shortcodes
	*
	* @return string HTML of layout editor
	*/
	protected function get_options_editor( $post ) {

		$values = array( 'default' => 'Default Editor', 'builder' => 'Layout Editor' );

		$cpb = \get_post_meta( $post->ID, '_cpb_pagebuilder', true );

		if ( '' === $cpb ) {

			$cpb = 'builder';

		} else if ( '0' === $cpb ) {

			$cpb = 'default';

		} else if ( '1' === $cpb ) {

			$cpb = 'builder';

		}; // End if

		if ( 'builder' === $cpb ) {

			//\remove_post_type_support( $post->post_type, 'editor' );

		} // End if

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/options-editor.php' );

		$html = \ob_get_clean();

		return $html;

	} // End get_layout_editor


	/*
	* @desc Get HTML for layout editor
	* @since 3.0.0
	*
	* @param array $content_shortcodes Array of shortcodes
	*
	* @return string HTML of layout editor
	*/
	protected function get_layout_editor( $content_shortcodes ) {

		$child_ids = array();

		$editor_content = cpb_get_editor_html( $content_shortcodes );

		$child_ids = cpb_get_child_shortcode_ids( $content_shortcodes );

		$child_ids = \implode( ',', $child_ids );

		$add_shortcode_form = $this->get_add_shortcode_form();

		$add_row_form = $this->get_add_row_form();

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/layout-editor.php' );

		$html = \ob_get_clean();

		return $html;

	} // End get_layout_editor


	/*
	* @desc Get HTML for form editor
	* @since 3.0.0
	*
	* @param array $content_shortcodes Array of shortcodes
	*
	* @return string HTML of form editor
	*/
	protected function get_form_editor( $content_shortcodes ) {

		$content_shortcodes_flat = cpb_flatten_shortcode_array( $content_shortcodes );

		$form_html = '';

		$empty_editors = '';

		if ( is_array( $content_shortcodes_flat ) ) {

			foreach( $content_shortcodes_flat as $index => $shortcode ) {

				$form_html .= cpb_get_editor_form_html( $shortcode ); 

			} // End foreach

		} // End if

		$text_editors = array( 'textblock' );

		foreach( $text_editors as $text_editor ) {

			for ( $i = 0; $i < 10; $i++ ) {

				$text_shortcode = cpb_get_shortcode( $text_editor, array(), '', false );

				$text_shortcode['form_classes'] = array('cpb-blank-editor');

				$empty_editors .= cpb_get_editor_form_html( $text_shortcode ); 

			} // end for

		} // end foreach

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/form-editor.php' );

		$html = \ob_get_clean();

		return $html;

	} // End get_layout_editor


	/*
	* @desc Get HTML for excerpt editor
	* @since 3.0.0
	*
	* @param WP_Post $post Current Post
	*
	* @return string HTML of editor
	*/
	protected function get_excerpt_editor( $post ) {

		$excerpt_type = \get_post_meta( $post->ID, '_cpb_m_excerpt', true );

		if ( '' === $excerpt_type ) {

			$excerpt_type = 0;

		} // End if

		$values = array( 'Default Excerpt', 'Custom Excerpt' );

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/excerpt-editor.php' );

		$html = \ob_get_clean();

		return $html;

	} // End get_excerpt_editor


	/*
	* @desc Get form HTML for adding rows
	* @since 3.0.0
	*
	* @return string HTML for form
	*/
	protected function get_add_row_form() {

		$layouts = array(
			'single' => 'Single Column',
			'halves' => 'Two Column',
			'side-right' => 'Two Column: Sidebar Right',
			'side-left' => 'Two Column: Sidbar Left',
			'thirds' => 'Three Column',
			'thirds-half-left' => 'Three Column: Left 50%',
			'thirds-half-right' => 'Three Column: Right 50% ',
			'triptych' => 'Three Column: Middle 50%',
			'quarters' => 'Four Column',
		);

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/add-row-editor.php' );

		$html = \ob_get_clean();

		return $html;

	} // end get_add_row_form


	/*
	* @desc Get form HTML for adding rows
	* @since 3.0.0
	*
	* @return string HTML for form
	*/
	protected function get_add_shortcode_form() {

		$shortcodes = cpb_get_shortcodes( false );

		\ob_start();

		include cpb_get_plugin_path( '/lib/displays/editor/add-shortcode-editor.php' );

		$shortcode_html = \ob_get_clean();

		$form_content = array( 'Select Item' => $shortcode_html );

		$form_args = array('title' => 'Add Items & Widgets');

		$html = cpb_get_editor_form_wrapper( 'cpb-add-item-form', $form_content, $form_args );

		return $html;

	} // end get_add_row_form


} // End Editor

$cpb_editor = new Editor();