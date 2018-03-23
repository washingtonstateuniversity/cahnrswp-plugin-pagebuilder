<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc get the plugin base path
* @since 3.0.0
*
* @param string $path Optional appended path
*
* @return string Full path
*/
function cpb_get_plugin_path( $path = '' ) {

	$path = CWPPAGEBUILDERPATH . $path;

	return $path;

} // End cpb_get_plugin_path


/*
* @desc get the plugin base url
* @since 3.0.0
*
* @param string $path Optional appended path
*
* @return string Full URL
*/
function cpb_get_plugin_url( $path = '' ) {

	$path = CWPPAGEBUILDERURL . $path;

	return $path;

} // End cpb_get_plugin_path


/*
* @desc Get registerd shortcodes
* @since 3.0.0
*
* @param bool $as_slugs Return a list of slugs or full shortcode array
*
* @return array Registered shortcodes
*/
function cpb_get_shortcodes( $as_slugs = true, $in_column = false ) {

	// Set shortcodes as global scope
	global $pagebuilder_shortcodes;

	$shortcodes = $pagebuilder_shortcodes;

	if ( $in_column ) {

		foreach ( $shortcodes as $key => $settings ) {

			if ( empty( $settings['in_column'] ) ) {

				unset( $shortcodes[ $key ] );

			} // End if
		} // End foreach
	} // End if

	if ( $as_slugs ) {

		// Just return the keys
		return array_keys( $shortcodes );

	} else {

		// Return everything
		return $shortcodes;

	} // End if

} // End cpb_get_plugin_path


/*
* @desc Get registerd Column shortcodes
* @since 3.0.0
*
* @param bool $as_slugs Return a list of slugs or full shortcode array
*
* @return array Registered shortcodes
*/
function cpb_get_column_shortcodes( $as_slugs = true ) {

	$shortcodes = cpb_get_shortcodes( false );

	foreach ( $shortcodes as $key => $shortcode ) {

		if ( empty( $shortcode['in_column'] ) ) {

			unset( $shortcodes[ $key ] );

		} // End if
	} // End foreach

	if ( $as_slugs ) {

		// Just return the keys
		return array_keys( $shortcodes );

	} else {

		// Return everything
		return $shortcodes;

	} // End if

} // End cpb_get_column_shortcodes


/*
* @desc Register shortcodes
* @since 3.0.0
*
* @param string $slug Shortcode slug
* @param array $args Shortcode args
*/
function cpb_register_shortcode( $slug, $args = array() ) {

	// Set shortcodes as global scope
	global $pagebuilder_shortcodes;

	if ( empty( $pagebuilder_shortcodes ) || ! is_array( $pagebuilder_shortcodes ) ) {

		// Make sure this is set and is an array
		$pagebuilder_shortcodes = array();

	} // End if

	// Set default Args
	$default_args = array(
		'id'                        => '', // ID set later
		'label'                     => '', // Label of the item
		'render_callback'           => false, // Callback to render shortcode
		'editor_render_callback'    => false, // Override editor content display
		'form_callback'             => false, // Callback to render form
		'sanitize_callback'         => false, // Callback to sanitize from inputs
		'editor_callback'           => false, // Callback to render form
		'shortcode_callback'        => false, // Callback to build custom shortcode
		'allowed_children'          => array(), // Allowed child shortcodes
		'default_shortcode'         => false, // Default to this if no children
		'children'                  => array(), // Shortcode children
		'default_atts'              => array(), // Default Atts
		'in_column'                 => true, // Allow in column
		'uses_wp_editor'            => false, // Uses WP Editor
		'form_size'                 => 'medium', // Set form size
		'classes'                   => array(), // Additional classes
		'form_classes'              => array(), // Additional form classes to add
	);

	// Set defaults
	$args = array_merge( $default_args, $args );

	// Add to global var
	$pagebuilder_shortcodes[ $slug ] = $args;

} // End cpb_get_plugin_path


