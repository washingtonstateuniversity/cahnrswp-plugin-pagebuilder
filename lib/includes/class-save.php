<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Save
* @since 3.0.0
*/
class Save {

	public function __construct() {

		\add_action( 'save_post', array( $this, 'do_save_layout' ) );

	} // End


	/*
	* @desc Check for layout and save it exists
	* @since 3.0.0
	*
	* @param int $post_id WP post id
	*/
	public function do_save_layout( $post_id ) {

		// If can't save abort
		if ( ! $this->check_can_save( $post_id ) ) {

			return;

		}

		// Get the pagebuilder settings
		$settings = $this->get_settings( $post_id );

		// If is set to builder and the layout isn't empty
		if ( ( 'builder' === $settings['_cpb_pagebuilder'] ) && ! empty( $settings['_cpb']['layout'] ) ) {

			// Get string html for shortcodes
			$shortcodes = $this->get_layout_shortcodes_recursive( $settings, $post_id );

			// Get the excerpt
			$excerpt = $this->get_excerpt( $settings, $shortcodes );

			// Set the post to insert
			$post = array(
				'ID'           => $post_id,
				'post_content' => $shortcodes,
				'post_excerpt' => $excerpt,
			);

			// No infinite loops here - good to avoid that
			\remove_action( 'save_post', array( $this, 'do_save_layout' ) );

			// Update the post into the database
			wp_update_post( $post );

		} // End if

	} // End do_save_layout


