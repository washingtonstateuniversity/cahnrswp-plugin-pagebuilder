<?php
class Shortcode_Regex_PB {
	
	private $shortcodes;
	
	public function __construct( $shortcodes ){
		
		$this->shortcodes = $shortcodes;
		
	} // end construct
	
	
	public function get_regex(){
		
		// Create empty array to populate later
		$tags = array();

		// Populate array with $types as keys
		foreach( $this->shortcodes as $shortcode ) {

			$tags[$shortcode] = true;

		} // end foreach

		// The keys from $shortcode_tags are used to populate the regex in parsing code
		global $shortcode_tags;

		// Temporarily write tags to temp
		$temp = $shortcode_tags;

		// Override with custom set
		$shortcode_tags = $tags;

		// Get regex code using WP function
		$regex = get_shortcode_regex();

		// Set back to original
		$shortcode_tags = $temp;

		$regex = '/' . $regex . '/';

		return $regex;
		
	} // end get_regex
	
}