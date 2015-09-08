<?php
class Item_Iframe_PB extends Item_PB {
	
	public $slug = 'cwpiframe';
	
	public $name = 'IFrame';
	
	public $desc = 'Add IFrame to page';
	
	public $form_size = 'small';
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['src'] ) ){
		
			$html .= '<iframe src="' . $settings['src'] . '" frameborder="0" style="width:' . $settings['width'] . '; height:' . $settings['height'] .'"></iframe>';
		
		} // end if
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		if ( ! empty( $settings['src'] ) ){
			
			$content = '<h3>Iframe SRC: ' . $settings['src'] . '</h3>';
			
		} else {
			
			$content = 'Select an IFrame';
			
		}// end if
		
		$html = '<h3>' . $content . '</h3>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){

		
		$html = Forms_PB::text_field( $this->get_name_field('src') , $settings['src'] , 'IFrame url' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('width') , $settings['width'] , 'Width' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('height') , $settings['height'] , 'Height' );
		
		return $html; 
		
	} // end form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		$clean['src'] = ( ! empty( $s['src'] ) )? sanitize_text_field( $s['src'] ) : '';
		
		$clean['width'] = ( ! empty( $s['width'] ) )? sanitize_text_field( $s['width'] ) : '100%';
		
		$clean['height'] = ( ! empty( $s['height'] ) )? sanitize_text_field( $s['height'] ) : '600px';
		
		return $clean;
		
	} // end clean
	
}