/*
* @desc Get pagbuilder shortcode structure from content
* @since 3.0.0
*
* @uses Shortcode_Parser /lib/classes/class-shortcode-parser.php
*
* @param string $content Content to look for shortcodes in
* @param array $shortcodes Shortcodes to look for
* @param bool $do_recursive Do recursive check for shortcodes
*
* @return array Array of shortcodes with children
*/
function cpb_get_shortcodes_from_content( $content, $shortcodes, $default_shortcode = false, $do_recursive = true ) {

	// Include parser class
	include_once cpb_get_plugin_path( '/lib/classes/class-shortcode-parser.php' );

	$shortcode_parser = new Shortcode_Parser();

	$shortcodes = $shortcode_parser->get_shortcodes_from_content( $content, $shortcodes, $default_shortcode, $do_recursive );

	return $shortcodes;

} // End cpb_get_shortcodes_from_content


/*
* @desc Get pagbuilder shortcode
* @since 3.0.0
*
* @uses Shortcode_Parser /lib/classes/class-shortcode-parser.php
*
* @param string $slug Shortcode slug
* @param array $atts Shortcode settings
* @param string $content Content to look for shortcodes in
* @param bool $get_children Do recursive check for children
*
* @return array Array of shortcodes with children
*/
function cpb_get_shortcode( $slug, $atts = array(), $content = '', $get_children = true ) {

	// Get registered shortcodes
	$registered_shortcodes = cpb_get_shortcodes( false );

	// if shortcode is registered by cpb_register_shortcode()
	if ( array_key_exists( $slug, $registered_shortcodes ) ) {

		// Get the registered shortcode
		$shortcode = $registered_shortcodes[ $slug ];

		// Set the slug
		$shortcode['slug'] = $slug;

		// Set the content
		$shortcode['content'] = $content;

		if ( ! is_array( $atts ) ) {

			$atts = array();

		} // End if

		// Set the atts
		$shortcode['atts'] = array_merge( $shortcode['default_atts'], $atts );

		// Set the id
		$shortcode['id'] = $shortcode['slug'] . '_' . wp_rand( 100, 1000000000 );

		// If allowes children (and $get_children = true) set the children
		if ( $get_children && ! empty( $shortcode['allowed_children'] ) ) {

			// Get the default shortcode
			$default_shortcode = $shortcode['default_shortcode'];

			// Set the children
			$shortcode['children'] = cpb_get_shortcodes_from_content( $content, $shortcode['allowed_children'], $default_shortcode );

		} // End if

		return apply_filters( 'cpb_get_shortcode', $shortcode, $get_children );

	} else {

		// Shortcode does not exist
		return false;

	} // End if

} // End cpb_get_shortcode


/*
* @desc Render given shortcode
* @since 3.0.0
*
* @param string $slug Shortcode slug
* @param array $atts Shortcode settings
* @param string $content Content to look for shortcodes in
* @param bool $render_children Do recursive check for children
*
* @return string Rendered shortcode
*/
function cpb_get_rendered_shortcode( $slug, $atts, $content, $render_children = true, $is_editor = false ) {

	$html = '';

	$inner_content = '';

	$shortcode = cpb_get_shortcode( $slug, $atts, $content, $render_children );

	if ( empty( $shortcode['allowed_children'] ) || empty( $shortcode['children'] ) ) {

		$inner_content = $content;

	} else {

		if ( is_array( $shortcode['children'] ) ) {

			foreach ( $shortcode['children'] as $index => $child_shortcode ) {

				$inner_content .= cpb_get_rendered_shortcode( $child_shortcode['slug'], $child_shortcode['atts'], $shortcode['content'], $render_children, $is_editor );
			} // End foreach
		} // End if
	} // End if

	if ( $is_editor && $shortcode['editor_render_callback'] ) {

		$html .= \call_user_func_array(
			$shortcode['editor_render_callback'],
			array(
				$shortcode['atts'],
				$inner_content,
			)
		);

	} else { // End if

		if ( $shortcode['render_callback'] ) {

			$html .= \call_user_func_array(
				$shortcode['render_callback'],
				array(
					$shortcode['atts'],
					$inner_content,
				)
			);

		} else {

			$html .= '';

		} // End if
	} // End if

	//return 'test';

	return $html;

} // End cpb_get_rendered_shortcode

