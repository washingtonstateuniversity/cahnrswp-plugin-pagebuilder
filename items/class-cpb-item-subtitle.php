<?php

class CPB_Item_Subtitle extends CPB_Item {
	
	protected $name = 'Subtitle';
	
	protected $slug = 'subtitle';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$ls = ( ! empty( $settings['link'] ) ) ? '<a href="' . $settings['link'] . '" >' : '';
		
		$le = ( ! empty( $settings['link'] ) ) ? '</a>' : '';
		
		$html = '<' . $settings['tag'] . ' class="' . $settings['csshook'] . ' ' . $settings['textcolor'] . '-text">' . $ls . $settings['title'] . $le . '</' . $settings['tag'] . '>';
		
		if ( ! empty( $settings['anchor'] ) ){
			
			$html = '<a name="' . $settings['anchor'] . '"></a>' . $html;
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $this->form_fields->get_header_tags() , 'Tag Type' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('link') , $settings['link'] , 'Link' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) )? sanitize_text_field( $settings['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) )? sanitize_text_field( $settings['textcolor'] ) : '';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) )? sanitize_text_field( $settings['csshook'] ) : '';
		
		$clean['anchor'] = ( ! empty( $settings['anchor'] ) )? sanitize_text_field( $settings['anchor'] ) : '';
		
		$clean['link'] = ( ! empty( $settings['link'] ) )? sanitize_text_field( $settings['link'] ) : '';
		
		return $clean;
	}
	
	
}