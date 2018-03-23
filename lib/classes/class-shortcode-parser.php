<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Encapsulate shortcode parsing code
* @since 3.0.0
*/
class Shortcode_Parser {

	/*
	* @desc Get pagbuilder shortcode structure from content
	* @since 3.0.0
	*
	* @param string $content Content to look for shortcodes in
	* @param array $allowed_shortcodes Shortcodes to look for
	* @param bool $is_recursive Do recursive check for shortcodes
	*
	* @return array Array of shortcodes with children
	*/
	public function get_shortcodes_from_content( $content, $allowed_shortcodes, $default_shortcode = false, $is_recursive = true ) {

		// If has value in_column
		if ( 'in_column' === $allowed_shortcodes ) {

			// Get shortcodes allowed in column
			$allowed_shortcodes = cpb_get_column_shortcodes();

		} // End if

		// Get registered shortcodes
		$registered_shortcodes = cpb_get_shortcodes( false );

		// Create array to add shortcodes to
		$shortcodes = array();

		// Get modified regex for parsing shortcodes
		$regex = $this->get_shortcode_regex( $allowed_shortcodes );

		// Split content to account for malformed shortcodes
		$split_content = $this->split_content( $content, $regex );

		// Loop through and add items
		foreach ( $split_content as $index => $shortcode_content ) {

			$trimmed_content = trim( $shortcode_content );

			// Ignore empty if more than one set
			if ( empty( $trimmed_content ) && 1 < count( $split_content ) ) {

				continue;

			} // End if

			// Look for items
			\preg_match_all( $regex, $shortcode_content, $shortcode_data );

			if ( ! empty( $shortcode_data[2] ) ) { // item found

				// Get the item
				$shortcode = cpb_get_shortcode(
					$shortcode_data[2][0],
					\shortcode_parse_atts( $shortcode_data[3][0] ),
					$shortcode_data[5][0]
				);

			} elseif ( $default_shortcode ) { // no items found and default exists set default

				$shortcode = cpb_get_shortcode( $default_shortcode, array(), $shortcode_content );

			} else {

				$shortcode = false;

			}// end if

			if ( $shortcode ) {

				$shortcodes[] = $shortcode;

			} // End if
		} // end foreach

		return $shortcodes;

	} // End get_shortcodes_from_content


	/*
	* @desc Get search regex for shortcode using built WP functions
	* @since 3.0.0
	*
	* @param array $allowed_shortcodes Shortcodes to look for
	*
	* @return string Search regex
	*/
	protected function get_shortcode_regex( $allowed_shortcodes ) {

		// Create empty array to populate later
		$slugs = array();

		// Populate $slugs array with shortcode slugs as keys (needed for WP)
		foreach ( $allowed_shortcodes as $shortcode ) {

			$slugs[ $shortcode ] = true;

		} // end foreach

		// The keys from $shortcode_tags are used to populate the regex in parsing code
		global $shortcode_tags;

		// Temporarily write tags to temp
		$temp = $shortcode_tags;

		// @codingStandardsIgnoreStart Override with custom set
		$shortcode_tags = $slugs;

		// Get regex code using WP function
		$regex = \get_shortcode_regex();

		$shortcode_tags = $temp;
		// @codingStandardsIgnoreEnd Set back to original

		$regex = '/' . $regex . '/';

		return $regex;

	} // End get_shortcode_regex


	/*
	* @desc Split content by shortcode to account for malformed shortcodes
	* @since 3.0.0
	*
	* @param string $content Content to split
	* @param string $regex Regex to use in search
	*
	* @return array Content split by shortcode instance
	*/
	protected function split_content( $content, $regex ) {

		if ( '' === $content ) {

			$content = ' ';

		} // End if

		// Add Delimiter to content. This is required to account for content outside of shortcodes
		$content_set = \preg_replace_callback(

			$regex, function( $matches ) {

				return '|$|' . $matches[0] . '|$|';

			},
			$content
		);

		// Split into an array of content and shortcodes
		$content_set = \explode( '|$|', $content_set );

		return $content_set;

	} // End split_content


} // End Shortcode_Parser
