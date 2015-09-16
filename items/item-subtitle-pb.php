<?php
class Item_Subtitle_PB extends Item_PB {
	
	public $slug = 'subtitle';
	
	public $name = 'Subtitle';
	
	public $desc = 'Add a new headding';
	
	public $form_size = 'small';
	
	public function item( $settings , $content ){
		
		$html = '<' . $settings['tag'] . ' class="' . $settings['csshook'] . '">' . $settings['title'] . '</' . $settings['tag'] . '>';
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';
		
		$html = '<h2>' . $title . '</h2>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$tags = array(
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
		);
		
		$html = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$html .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , $tags , 'Tag Type' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		return $html; 
		
	} // end form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		$clean['tag'] = ( ! empty( $s['tag'] ) )? sanitize_text_field( $s['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $s['title'] ) )? sanitize_text_field( $s['title'] ) : '';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';
		
		return $clean;
		
	} // end clean
	
}