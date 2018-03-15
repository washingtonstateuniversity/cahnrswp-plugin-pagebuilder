<?php

class Item_Twitter_CPB {
	
	
	protected $default_atts = array(
		'type'		=> 'twitter-timeline',
		'src' 		=> '',
		'height' 	=> '800',
	);
	
	
	public function __construct(){
		
		cpb_register_item( 
			'twitter', 
			array(), 
			array( $this, 'get_form' ), 
			array( $this, 'get_shortcode' ), 
			array( $this, 'get_sanitized' ) 
		);
		
		add_shortcode( 
			'twitter', 
			array( $this, 'get_item') 
		);
		
	} // End __construct
	
	
	public function get_item( $atts, $content = '', $tag = '' ){
		
		$atts = shortcode_atts( $this->default_atts, $atts );
		
		$html = '';
		
		if ( ! empty( $atts['src'])){
			
			$html .= '<a class="' . $atts['type'] . '" data-height="' . $atts['height'] . '" href="' . $atts['src'] . '">Tweets by wsucahnrs</a>';
			
			$html .= '<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
			
		} // End if
		
		return $html;
		
	} // End the_item
	
	
	public function get_form( $atts, $args = array(), $content = '', $tag = '' ){
		
		$atts = shortcode_atts( $this->default_atts, $atts );
		
		$html = '';
		
		$html .= 'form';
		
		return $html;
		
	} // End the_editor
	
	
	public function get_shortcode( $atts, $args = array(), $content = '', $tag = '' ){
		
	} // End to_shortcode
	
	
	public function get_sanitized( $atts, $args = array(), $content = '', $tag = '' ){
		
	} // End sanitize_values
	
} // End Item_Twitter_Embed_CPB

$item_twitter_cpb = new Item_Twitter_CPB();