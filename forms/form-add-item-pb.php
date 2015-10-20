<?php
class Form_Add_Item_PB {
	
	private $items;
	
	private $exc = array('section','row','column');
	
	public function __construct( $items ){
		
		$this->items = $items; 
		
	} // end __construct
	
	public function get_form(){
		
		$html = '<div class="cpb-form-slider">';
		
			$html .= '<div class="cpb-form-slide">';
		
			$i = 0;
		
			foreach ( $this->items as $item ){
			
				$html .= '<div class="cpb-add-item-wrapper">';
			
					$html .= $item->name;
			
				$html .= '</div>';
				
				$i++;
			
		
				if ( $i = 6 ){
				
					$html .= '</div><div class="cpb-form-slide">';
					
					$i = 0;
				
				} // end if
		
			} // end for
			
			$html .= '</div>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_form
	
}