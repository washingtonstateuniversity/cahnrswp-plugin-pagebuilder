<?php

class CPB_Item_Textblock extends CPB_Item {
	
	protected $name = 'Text/HTML';
	
	protected $slug = 'textblock';
	
	protected $uses_wp_editor = true;
	
	public function item( $settings , $content ){
		
		$content = do_shortcode( $content );
		
		if ( ! empty( $settings['textcolor'] ) ) $content = '<span class="' . $settings['textcolor'] . '-text">' . $content . '</span>';
		
		return apply_filters( 'the_content' , $content );
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		ob_start();
		
		wp_editor( $content , '_cpb_content_' . $this->get_id() );
		
		$html = ob_get_clean();
		
		$adv = $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	public function editor_default_html(){
		
		return 'Add Text Here';
		
	} // end editor_default_html
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) ) ? sanitize_text_field( $settings['textcolor'] ) : '';
		
		return $clean;
		
	}
	
	
}