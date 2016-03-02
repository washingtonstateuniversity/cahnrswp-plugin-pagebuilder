<?php

require_once 'form-pb.php';

class Form_Excerpt_PB extends Form_PB {
	
	private $settings;
	
	private $post;
	
	public function __construct( $settings , $post ){
		
		$this->settings = $settings; 
		
		$this->post = $post;
		
	} // end __construct
	
	public function get_form(){
		
		if ( $this->settings['_cpb_m_excerpt'] == 'manual' ) {
			
			$excerpt = $this->settings['_cpb_excerpt']; 
			
		} else {
			
			$excerpt = $this->post->post_excerpt;
			
		} // end if

		$html = '<div id="cpb-excerpt">';
		
			$html .= '<div class="cpb-title-text">Excerpt Mode:</div>';
			
			$html .= $this->radio_toggle_button( '_cpb_m_excerpt' , 'auto' , $this->settings['_cpb_m_excerpt'] , 'Auto' , $class = '' );
			
			$html .= $this->radio_toggle_button( '_cpb_m_excerpt' , 'manual' , $this->settings['_cpb_m_excerpt'] , 'Manual' , $class = '' );

			$html .= '<textarea name="_cpb_excerpt">' . $excerpt . '</textarea>';

		$html .= '</div>';

		return $html;

		return $html;
		
	} // end get_form
	
}