/*
* @desc Get HTML for item editor
* @since 3.0.0
*
* @param array|string Array of shortcodes or single shortcode slug
*
* @return string HTML for editor
*/
function cpb_get_editor_html( $shortcodes, $is_recursive = true ) {

	if ( ! empty( $shortcodes['id'] ) ) {

		$shortcodes = array( $shortcodes );

	} // End if

	$html = '';

	if ( ! empty( $shortcodes ) ) {

		foreach ( $shortcodes as $shortcode ) {

			if ( $shortcode['editor_callback'] ) {

				$html .= call_user_func_array(
					$shortcode['editor_callback'],
					array(
						$shortcode['id'],
						$shortcode['atts'],
						$shortcode['content'],
						$shortcode['children'],
					)
				);

			} else {

				// Set the id
				$id = $shortcode['id'];

				// Set the slug
				$slug = $shortcode['slug'];

				// Set the classes
				$classes = array( 'cpb-' . $slug, 'cpb-item', 'cpb-content-item' );

				// Set the content
				$content = $shortcode['content'];

				// Set the name
				$name = $shortcode['label'];

				// If it uses the WP Editor
				if ( $shortcode['uses_wp_editor'] ) {

					$classes[] = ' cpb-wp-editor';

				} // End if

				/*if ( defined( 'CWPPAGEBUILDER_DOING_AJAX' ) && ( true === CWPPAGEBUILDER_DOING_AJAX ) ) {

				// This is only allowed if this is an AJAX Call - rendering the shortcodes in the editor window could be bad

				if ( $shortcode['render_callback'] ) {

					$shortcode_content = call_user_func_array(
						$shortcode['render_callback'],
						array(
							$shortcode['atts'],
							$shortcode['content'],
						)
					);

				} else {

					$shortcode_content = $shortcode['content'];

				} // End if

				} // End if */

				$editor_content = '<iframe id="item-content-' . esc_html( $id ) . '" class="cpb-editor-content" data-id="' . esc_html( $id ) . '" src="about:blank" scrolling="no"></iframe>';

				$editor_content .= '<textarea style="display:none;"></textarea>';

				// Implode classes for use in html
				$class = implode( ' ', $classes );

				ob_start();

				// Include the html
				include cpb_get_plugin_path( '/lib/displays/editor/generic-editor.php' );

				$html .= ob_get_clean();

			} // End if
		} // End foreach
	} // End if

	return $html;

} // End cpb_get_editor_html


/*
* @desc Get slugs from child shortcodes
* @since 3.0.0
*
* @param array $children Child shortcodes
*
* @return array Child shortcode slugs
*/
function cpb_get_child_shortcode_slugs( $children ) {

	$child_keys = array();

	if ( ! empty( $children ) ) {

		foreach ( $children as $index => $child ) {

			$child_keys[] = $child['slug'];

		} // End foreach
	} // End if

	return $child_keys;

} // end cpb_get_child_shortcode_slugs

/*
* @desc Get ids from child shortcodes
* @since 3.0.0
*
* @param array $children Child shortcodes
*
* @return array Child shortcode ids
*/
function cpb_get_child_shortcode_ids( $children ) {

	$child_keys = array();

	if ( ! empty( $children ) ) {

		foreach ( $children as $index => $child ) {

			$child_keys[] = $child['id'];

		} // End foreach
	} // End if

	return $child_keys;

} // end cpb_get_child_shortcode_slugs


/*
* @desc Get edit shortcode button
* @since 3.0.0
*
* @return string HTML for the button
*/
function cpb_get_editor_edit_button() {

	ob_start();

	include cpb_get_plugin_path( '/lib/displays/editor/edit_button.php' );

	return ob_get_clean();

} // End cpb_get_editor_edit_button


/*
* @desc Get remove shortcode button
* @since 3.0.0
*
* @return string HTML for the button
*/
function cpb_get_editor_remove_button() {

	ob_start();

	include cpb_get_plugin_path( '/lib/displays/editor/remove_button.php' );

	return ob_get_clean();

} // End cpb_get_editor_edit_button


/*
* @desc Get editor input name
* @since 3.0.0
*
* @param string $id Shortcode ID
* @param string $name Input name
* @param bool $is_setting Is setting
* @param bool | string $prefix Input prefix
*
* @return string input name
*/
function cpb_get_input_name( $id, $is_setting = true, $name = false, $prefix = false ) {

	$input_name = '_cpb[' . $id . ']';

	if ( $is_setting ) {

		$input_name .= '[settings]';

	} // End if

	if ( $name ) {

		if ( $prefix ) {

			$name = $prefix . '_' . $name;

		} // End if

		$input_name .= '[' . $name . ']';

	} // end if

	return $input_name;

} // end get_input_name

