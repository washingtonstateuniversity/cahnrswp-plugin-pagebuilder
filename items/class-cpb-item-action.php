<?php

class CPB_Item_Action extends CPB_Item {
	
	protected $slug = 'action';

	protected $name = 'Action Button';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		$class = array('cpb-action-button' , 'cpb-action-button-item');
		
		if ( ! empty( $settings['style'] ) ){
			
			$class[] = $settings['style'];
			
		} // End if
		
		if ( ! empty( $settings['caption'] ) ) $class[] = 'has-caption';
		
		if ( ! empty( $settings['csshook'] ) ) $class[] = $settings['csshook'];
		
		if ( ! empty( $settings['label'] ) ) {

			$html = '<a href="' . $settings['link'] . '" class="'. implode( ' ' , $class ) . '">';
			
			$html .= '<span class="link-title">' . $settings['label'] . '</span>';
			
			if ( ! empty( $settings['caption'] ) ){
				
				$html .= '<span class="link-caption">' . $settings['caption'] . '</span>';
				
			} // End if
			
			$html .= '</a>';

		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$styles = array(
			''					=> 'None',
			'in-page-action'  	=> 'In Page Button',
		);
		
		$html .= $this->form_fields->text_field( $this->get_input_name('label'), $settings['label'], 'Label' );

		$html .= $this->form_fields->text_field( $this->get_input_name('link'), $settings['link'], 'Link' );

		$html .= $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('style'), $settings['style'], $styles, 'Style' );
		
		$adv .= $this->form_fields->textarea_field( $this->get_input_name('caption'), $settings['caption'], 'Link Description' );
		
		return array( 'Basic' => $html, 'Advanced' => $adv );
		
	} // end form
	
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['label'] = ( ! empty( $settings['label'] ) ) ? sanitize_text_field( $settings['label'] ):'';
		
		$clean['link'] = ( ! empty( $settings['link'] ) ) ? sanitize_text_field( $settings['link'] ):'#';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ):'';
		
		$clean['style'] = ( ! empty( $settings['style'] ) )? sanitize_text_field( $settings['style'] ) : '';
		
		$clean['caption'] = ( ! empty( $settings['caption'] ) )? sanitize_text_field( $settings['caption'] ) : '';
		
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