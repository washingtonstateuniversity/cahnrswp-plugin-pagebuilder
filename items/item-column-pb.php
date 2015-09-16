<?php
class Item_Column_PB extends Item_PB {
	
	public $slug = 'column';
	
	public $name = 'Column';
	
	public $allowed_children = 'all';
	
	public $default_child = 'textblock';

	
	public function item( $settings , $content ){
		
		global $cpb_column_i;
		
		if ( ! isset( $cpb_column_i ) ) $cpb_column_i = 1;
		
		$html = '<div class="' . $settings['csshook'] . ' cpb-column ' . $this->i_array[ $cpb_column_i - 1 ]  . '"><div class="cpb-inner-wrap">' . $content . '</div></div>';
		
		$cpb_column_i++;
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;
		
		$html .= '<div class="inner-wrapper">';
	
			$html .= '<header class="cpb-item-' . $this->slug . '-header">';
			
				$html .= '<h4>' . $title . '</h4>';
				
				$html .= '<a href="#" class="cpb-edit-item" data-id="' . $this->id . '"></a>';
			
			$html .= '</header>';
			
			$html .= '<div class="cpb-item-set cpb-column-item-set">';
			
				$html .= $editor_content;
			
			$html .= '</div>';
			
			$html .= Forms_PB::hidden_field( $this->get_name_field( 'children' , false  ) , implode( ',' , $this->get_child_ids() ) , 'cpb-input-items-set' );
			
			$html .= '<footer>';
				
				$html .= '<a href="#" class="add-part-action cpb-button cpb-button-small" data-part="item">+ Add Item</a>';
			
			$html .= '</footer>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$html = Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' );
		
		return $html;
		
	} // end form
	
	
	public function clean( $s ){
		
		$clean = array();
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';
		
		return $clean;
		
	} // end clean_settings
	
	/*
	 * Items have a standardized wrapper around them. The editor in this case only
	 * returns the inner content of the item.
	*/
	/*public function wrap_item( $content , $settings , $is_item = true ){
		
		$html = '<div class="cpb-' . $this->slug . ' ' . $this->index_class . '" data-id="' .  $this->id . '">';
		
		$html .= Forms_PB::hidden_field( $this->get_name_field( 'type' , false ) , $this->slug );
				
		$html .= $content;
				
		$html .= '</div>';
		
		return $html;
		
	} // end wrap_item*/
	
}