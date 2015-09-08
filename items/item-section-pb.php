<?php
class Item_Section_PB extends Item_PB {
	
	public $slug = 'section';
	
	public $name = 'Page Section';
	
	public $allowed_children = 'row';
	
	public $default_child = 'row';
	
	
	
	public function item( $settings , $content ){
		
		$html = '<div class="section">' . $content . '</div>';
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;
		
		$html .= '<header class="cpb-item-' . $this->slug . '-header">';
		
			$html .= '<h4>' . $title . '</h4>';
		
		$html .= '</header>';
		
		$html .= '<div class="cpb-item-set cpb-item-' . $this->slug . '-set">';
		
			$html .= $editor_content;
		
		$html .= '</div>';
		
		$html .= Forms_PB::hidden_field( $this->get_name_field( 'children' , false ) , implode( ',' , $this->get_child_ids() ) , 'cpb-input-items-set' );
		
		//$html .= '<input class="cpb-input-items-set" type="text" name="_cwpb[' . $this->id . '][children]" value="' . implode( ',' , $this->get_child_ids() ) . '" />';
		
		$html .= '<footer>';
		
			$html .= '<a href="#" class="add-part-action cpb-button" data-part="row">+ Add Row</a>';
			
			$html .= '<a href="#" class="add-part-action cpb-darkgrey-button" data-part="section">+ Add Section</a>';
		
		$html .= '</footer>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$html = $this->text_field( $this->get_name_field( 'title' ) , $settings['title'] , 'Title' , 'cpb-form-100' );
		
		return $html;
		
	} // end form
	
	
	public function clean( $s ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $s['title'] ) ) ? sanitize_text_field( $s['title'] ) : 'Page Section';
		
		return $clean;
		
	} // end clean_settings
	
}