<?php

class CPB_Item_FAQ extends CPB_Item {
	
	protected $name = 'FAQ';
	
	protected $slug = 'faq';
	
	protected $uses_wp_editor = true;
	
	protected function item( $settings , $content ){
		
		$tag_start = ( ! empty( $settings['tag'] ) )? '<' . $settings['tag'] . '>' : '';
		
		$tag_end = ( ! empty( $settings['tag'] ) )? '</' . $settings['tag'] . '>' : '';
		
		$html = '<dl class="cpb-faq">';
		
			$html .= '<dt>' . $tag_start . $settings['title'] . $tag_end . '</dt>';
			
  			$html .= '<dd>' . $content . '</dd>';
		
		$html .= '</dl>';
		
		return $html;
		
	}// end item
	
	
	protected function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('title'), $settings['title'], 'Title' ); 
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $this->form_fields->get_header_tags( true ), 'Tag Type' ); 
		
		ob_start();
		
		wp_editor( $content , '_cpb_content_' . $this->get_id() );
		
		$html .= ob_get_clean();
		
		$adv = $this->form_fields->select_field( 
			$this->get_input_name('textcolor'), 
			$settings['textcolor'], 
			$this->form_fields->get_wsu_colors(), 
			'Text Color' 
			);
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	protected function editor_default_html(){
		
		return 'Add Text Here';
		
	} // end editor_default_html
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $settings['title'] ) ) ? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : '';
		
		return $clean;
		
	}
	
	protected function css() {
		
		$style = '.cpb-faq { background: #fff;}';
		
		$style .= '.cpb-faq dt { display: block; margin:0;padding: 1rem; font-size: 1.1rem;color:#981e32;cursor:pointer;border: 1px solid #981e32; }';
		
		$style .= '.cpb-faq dd { margin: 0; padding: 1rem 1.5rem; border-left: 1px solid #ddd;border-right: 1px solid #ddd;border-bottom: 1px solid #ddd; display: none; }';
		
		return $style;
		
	}
	
	
}