	/*
	* @desc Check if can save or not
	* @since 3.0.0
	*
	* @param int $post_id WP post id
	*
	* @return bool Allow save
	*/
	protected function check_can_save( $post_id ) {

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;

		} // end if

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {

				return false;

			} // end if
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {

				return false;

			} // end if
		} // end if

		if ( ! isset( $_POST['cahnrs_pagebuilder_key'] ) || ! wp_verify_nonce( $_POST['cahnrs_pagebuilder_key'], 'save_cahnrs_pagebuilder_' . $post_id ) ) {

			return false;

		}

		return true;

	}


	/*
	* @desc Get the save settings
	* @since 3.0.0
	*
	* @param int $post_id WP Post ID
	*
	* @return array Settings with values
	*/
	protected function get_settings( $post_id ) {

		$fields = array(
			'_cpb_excerpt'      => ( ! empty( $_POST['_cpb_excerpt'] ) ) ? sanitize_text_field( $_POST['_cpb_excerpt'] ) : '',
			'_cpb_pagebuilder'  => ( ! empty( $_POST['_cpb_pagebuilder'] ) ) ? sanitize_text_field( $_POST['_cpb_pagebuilder'] ) : '',
			'_cpb_m_excerpt'    => ( ! empty( $_POST['_cpb_m_excerpt'] ) ) ? sanitize_text_field( $_POST['_cpb_m_excerpt'] ) : '',
			'_cpb'              => ( ! empty( $_POST['_cpb'] ) ) ? $_POST['_cpb'] : '',  // TO DO: Sanitize array
		);

		return $fields;

	} // End get_settings


	/*
	* @desc Get content to save
	* @since 3.0.0
	*
	* @param array $settings Builder settings
	* @param int $post_id WP Post ID
	*
	* @return array Nested Shortcodes to save
	*/
	protected function get_layout_shortcodes_recursive( $settings, $post_id ) {

		// String for the shortcode to build
		$shortcode_string = '';

		// Get the set layout
		$layout = sanitize_text_field( $settings['_cpb']['layout'] );

		// Get the nested shortcode structure for the layout
		$shortcodes = $this->get_shortcode_array_recursive( $layout, $settings );

		// Let's make sure this is an array
		if ( is_array( $shortcodes ) ) {

			// Loop through all shortcodes and convert to string
			foreach ( $shortcodes as $index => $shortcode ) {

				// Convert shortcode array to string for save
				$shortcode_string .= $this->get_to_shortcode_recursive( $shortcode );

			} // End foreach
		} // End if

		return $shortcode_string;

	} // End get_layout_shortcodes


	private function get_excerpt( $settings, $content ) {

		if ( ! empty( $settings['_cpb_m_excerpt'] ) ) {

			$excerpt = ( ! empty( $settings['_cpb_excerpt'] ) ) ? $settings['_cpb_excerpt'] : '';

		} else {

			$excerpt = $this->get_excerpt_from_post_content( $content );

		} // end if

		return $excerpt;

	} // end get_excerpt


	/*
	* @desc Get the excerpt from the post
	* @since 3.0.0
	*
	* @param WP_Post $post WP Post object
	*
	* @return string Post excerpt
	*/
	protected function get_excerpt_from_post_content( $content ) {

		// We'll start with the post content
		$excerpt = $content;

		// Remove shortcodes but keep text inbetween ]...[/
		$excerpt = \preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $excerpt );

		// Remove HTML tags and script/style
		$excerpt = \wp_strip_all_tags( $excerpt );

		// Shorten to 35 words and convert special characters
		$excerpt = \htmlspecialchars( \wp_trim_words( $excerpt, 35 ) );

		return $excerpt;

	} // End get_excerpt_from_post


	protected function get_to_shortcode_recursive( $shortcode ) {

		// We'll add the built shortcodes here
		$shortcode_string = '';

		// Child shortcode content added here
		$shortcode_content = '';

		// Does the shortcode have children
		if ( ! empty( $shortcode['children'] ) ) {

			// Loop through the children
			foreach ( $shortcode['children'] as $index => $child_shortcode ) {

				// Add string value to child shortcode content
				$shortcode_content .= $this->get_to_shortcode_recursive( $child_shortcode );

			} // End forach
		} // End if

		// Does this shortcode have a callback to build itself?
		if ( ! empty( $shortcode['shortcode_callback'] ) ) {

			// Get shortcode string from callback
			$shortcode_string = \call_user_func_array(
				$shortcode['shortcode_callback'],
				array(
					$shortcode,
					$shortcode_content,
				)
			);

		} else { // no shortcode callback

			// Start the shortcode
			$shortcode_string = '[' . $shortcode['slug'];

			// Convert atts to name=value pairs
			$atts = $this->get_convert_atts( $shortcode['atts'] );

			// If there are atts add them to the shortcode
			if ( ! empty( $atts ) ) {

				$shortcode_string .= ' ' . $atts . ' ';

			} // End if

			// Does the shortcode have child content?
			if ( ! empty( $shortcode_content ) ) {

				// Add inner content ( $shortcode_content) and close the shortcode
				$shortcode_string .= ']' . $shortcode_content . '[/' . $shortcode['slug'] . ']';

			} else if ( ! empty( $shortcode['content'] ) ) { // Does the shortcode have content?

				// Add the content
				$shortcode_string .= ']' . $shortcode['content'] . '[/' . $shortcode['slug'] . ']';

			} else {

				// Nope, let's just close the shortcode
				$shortcode_string .= ']';

			} // End if
		} // End if

		return $shortcode_string;

	} // End get_to_shortcode_recursive


	protected function get_convert_atts( $shortcode_atts ) {

		$converted = array();

		foreach ( $shortcode_atts as $key => $value  ) {

			$converted[] = $key . '="' . $value . '"';

		} // End foreach

		return implode( ' ', $converted );

	} // End get_convert_atts


	/*
	* @desc Build shortcode array from post
	* @since
	*
	* @param string $shortcodes_list List of shortcodes
	* @param array $settings CPB settings submitted from form
	*
	* @return array Nested array of shortcodes
	*/
	protected function get_shortcode_array_recursive( $shortcodes_list, $settings ) {

		// We'll populate this later
		$shortcodes = array();

		// Split the layout by comma
		$layout = explode( ',', $shortcodes_list );

		// Make sure we are dealing with an array here
		if ( is_array( $layout ) ) {

			// Loop through keys i.e. row_32305823508
			foreach ( $layout as $index => $shortcode_key ) {

				// Split key to get shortcode slug
				$shortcode_slug = cpb_get_shortcode_type_from_key( $shortcode_key );

				// Get the registered shortcode from slug
				$shortcode = cpb_get_shortcode( $shortcode_slug, array(), '', false );

				// cpb_get_shortcode assigns a random id, let's override it with the actual id
				$shortcode['id'] = $shortcode_key;

				// Does this use a wp_editor in it?
				if ( $shortcode['uses_wp_editor'] ) {

					// WP Eitors use a special name pattern _cpb_content_.....ID
					$content_key = '_cpb_content_' .  $shortcode_key;

					// Check if it has content
					if ( ! empty( $_POST[ $content_key ] ) ) {

						// Sanitize and add to content key
						$shortcode['content'] = wp_kses_post( $_POST[ $content_key ] );

					} // End if
				} // End if

				// Check if any settings have been sent with the $_POST request
				$save_settings = ( ! empty( $settings['_cpb'][ $shortcode_key ]['settings'] ) ) ? $settings['_cpb'][ $shortcode_key ]['settings'] : array();

				// Add that to the atts key after getting and sanitizing
				$shortcode['atts'] = $this->get_save_settings( $save_settings, $shortcode );

				// Does this have any child items
				if ( ! empty( $settings['_cpb'][ $shortcode_key ]['settings']['children'] ) ) {

					// Get the children
					$children = $settings['_cpb'][ $shortcode_key ]['settings']['children'];

					if ( ! empty( $children ) ) {

						// Get child shortcodes and add to the children key
						$shortcode['children'] =  $this->get_shortcode_array_recursive( $children, $settings );

					} // End if
				} // End if

				// Add shortcode to shortcodes array
				$shortcodes[] = $shortcode;

			} // End foreach
		} // End if

		return $shortcodes;

	} // End get_shortcode_array_recursive


	/*
	* @desc Remove default settings and sanitize
	* @since 3.0.0
	*
	* @param array $shortcode Registered shortcode
	* @param array $default_settings Default shortcode settings
	*
	* @return array Settings to save
	*/
	protected function get_save_settings( $save_settings, $shortcode ) {

		// Key = Value pairs to save after removing default and empty keys
		$to_save = array();

		// Loop through default settings
		foreach ( $shortcode['default_atts'] as $key => $default_value ) {

			// Check if default setting is in save_settings
			if ( array_key_exists( $key, $save_settings ) ) {

				// Get default value
				$default_value = $shortcode['default_atts'][ $key ];

				// Get value that is being save
				$save_value = $save_settings[ $key ];

				// If the save value is same as default, or is empty string, or is literally default do nothing
				if ( ( $default_value !== $save_value ) && ( '' !== $save_value ) && ( 'default' !== $save_value ) ) {

					// Otherwise add to to_save array
					$to_save[ $key ] = $save_value;

				} // End if

			} // End if

		} // End foreach

		// Does this shortcode have a callback to sanitize the settings?
		if ( $shortcode['sanitize_callback'] ) {

			// Cool let's do that
			$clean_settings = call_user_func_array( $shortcode['sanitize_callback'], array( $to_save ) );

			// Just checking to make sure this is is an array
			if ( ! is_array( $clean_settings ) ) {

				$clean_settings = array();

			} // End if

		} else { // No sanitize callback - well fine then, let's just make it up

			$clean_settings = array();

			// Loop through values trying to save
			foreach ( $to_save as $key => $value ) {

				// Just some basic sanity
				$clean_settings[ $key ] = sanitize_text_field( $value );

			} // End foreach

		} // End if

		return $clean_settings;

	} // End get_save_settings


} // End Save

$cpb_save = new Save();
