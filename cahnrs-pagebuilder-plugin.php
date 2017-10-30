<?php
/*
Plugin Name: CAHNRS Pagebuilder 2.0
Plugin URI: http://cahnrs.wsu.edu/communications
Description: Builds customizable page layouts
Author: cahnrscommunications, Danial Bleile
Author URI: http://cahnrs.wsu.edu/communications
Version: 2.3.17
*/

class CAHNRS_Pagebuilder_Plugin {
	
	private static $instance;
	
	public static $url;
	
	public static $dir;
	
	public static $is_ajax;
	
	public static $is_editor;
	
	public $items;
	
	public $options;
	
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
		
		// Constant for plugin URI
		CAHNRS_Pagebuilder_Plugin::$url = plugin_dir_url( __FILE__ ); // Plugin Base url

		// Constant for plugin Directory Path
		CAHNRS_Pagebuilder_Plugin::$dir = plugin_dir_path( __FILE__ ); // Plugin Directory Path
		
		// Static property for ajax
		CAHNRS_Pagebuilder_Plugin::$is_ajax = false;
		
		// Static property for is editor
		CAHNRS_Pagebuilder_Plugin::$is_editor = false;
		
		require_once 'classes/class-cpb-customizer.php';
		$customizer = new CPB_Customizer();
		$customizer->init();
		
		require_once 'classes/class-cpb-item.php';
		require_once 'classes/class-cpb-items.php';
		$this->items = new CPB_Items();
		
		require_once 'classes/class-cpb-shortcodes.php';
		$shortcodes = new CPB_Shortcodes( $this->items );
		
		require_once 'classes/class-cpb-options.php';
		$this->options = new CPB_Options();
		
		// Register Shortcodes
		add_action( 'init' , array( $shortcodes , 'add_shortcodes' ), 99 );
		
		add_filter( 'the_content', array( $this , 'remove_empty_p' ) , 1 );
		
		if ( is_admin() ){
			
			// Add the editor
			add_action( 'edit_form_after_title', array( $this, 'the_editor' ) , 99 );
			
			// Handle AJAX calls
			add_action( 'wp_ajax_cpb_ajax', array( $this, 'admin_ajax' ) );
			
			// Add admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			
			// Save Post
			add_action( 'save_post' , array( $this , 'save' ) );
			
			// Adds pagebuilder settings page
			add_action( 'admin_menu', array( $this, 'the_admin' ) );
			
		} else {
			
			// Add admin scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'public_scripts' ) );
			
		}// end if
		
		if ( isset( $_GET['cpb-get-template'] ) ){
			
			add_filter( 'template_include', array( $this , 'get_template'), 99 );
			
		} // end if
		
		include_once 'people/people.php';
		
	} // end init_plugin
	
	public function remove_empty_p( $content){
		
		return do_shortcode( $content );
		
		//$post_object->post_content = do_shortcode( $post_object->post_content );
		
	} // end remove_empty_p
	
	
	public function the_editor( $post ){
		
		if ( in_array( $post->post_type , $this->options->get_option_post_types() ) ){
			
			$settings = $this->options->get_post_settings( $post );
			
			// Static property for is editor
			CAHNRS_Pagebuilder_Plugin::$is_editor = true;
		
			require_once 'classes/class-cpb-editor.php';
			
			$editor = new CPB_Editor( $this->items );
			
			echo $editor->get_editor( $post , $settings );
			
			CAHNRS_Pagebuilder_Plugin::$is_editor = false;
		
		} // end if
		
	} // end the_editor
	
	public function the_admin(){
		
		require_once 'classes/class-cpb-admin.php';
		
		$admin = new CPB_Admin( $this->options );
		
	} // end the_admin
	
	public function admin_scripts(){
		
		$params = $this->items->get_name_versions();
		
		wp_enqueue_style( 'admin_css', get_site_url() . '/?cpb-get-template=stylesheet&cpb-stylesheet=admin&codes=' . implode( '_' , $params ) , false , '0.0.3' );
		
		wp_enqueue_script( 'admin_js', plugin_dir_url( __FILE__ ) . 'js/admin.js' , array('jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , '0.0.3' , true );
		
		//wp_enqueue_script( 'admin_js_3', CWPPBURL . 'js/admin-3.js' , array('jquery-ui-draggable','jquery-ui-droppable','jquery-ui-sortable') , '0.0.1' , true );
		
		//wp_enqueue_script( 'cycle2', CWPPBURL . 'js/cycle2.js' , false , '0.0.1' );

	} // end admin_scripts
	
	public function public_scripts(){
		
		$params = $this->items->get_name_versions();
		
		wp_enqueue_style( 'public_css', plugin_dir_url( __FILE__ ) . 'css/public.css' , false , '0.0.3' );
		
		wp_enqueue_style( 'template_css', get_bloginfo('url') . '/?cpb-get-template=stylesheet&cpb-stylesheet=public&codes=' . implode( '_' , $params ) , false , '0.0.2' );
		
		wp_enqueue_script( 'public_js', plugin_dir_url( __FILE__ ) . 'js/public.js' , false , '0.0.4' , true );
		
	} // end admin_scripts
	
	public function admin_ajax(){
		
		require_once 'classes/class-cpb-ajax.php';
		
		$ajax = new CPB_Ajax( $this->items );
		
		$ajax->do_request();
		
		die();
		
	} // end admin_ajax
	
	public function save( $post_id ){
		
		if ( isset( $_POST['_cpb_pagebuilder'] ) ){
			
			require_once 'classes/class-cpb-save.php';
			
			$save = new CPB_Save( $this->items );
			
			if ( $save->check_can_save ( $post_id ) ) {
		
				// unhook this function so it doesn't loop infinitely
				remove_action('save_post', array( $this , 'save' ) );
				
				$save->save_layout( $post_id );
				
				// re-hook this function
				add_action('save_post', array( $this , 'save' ) );
			
			} // end if
		
		} // end if
		
	} // end save
	
	public function get_stylesheet_template( $template ){
		
		return plugin_dir_path( __FILE__ ) . 'classes/class-cpb-stylesheet.php';
		
	} // end get_stylesheet_template
	
	public function get_template( $template ){
		
		switch( $_GET['cpb-get-template'] ){
			
			case 'stylesheet':
				$template = plugin_dir_path( __FILE__ ) . 'css/stylesheet.php';
				break;
			case 'editor-iframe':
				$template = plugin_dir_path( __FILE__ ) . 'dynamic-editor.php';
				break;
			case 'lightbox':
				$template = plugin_dir_path( __FILE__ ) . 'lightbox.php';
				break;
		} // end switch
		
		return $template;
		
	} // end get_stylesheet_template
	
	
} // end CAHNRS_Pagebuilder_Plugin

CAHNRS_Pagebuilder_Plugin::get_instance();