<?php
class CPB_Editor {
	
	private $items_factory;
	
	private $cpb_shortcodes;
	
	
	public function __construct( $items_factory , $cpb_shortcodes ){
		
		$this->items_factory = $items_factory;
		
		$this->cpb_shortcodes = $cpb_shortcodes;
		
	} // end __construct
	
	public function get_editor( $post ){
		
		$this->cpb_shortcodes->do_admin_shortcodes( $post->post_content , $post );
		
		return 'hello world';
		
	} // end get_editor
	
	
}