/*
* @desc Convert number to text value
* @since 3.0.0
*
* @param int $int Number to convert
*
* @return string Converted number
*/
function cpb_convert_int_to_string( $int ) {

	$index_list = 'zero,one,two,three,four,five,six,seven,eight,nine,ten';

	$index_list = explode( ',', $index_list );

	if ( ! empty( $index_list[ $int ] ) ) {

		return $index_list[ $int ];

	} else {

		return '';

	}// End if

} // End cpb_convert_int_to_string


/*
* @desc Un-nest Child shortcodes
* @since 3.0.0
*
* @param array $content_shortcodes Nested array of shortcodes
*
* @return array Un-nested array of shortcodes
*/
function cpb_flatten_shortcode_array( $content_shortcodes ) {

	$shortcodes = array();

	foreach ( $content_shortcodes as $index => $shortcode ) {

		if ( ! empty( $shortcode['children'] ) ) {

			$child_shortcodes = cpb_flatten_shortcode_array( $shortcode['children'] );

			$shortcode['children'] = array();

			$shortcodes = array_merge( $shortcodes, $child_shortcodes );

		} // End if

		$shortcodes[] = $shortcode;

	} // End foreach

	return $shortcodes;

} // End cpb_flatten_shortcode_array

/*
* @desc Get form html from nested shortcodes_array
* @since 3.0.0
*
* @param array $shortcode Shortcode class
* @param bool $as_array Return as array or string
*
* @return array|string HTML for editor form
*/
function cpb_get_shortcodes_editor_form_html( $shortcodes, $as_array = false ) {

	$editors = array();

	if ( ! empty( $shortcodes['id'] ) ) {

		$shortcodes = array( $shortcodes );

	} // End if

	$flat_shortcodes = cpb_flatten_shortcode_array( $shortcodes );

	foreach ( $flat_shortcodes as $index => $shortcode ) {

		$editors[] = cpb_get_editor_form_html( $shortcode );

	} // End foreach

	if ( ! $as_array ) {

		$editors = implode( '', $editors );

	} // End if

	return $editors;

} // End cpb_get_editor_form_html


/*
* @desc Get form html from shortcodes
* @since 3.0.0
*
* @param array $shortcode Shortcode class
*
* @return string HTML for editor form
*/
function cpb_get_editor_form_html( $shortcode ) {

	// IF this is an AJAX request and the from uses WP Editor return empty string
	if ( defined( 'CWPPAGEBUILDER_DOING_AJAX' ) && $shortcode['uses_wp_editor'] ) {

		return '';

	} // End if

	if ( $shortcode['form_callback'] ) {

		$form_content = call_user_func_array(
			$shortcode['form_callback'],
			array(
				$shortcode['id'],
				$shortcode['atts'],
				$shortcode['content'],
			)
		);

	} else {

		$form_content = '';

	} // End if

	$form_classes = array();

	if ( empty( $shortcode['allowed_children'] ) ) {

		$form_classes[] = 'cpb-content-item-form';

	} // End if

	if ( ! empty( $shortcode['uses_wp_editor'] ) ) {

		$form_classes[] = 'cpb-wp-editor-item-form';

	} // End if

	if ( ! empty( $shortcode['form_classes'] && is_array( $shortcode['form_classes'] ) ) ) {

		$form_classes = array_merge( $form_classes, $shortcode['form_classes'] );

	} // End if

	$form_args = array(
		'slug' => $shortcode['slug'],
		'class' => implode( ' ', $form_classes ),
		'title' => $shortcode['label'],
		'size' => $shortcode['form_size'],
	);

	$form_html = cpb_get_editor_form_wrapper( $shortcode['id'], $form_content, $form_args );

	return $form_html;

} // End


/*
* @desc Get form wrapper html
* @since 3.0.0
*
* @param string $id Shortcode ID
* @param array|string $form_content Form content HTML
* @param array $form_args Args to render the form
*
* @return string HTML for editor form
*/
function cpb_get_editor_form_wrapper( $id, $form_content, $form_args ) {

	$cpb_form = cpb_get_form_class();

	$form_html = $cpb_form->get_item_form( $id, $form_content, $form_args );

	return $form_html;

} // End cpb_get_editor_form_wrapper


/*
* @desc Get form class - this is a holdover from previous versions since it
* would take too long to rebuild it currently. TO DO: Rebuild form class
* @since 3.0.0
*
* @return Form Instance for editor form
*/
function cpb_get_form_class() {

	include_once cpb_get_plugin_path( '/lib/classes/class-form.php' );

	$cpb_form = new Form();

	return $cpb_form;

} // End cpb_get_form_class


