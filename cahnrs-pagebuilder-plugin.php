<?php
/*
Plugin Name: CAHNRS Pagebuilder
Plugin URI: http://cahnrs.wsu.edu/communications
Description: Builds customizable page layouts
Author: cahnrscommunications, Danial Bleile
Author URI: http://cahnrs.wsu.edu/communications
Version: 0.0.1
*/

class CAHNRS_Pagebuilder_Plugin {
	
	public static $url;
	
	public static $dir;
	
	private $items_factory;
	
	private static $instance;
	
	/**
	 * Singleton Pattern - only one instance of 
	 * class exists
	**/ 
	public static function get_instance(){
		
		if ( null == self::$instance ) {
            self::$instance = new self;
			self::$instance->init_plugin();
        } // end if
 
        return self::$instance;
		
	} // end get_instance

	
	public function init_plugin(){
		
		require_once 'classes/class-cpb-items-factory.php';
		
		$this->items_factory = new CPB_Items_Factory();
		
		// Add the editor
		add_action( 'edit_form_after_title', array( $this, 'the_editor' ) );
		
	} // end init_plugin
	
	
	public function the_editor( $post ){
		
		require_once 'classes/class-cpb-editor.php';
		
		require_once 'classes/class-cpb-shortcodes.php';
		
		$cpb_shortcodes = new CPB_Shortcodes( $this->items_factory );
		
		$editor = new CPB_Editor( $this->items_factory , $cpb_shortcodes );
		
		echo $editor->get_editor( $post );
		
	} // end the_editor
	
	
}

CAHNRS_Pagebuilder_Plugin::get_instance();