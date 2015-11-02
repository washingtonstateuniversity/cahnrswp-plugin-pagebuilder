<?php

require_once 'form-pb.php';

class Form_Builder_Options_PB extends Form_PB {
	
	private $settings;
	
	public function __construct( $settings ){
		
		$this->settings = $settings; 
		
	} // end __construct
	
	public function get_form(){
		
		$html = '<div id="cwp-pb-options">';

			$html .= '<div class="cpb-title-text">Editing Mode:</div>';
			
			$html .= '<a href="#" class="cpb-edit-format-action cpb-radio-button' . $this->cpb_is_selected( 0 , $this->settings['_cpb_pagebuilder'] ) . '">Default<input type="radio" name="_cpb_pagebuilder" value="0" ' . checked( 0 , $this->settings['_cpb_pagebuilder'] , false ) . '/></a>';
			
			$html .= '<a href="#" class="cpb-edit-format-action cpb-radio-button' . $this->cpb_is_selected( 1 , $this->settings['_cpb_pagebuilder'] ) . '">Layout Builder<input type="radio" name="_cpb_pagebuilder" value="1" ' . checked( 1 , $this->settings['_cpb_pagebuilder'] , false ) . '/></a>';

		$html .= '</div>';

		return $html;
		
	} // end get_form
	
}