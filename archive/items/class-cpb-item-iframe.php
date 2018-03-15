<?php

class CPB_Item_Iframe extends CPB_Item {
	
	protected $slug = 'iframe';

	protected $name = 'IFrame';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['src'] ) ){
			
			$html .= '<iframe src="' . $settings['src'] . '" frameborder="0" ';
			
			$html .= 'style="height:' . $settings['height'] . ';width:' . $settings['width'] . ';" ';
			
			$html .= '></iframe>';
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('src'), $settings['src'], 'URL' );

		$html .= $this->form_fields->text_field( $this->get_input_name('height'), $settings['height'], 'height' );

		$html .= $this->form_fields->text_field( $this->get_input_name('width'), $settings['width'], 'width' );
		
		return $html;
		
	} // end form
	
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['src'] = ( ! empty( $settings['src'] ) ) ? sanitize_text_field( $settings['src'] ):'';
		
		$clean['height'] = ( ! empty( $settings['height'] ) ) ? sanitize_text_field( $settings['height'] ):'600px';
		
		$clean['width'] = ( ! empty( $settings['width'] ) ) ? sanitize_text_field( $settings['width'] ):'100%';
		
		return $clean;
		
	} // end clean
	
	
	protected function editor_default_html( $settings , $content ){
		
		$html = '<h2>Iframe</h2>';
		
		return $html;
		
	} // end editor_default_html
	
	
}