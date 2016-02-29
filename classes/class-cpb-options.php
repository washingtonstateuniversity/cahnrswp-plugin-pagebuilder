<?php
class CPB_Options {
	
	protected $option_post_types;
	
	protected $option_layout_css;
	
	protected $option_global_css;
	
	protected $fields = array( '_cpb_excerpt' , '_cpb_pagebuilder' , '_cpb_m_excerpt');
	
	
	public function __construct(){
		
		$this->set_post_types();
		
		$this->set_layout_css();
		
		$this->set_global_css();
		
	} // end __construct
	
	
	
	
	public function get_option_post_types(){ return apply_filters( 'cpb_apply_post_types' , $this->option_post_types ); }
	
	public function get_option_layout_css(){ return $this->option_layout_css; }
	
	public function get_option_global_css(){ return $this->option_global_css; } // end get_option_global_css
	
	public function get_fields(){ return $this->fields; }
	
	public function get_post_settings( $post ){
		
		$settings = array();
		
		$fields = $this->get_fields();
		
		foreach( $fields as $field ){
			
			$settings[ $field ] = get_post_meta( $post->ID , $field , true  );
			
		} // end foreach
		
		return $settings;
		
	}
	
	
	public function set_post_types( $post_types = 'na' ){
		
		if ( $post_types == 'na' ) { // get the setting if nothing provided
		
			$post_types = get_option('cpb_post_types', array('page') );
		
		} // end if
		
		$this->option_post_types = $post_types;
		
	} // end set_post_types
	
	
	public function set_layout_css( $layout_css = 'na' ){
		
		if ( $layout_css == 'na' ) { // get the setting if nothing provided
		
			$layout_css = get_option('cpb_layout_css', 0 );
		
		} // end if
		
		$this->option_layout_css = $layout_css;
		
	} // end set_post_types
	
	
	public function set_global_css( $global_css = 'na' ){
		
		if ( $global_css == 'na' ) { // get the setting if nothing provided
		
			$global_css = get_option('cpb_global_css', 0 );
		
		} // end if
		
		$this->option_global_css = $global_css;
		
	} // end set_post_types
	
}