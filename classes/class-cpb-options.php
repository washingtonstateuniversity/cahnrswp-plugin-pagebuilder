<?php
class CPB_Options {
	
	private $post_types;
	
	private $layout_css;
	
	private $global_css;
	
	
	public function __construct(){
		
		$this->set_options();
		
	} // end __construct
	
	
	public function set_options(){
		
		$this->post_types = get_option('cpb_post_types', array('page') );
		
		$this->layout_css = get_option('cpb_layout_css', true );
		
		$this->global_css = get_option('cpb_global_css', 0 );
		
	} // end set_options
	
	
	public function get_option_post_types(){
		return apply_filters( 'cpb_apply_post_types' , $this->post_types );
	} // end get_option_post_types
	
	public function get_option_layout_css(){
		return $this->layout_css;
	} // end get_option_layout_css
	
	public function get_option_global_css(){
		return $this->global_css;
	} // end get_option_global_css
	
}