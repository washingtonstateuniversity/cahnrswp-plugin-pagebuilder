<?php

require_once 'form-pb.php';

class Form_Add_Item_PB extends Form_PB {
	
	private $items;
	
	private $exc = array('section','row','column','pagebreak','widget');
	
	public function __construct( $items ){
		
		$this->items = $items; 
		
	} // end __construct
	
	public function get_form(){
		
		$form_args = array(
			'action' => 'close-form-action ajax-add-item-action',
			'label'  => 'Add Item',
			'width'  => 'medium',
			'id'     => 'cpb_add_item',
		);
		
		$html = '<div class="cpb-form-slider cycle-slideshow" 
			data-cycle-fx="scrollHorz" 
			data-cycle-timeout="0" 
			data-cycle-slides="> div"
			data-cycle-pager="#cpb-add-items-pager"
			data-cycle-prev="#cpb-add-items-prev"
        	data-cycle-next="#cpb-add-items-next">';
		
			$html .= '<div class="cpb-form-slide active">';
		
				$i = 0;
			
				foreach ( $this->items as $item ){
					
					if ( in_array( $item->slug , $this->exc ) ) continue;
					
					$html .= $this->get_item_icon( $item );
					
					$i++;
			
					if ( $i == 4 ){
					
						$html .= '</div><div class="cpb-form-slide">';
						
						$i = 0;
					
					} // end if
			
				} // end foreach
			
			$html .= '</div>';
		
		$html .= '</div>';
		
		$html .= '<nav class="cpb-form-add-items-nav">';
		
			$html .= '<a id="cpb-add-items-prev" href="#">Prev</a>';
			
				$html .= '<div id="cpb-add-items-pager"></div>';
		
			$html .= '<a id="cpb-add-items-next" href="#">Next</a>';
		
		$html .= '</nav>';
		
		return $this->wrap_form( array('Items' => $html ) , $form_args );
		
	} // end get_form
	
	
	public function get_item_icon( $item ){
		
		if ( ! isset( $item->icon ) ) $item->icon = CWPPBURL . 'images/item-place-holder.jpg';
		
		$html = '<ul class="cpb-add-item-wrapper cpb-toggle-select cpb-radio" data-type="' . $item->slug . '">';
		
			$html .= '<li class="cpb-add-item-icon"><img src="'. $item->icon . '" /><div class="cpb-add-item-select-box"></div></li>';
			
			$html .= '<li class="cpb-add-item-title">' . $item->name . '</li>';
			
			$html .= '<li class="cpb-add-item-desc">' . $item->desc . '</li>';
	
		$html .= '</ul>';
		
		return $html;
		
	} // end get_item_icon
	
}