<?php

class Shortcode_CAHNRSWP_People {
	
	protected $people_factory;
	
	public function __construct( $people_factory, $do_init = false ){
		
		$this->people_factory = $people_factory;
		
		if ( $do_init ){
			
			$this->init();
			
		} // end if
		
	} // end construct
	
	
	public function init(){
		
		add_shortcode( 'cahnrswp_people', array( $this, 'get_the_shortcode' ) );
		
	} // end init
	
	
	public function get_the_shortcode( $atts ){
		
		$html = '';
		
		$default_atts = array(
			'display' 		=> '',
			'taxonomy' 		=> 'post_tags',
			'terms' 		=> '',
			'count' 		=> -1,
			'order' 		=> 'title',
			'bio' 			=> '',
			'bio-label' 	=> '',
			'show_profile' 	=> 1,
			'link' 			=> 1,
			'profile' 		=> 0,
			'tags'			=> '',
		);
		
		$atts = shortcode_atts( $default_atts, $atts, 'cahnrswp_people' );
		
		if ( $atts['profile'] ){
			
			$html .= $this->get_person_profile( $atts );
			
		} else {
			
			$people = $this->people_factory->get_people( $atts );
			
			switch( $atts['profile'] ){
				
				case 'table':
					$html = $this->get_table_display( $people, $atts );
					break;
				
			} // end switch
			
		} // end if
		
		return $html;
		
	} // end $atts
	
	
	protected function get_table_display( $people, $atts ) {
		
		$html = '';
		
		$people_html = '';
		
		$header_html = ''; 
		
		ob_start();
		
		include CAHNRS_Pagebuilder_Plugin::$dir . 'people/includes/table-display/header.php';
		
		$header_html .= ob_get_clean();
		
		foreach( $people as $index => $person ){
			
			ob_start();
			
			include CAHNRS_Pagebuilder_Plugin::$dir . 'people/includes/table-display/person.php';
			
			$people_html .= ob_get_clean();
			
		} // end foreach
		
		ob_start();
		
		include CAHNRS_Pagebuilder_Plugin::$dir . 'people/includes/table-display/table.php';
		
		$html .= ob_get_clean();
		
		return $html;
		
	} // end get_table_display
	
	
} // end class Shortcode_CAHNRSWP_People