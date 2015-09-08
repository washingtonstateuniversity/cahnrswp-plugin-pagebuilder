<?php
/*
Plugin Name: CAHNRSWP Pagebuilder (Final)
Plugin URI: https://web.wsu.edu/
Description: Builds Stuff
Author: CAHNRS Communication WSU, Danial Bleile
Version: 1.0.0
*/

//Start plugin class
class CWP_Pagebuilder { 
	
	// Items object
	public $items;
	
	public $options;
	
	public function __construct(){
		
		// Constant for plugin URI
		define( 'CWPPBURL' , plugin_dir_url( __FILE__ ) ); // Plugin Base url
		
		// Constant for plugin Directory Path
		define( 'CWPPBDIR' , plugin_dir_path( __FILE__ ) ); // Plugin Directory Path
		
		// Form classes
		require_once 'classes/class-forms-pb.php';
		
		// Query classes
		require_once 'classes/class-query-pb.php';
		
		// Display classes
		require_once 'classes/class-display-pb.php';
		
		// Abstract item class
		require_once 'items/item-pb.php';
		
		// Set items object
		require_once 'classes/class-items-pb.php';
		$this->items = new Items_PB();
		
		// Start options object
		require_once 'classes/class-options-pb.php';
		$this->options = new Options_PB();
		
		// Set options
		$this->options->set_settings();
		
		// Sets the item and registers shortcodes
		add_action( 'init' , array( $this , 'init_plugin' ), 99 );
		
		// Add the editor
		add_action( 'edit_form_after_title' , array( $this , 'editor' ) ); 
		
		// Add admin scripts
		add_action( 'admin_enqueue_scripts' , array( $this , 'admin_scripts' ) );
		
		// Handle AJAX calls 
		add_action( 'wp_ajax_cpb_ajax', array( $this , 'ajax_request' ) );
		
		// Save the post
		add_action( 'save_post' , array( $this , 'save_layout' ) , 10 , 3 );
		
		// Adds pagebuilder settings page
		add_action( 'admin_menu' , array( $this->options , 'add_page' ) );
		
		if ( $this->options->settings['cpb_layout_css'] ){
			
			add_action( 'wp_enqueue_scripts', array( $this , 'enqueue_public_scripts' ) );
			
		} // end if
		
	} // end __construct
	
	
	public function enqueue_public_scripts(){
		
		wp_enqueue_style( 'cpb-public-layout', CWPPBURL . 'css/layout.css' , array() , '0.0.1' );
		
	} // end enqueue_public_scripts
	
	/*
	 * Set up items and shortcodes which are used in both public
	 * and admin instances
	*/
	public function init_plugin(){

		// Register items
		$this->items->register_items();
		
		// Register shortcodes
		$this->items->register_shortcodes();
		
		// Temp: Remove editor from pagebuilder post types
		$this->options->remove_editor();
		
	} // end init_plugin
	
	
	/*
	 * Build and render plugin for selected
	 * post types under settings
	*/
	public function editor( $post ){
		
		if ( $this->check_post_type( $post->post_type ) ){
			
			require_once 'classes/class-editor-pb.php';
			
			$editor = new Editor_PB( $this->items );
			
			$editor->the_editor( $post );
		
		} // end if
		
	} // end editor
	
	
	/*
	* Add CSS and JS for the admin editor.
	*/
	public function admin_scripts(){
		
		wp_enqueue_style( 'admin_css', CWPPBURL . 'css/admin.css' , false , '0.0.1' );
		
		wp_enqueue_script( 'admin_js', CWPPBURL . 'js/admin.js' , array('jquery-ui-draggable','jquery-ui-droppable') , '0.0.1' );
		
	} // end admin_scripts
	
	
	/*
	* Handles AJAX Requests sent by the plugin
	*/
	public function ajax_request(){
		
		require_once 'classes/class-ajax-pb.php';
		
		$ajax = new AJAX_PB( $this->items );
		
		$ajax->do_request();
		
		die();
		
	} // end ajax_request 
	
	public function save_layout( $post_id , $post , $update ){
		
		if ( ! wp_is_post_revision( $post_id ) ){
			
			if ( $this->check_post_type( $post->post_type ) ){
		
				require_once 'classes/class-save-pb.php';
				
				$save = new Save_PB( $this->items );
				
				remove_action('save_post', array( $this , 'save_layout') );
				
				$save->save( $post_id );
			
			} // end if
		
		} // end if
		
	} // end save
	
	/*public function admin_menu(){
		
		$this->options->add_page();
		
	} // end admin_menu*/
	
	public function check_post_type( $post_type ){
		
		$post_types = get_option('cpb_post_types');
		
		if ( is_array( $post_types ) && in_array( $post_type , $post_types ) ) {
			
			return true;
			
		} else {
			
			return false;
			
		} // end if
		
	} // end check_post_type
	
	
} // end CWP_Pagebuilder

$cwp_pb = new CWP_Pagebuilder();