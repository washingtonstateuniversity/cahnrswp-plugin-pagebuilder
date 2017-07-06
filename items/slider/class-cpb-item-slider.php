<?php

class CPB_Item_Slider extends CPB_Item {
	
	protected $name = 'Horizontal Slider';
	
	protected $slug = 'slider';
	
	protected $form_size = 'medium';
	
	
	public function item( $settings , $content ){
		
		ob_start();
		
		include 'parts/slider.php';
		
		$html = ob_get_clean();
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' ); 
		
		return array('Basic' => $html );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		return $clean;
	}
	
}