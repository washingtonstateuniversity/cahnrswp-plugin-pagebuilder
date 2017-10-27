<?php

class CPB_Item_Action extends CPB_Item {
	
	protected $slug = 'action';

	protected $name = 'Action Button';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		$class = array('cpb-action-button' , 'cpb-action-button-item');
		
		if ( ! empty( $settings['csshook'] ) ) $class[] = $settings['csshook'];
		
		if ( ! empty( $settings['label'] ) ) {

			$html = '<a href="' . $settings['link'] . '" class="'. implode( ' ' , $class ) . '">' . $settings['label'] . '</a>';

		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html .= $this->form_fields->text_field( $this->get_input_name('label'), $settings['label'], 'Label' );

		$html .= $this->form_fields->text_field( $this->get_input_name('link'), $settings['link'], 'Link' );

		$html .= $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );
		
		return $html;
		
	} // end form
	
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['label'] = ( ! empty( $settings['label'] ) ) ? sanitize_text_field( $settings['label'] ):'';
		
		$clean['link'] = ( ! empty( $settings['link'] ) ) ? sanitize_text_field( $settings['link'] ):'#';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ):'';
		
		return $clean;
		
	} // end clean
	
	protected function css() {
		/*
		.cpb-action-button a {
			display: block;
			background: #981e32;
			color: #fff;
			padding: 1rem;
		}*/
		
		$style = '.cpb-action-button {display:block;background:#981e32;color: #fff;padding: 1rem;margin-bottom: 1.5rem;}';
		
		return $style;
		
	}
	
	protected function editor_default_html( $settings , $content ){
		
		$html = '<a class="cpb-action-button" href="#" class="cpb-action-button-item">Action Button</a>';
		
		return $html;
		
	} // end editor_default_html
	
	
}