/*
* @desc Get query class - this is a holdover from previous versions since it
* would take too long to rebuild it currently. TO DO: Rebuild class
* @since 3.0.0
*
* @return Query Instance
*/
function cpb_get_query_class() {

	include_once cpb_get_plugin_path( '/lib/classes/class-query.php' );

	$cpb_query = new Query();

	return $cpb_query;

} // End cpb_get_form_class


/*
* @desc Get registered layout from slug
* @since 3.0.0
*
* @param string $layout_slug Layout slug
*
* @return array Layout
*/
function cpb_get_registered_layout( $layout_slug ) {

	$layouts = cpb_get_registered_layouts();

	if ( array_key_exists( $layout_slug, $layouts ) ) {

		return $layouts[ $layout_slug ];

	} else {

		return false;

	} // End if

} // End cpb_get_registered_layout

/*
* @desc Get registered layouts
* @since 3.0.0
*
* @param bool $as_slug_label Return as slug => name only
*
* @return array Layouts
*/
function cpb_get_registered_layouts( $as_slug_label = false ) {

	$layouts = array(
		'single' => array(
			'name'    => 'Single Column',
			'columns' => array( 1 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-single-icon.gif' ),
		),
		'halves' => array(
			'name'    => 'Two Column',
			'columns' => array( 0.5, 0.5 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-halves-icon.gif' ),
		),
		'side-right' => array(
			'name'    => 'Two Column: Sidebar Right',
			'columns' => array( 0.7, 0.3 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-two-sidebar-right-icon.gif' ),
		),
		'side-left' => array(
			'name'    => 'Two Column: Sidbar Left',
			'columns' => array( 0.3, 0.7 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-two-sidebar-left-icon.gif' ),
		),
		'thirds' => array(
			'name'    => 'Three Column',
			'columns' => array( 0.33, 0.33, 0.33 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-thirds-icon.gif' ),
		),
		'thirds-half-left' => array(
			'name'    => 'Three Column: Left 50%',
			'columns' => array( 0.5, 0.25, 0.25 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-thirds-left-icon.gif' ),
		),
		'thirds-half-right' => array(
			'name'    => 'Three Column: Right 50% ',
			'columns' => array( 0.25, 0.25, 0.5 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-thirds-right-icon.gif' ),
		),
		'triptych' => array(
			'name'    => 'Three Column: Middle 50%',
			'columns' => array( 0.25, 0.5, 0.25 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-thirds-middle-icon.gif' ),
		),
		'quarters' => array(
			'name'    => 'Four Column',
			'columns' => array( 0.25, 0.25, 0.25, 0.25 ),
			'img'     => cpb_get_plugin_url( 'lib/images/column-four-icon.gif' ),
		),
	);

	return apply_filters( 'cpb_layouts', $layouts );

}

/*
* @desc Get the shortcode string from key - used on save
* @sicne 3.0.0
*
* @param string $shortcode_key Shortcode key ( slug_intid )
*
* @return string Shortcode to save
*/
function cpb_get_to_shortcode( $shortcode_key ) {

} // End cpb_get_to_shortcode

/*
* @desc Get shortcode type from shortcode key
* @since 3.0.0
*
* @param string $key Shortcode key
*
* @return string Shortcode type
*/
function cpb_get_shortcode_type_from_key( $shortcode_key ) {

	$shortcode_data = explode( '_', $shortcode_key );

	$last_id = array_pop( $shortcode_data );

	$type = implode( '_', $shortcode_data );

	return $type;

} // End cpb_get_shortcode_type_from_key

/*
* @desc Get generic defaults for local query for use in shortcodes
* @since 3.0.0
*
* @param string $prefix Prefix to add to default key
*
* @return array Defaults
*/
function cpb_get_local_query_defaults( $prefix = '' ) {

	$defaults = array(
		$prefix . 'post_type'       => '',
		$prefix . 'taxonomy'        => '',
		$prefix . 'terms'           => '',
		$prefix . 'count'           => '',
		$prefix . 'offset'          => '',
		$prefix . 'order_by'        => '',
		$prefix . 'term_operator'   => '',
	);

	return $defaults;

} // End cpb_get_local_query_defaults


/*
* @desc Get generic defaults for remote query for use in shortcodes
* @since 3.0.0
*
* @param string $prefix Prefix to add to default key
*
* @return array Defaults
*/
function cpb_get_remote_query_defaults( $prefix = '' ) {

	$defaults = array(
		$prefix . 'site_url'        => '',
		$prefix . 'post_type'       => '',
		$prefix . 'taxonomy'        => '',
		$prefix . 'terms'           => '',
		$prefix . 'count'           => '',
	);

	return $defaults;

} // End cpb_get_local_query_defaults


/*
* @desc Get generic defaults for set query for use in shortcodes
* @since 3.0.0
*
* @param string $prefix Prefix to add to default key
*
* @return array Defaults
*/
function cpb_get_select_query_defaults( $prefix = '' ) {

	$defaults = array(
		$prefix . 'site_url'        => '',
		$prefix . 'remote_items'    => '',
	);

	return $defaults;

} // End cpb_get_local_query_defaults


function cpb_get_image_properties_array( $image_id, $image_size = 'single-post-thumbnail' ) {

	$image_array = array();

	$image = \wp_get_attachment_image_src( $image_id, $image_size );

	$image_array['alt'] = \get_post_meta( $image_id, '_wp_attachment_image_alt', true );

	$image_array['src'] = $image[0];

	return $image_array;

} // End cpb_get_image_properties_array


function cpb_get_post_image_array( $post_id, $image_size = 'single-post-thumbnail' ) {

	$image_array = array();

	if ( \has_post_thumbnail( $post_id ) ) {

		$image_id = \get_post_thumbnail_id( $post_id );

		$image_array = cpb_get_image_properties_array( $image_id, $image_size );

	} // End if

	return $image_array;

} // end cpb_get_post_image_array

/*
* @desc Check advanced display options
* @since 3.0.0
*
* @param array $post_item Post Item array
* @param array $settings Item settings
* @param string $prefix Input prefix if used
*
* @return array Modified Post Item array
*/
function cpb_check_advanced_display( $post_item, $settings, $prefix = '' ) {

	if ( ! empty( $post_item['title'] ) && ! empty( $settings[ $prefix . 'unset_title' ] ) ) {

		unset( $post_item['title'] );

	} // end if

	if ( ! empty( $post_item['excerpt'] ) && ! empty( $settings[ $prefix . 'unset_excerpt' ] ) ) {

		unset( $post_item['excerpt'] );

	} // end if

	if ( ! empty( $post_item['img'] ) && ! empty( $settings[ $prefix . 'unset_img' ] ) ) {

		unset( $post_item['img'] );

	} // end if

	if ( ! empty( $post_item['link'] ) && ! empty( $settings[ $prefix . 'unset_link' ] ) ) {

		unset( $post_item['link'] );

	} // end if

	if ( ! empty( $post_item['excerpt'] ) && ! empty( $settings[ $prefix . 'excerpt_length' ] ) ) {

		switch ( $settings[ $prefix . 'excerpt_length' ] ) {

			case 'short':
				$words = 15;
				break;
			case 'long':
				$words = 40;
				break;
			case 'full':
				$words = false;
				break;
			default:
				$words = 25;
				break;

		} //end switch

		if ( $words ) {

			$post_item['excerpt'] = wp_trim_words( $post_item['excerpt'], $words, '...' );

		} // end if
	} // end if

	return $post_item;

} // End


function cpb_get_public_posts( $post_types = array(), $as_options = false, $include_empty = false ) {

	$posts = array();

	if ( $include_empty ) {

		$posts[''] = 'None Selected';

	} // end if

	if ( empty( $post_types ) ) {

		$post_type_args = array(
			'publicly_queryable' => true,
		);

		$post_types = \get_post_types( $post_type_args );

	} // end if

	$args = array(
		'post_status'     => 'publish',
		'posts_per_page'  => -1,
		'post_type'       => $post_types,
	);

	$posts_array = get_posts( $args );

	//var_dump( $posts_array );

	if ( ! empty( $posts_array ) ) {

		foreach ( $posts_array as $post_item ) {

			if ( $as_options ) {

				$posts[ $post_item->ID ] = $post_item->post_title;

			} else {

				$posts[ $$post_item->ID ] = $post_item;

			}//  End if

		} // End foreach
	} // End if

	return $posts;

} // End cpb_get_posts
