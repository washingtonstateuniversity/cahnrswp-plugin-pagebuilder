<?php
class CPB_Shortcodes {
	
	private $items_factory;
	
	public function __construct( $items_factory ){
		
		$this->items_factory = $items_factory;
		
	}
	
	public function do_shortcodes( $content , $post = false ){
	} // end do_shortcodes
	
	public function do_admin_shortcodes( $content , $post = false ){
	} // end do_admin_shortcodes
	
}