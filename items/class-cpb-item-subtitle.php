<?php

class CPB_Item_Subtitle extends CPB_Item {
	
	protected $name = 'Subtitle';
	
	protected $slug = 'subtitle';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$ls = ( ! empty( $settings['link'] ) ) ? '<a href="' . $settings['link'] . '" >' : '';
		
		$le = ( ! empty( $settings['link'] ) ) ? '</a>' : '';
		
		$classes = array('cpb-subtitle');
		
		if ( ! empty( $settings['style'] ) ){
			
			$classes[] = $settings['style'];
			
		} // End if
		
		if ( ! empty( $settings['csshook'] ) ){
			
			$classes[] = $settings['csshook'];
			
		} // End if
		
		if ( ! empty( $settings['textcolor'] ) ){
			
			$classes[] = $settings['textcolor'] . '-text';
			
		} // End if
		
		$html = '<' . $settings['tag'] . ' class="' . implode( ' ', $classes ) . '">' . $ls . $settings['title'] . $le . '</' . $settings['tag'] . '>';
		
		if ( ! empty( $settings['anchor'] ) ){
			
			$html = '<a name="' . $settings['anchor'] . '"></a>' . $html;
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$styles = array(
			'' 									=> 'None',
			'underline-heading' 				=> 'Underlined Heading',
			'underline-heading small-heading' 	=> 'Underlined Heading (small font)',
		);
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $this->form_fields->get_header_tags() , 'Tag Type' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('link') , $settings['link'] , 'Link' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('style'), $settings['style'], $styles, 'Style' );
		
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
		
		$clean['style'] = ( ! empty( $settings['style'] ) )? sanitize_text_field( $settings['style'] ) : '';
		
		return $clean;
	}
	
	
}