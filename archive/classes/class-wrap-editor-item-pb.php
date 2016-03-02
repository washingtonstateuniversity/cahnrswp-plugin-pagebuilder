<?php

class Wrap_Editor_Item_PB {
	
	private $item;
	
	public function __construct( $item ){
		
		$this->item = $item;
		
	} // end __construct
	
	public function wrap_item( $editor_content ){
		
		if ( property_exists ( $item , 'is_layout' ) && $this->is_layout ){
			
			
			
		} else {
			
			
			
		} // end if
		
	} // end wrap_item
	
	public function wrap_layout_item( $editor_content ){
		
		$html = '<input type="hidden" name="' . $this->item->get_name_field( 'type', false ) . '" value="' . $this->item->slug . '" />';
		
		$html .= $editor_content;
		
		return $this->wrap_outside( $html , 'cpb-item ');
		
	} // end wrap_layout_item
	
	public function wrap_column_item( $editor_content ){
	} // end wrap_column_item
	
	public function wrap_outside( $inner_html , $class){
		
		$html = '<div class="cpb-item ' . $class . 'cpb-' . $this->item->slug . ' ' . $this->item->i_array[ $this->item->i ] . '" data-id="' .  $this->item->id . '">';
		
		$html .= $inner_html;
		
		$html .='</div>';
		
		return $html;
		
	} // end wrap_shell
	
}