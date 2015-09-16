<?php
class Item_Row_PB extends Item_PB {
	
	public $slug = 'row';
	
	public $name = 'Page Row';
	
	public $allowed_children = 'column';
	
	public $default_child = 'column';
	
	public function item( $settings , $content ){
		
		global $cpb_column_i;
		
		$cpb_column_i = 1;
		
		$html = '<div class="' . $settings['csshook'] . ' cpb-row cpb-' . $settings['layout'] . '">' . $content . '<div class="cpb-clearfix"></div></div>';
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;
		
		$html .= '<header class="cpb-item-' . $this->slug . '-header">';
		
			$html .= '<h4>' . $title . '</h4>';
			
			$html .= '<a href="#" class="cpb-edit-item" data-id="' . $this->id . '"></a>';
			
			$html .= '<a href="#" class="remove-item-action"></a>';
		
		$html .= '</header>';
		
		$html .= '<div class="cpb-item-set cpb-' . $settings['layout'] . '  cpb-item-' . $this->slug . '-set">';
		
			$html .= $editor_content;
		
		$html .= '</div>';
		
		$html .= Forms_PB::hidden_field( $this->get_name_field( 'children' , false  ) , implode( ',' , $this->get_child_ids() ) , 'cpb-input-items-set' );
		
		$html .= '<footer>';
		
		$html .= '</footer>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$html = Forms_PB::hidden_field( $this->get_name_field( 'layout' ) , $settings['layout'] );
		
		$html .= Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' );
		
		return $html;
		
	} // end form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		$clean['layout'] = ( ! empty( $s['layout'] ) ) ? sanitize_text_field( $s['layout'] ) : 'single';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';
		
		return $clean;
		
	} // end clean_settings
	
}