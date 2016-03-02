<?php

require_once 'class-shortcode-regex-pb.php';

class Parse_Shortcode_PB extends Shortcode_Regex_PB {
	
	private $content;
	
	
	public function __construct( $content ){
		
		$this->content = $content;
		
	} // end construct
	
	
	public function get_shortcode_array( $content , $search_array , $default_shortcode = false , $default_settings = array() , $wrap = true ){
		
		if ( ! is_array( $search_array ) ) $search_array = array( $search_array );
		
		$regex = new Shortcode_Regex_PB( $search_array );
		
		preg_match_all( $regex->get_regex() , $content , $matches );
		
		if ( ! empty( $matches ) ){
			
			$shortcode_array = $this->get_matches( $matches );
			
		} else if ( $default_shortcode ) {
			
			$shortcode_array = array(
				array(
					'shortcode' => $default_shortcode,
					'content'   => $content,
					'settings'  => $default_settings,
				),
			);
			
		} else {
			
			$shortcode_array = array();
			
		}// end if
		
		return $shortcode_array;
		
	} // end get_shortcode_array
	
	
	private function get_matches( $matches ){
		
		$shortcodes = array();
		
		if ( ! empty( $matches[0] ) ){
			
			foreach( $matches[0] as $index => $match ){
				
				$shortcodes[ $index ]['fullcontent'] = $matches[0][ $index ];
				
				$shortcodes[ $index ]['shortcode'] = $matches[2][ $index ];
				
				$shortcodes[ $index ]['content'] = $matches[5][ $index ]; 
				
				$shortcodes[ $index ]['settings'] = $this->parse_settings( $matches[3][ $index ] );
				
			} // end foreach
			
		} // end if
		
		return $shortcodes;
		
	} // end get_matches
	
	
	private function parse_settings( $settings_string ){
		
		if ( $settings_string ){
		
			$settings = shortcode_parse_atts( $settings_string );
		
		} else {
			
			$settings = array();
			
		} // end if
		
		return $settings;
		
	} // end parse_settings
	
	
}