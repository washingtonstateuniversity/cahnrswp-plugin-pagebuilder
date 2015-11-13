<?php

require_once 'form-pb.php';
require_once CWPPBDIR . 'classes/class-inputs-pb.php';
require_once CWPPBDIR . 'classes/class-forms-pb.php';

class Form_Add_Item_PB extends Form_PB {
	
	private $items;
	
	private $exc = array('section','row','column','pagebreak','widget');
	
	public function __construct( $items ){
		
		$this->items = $items; 
		
	} // end __construct
	
	public function get_form(){
		
		/*$form_args = array(
			'action' => 'close-form-action ajax-add-item-action',
			'label'  => 'Add Item',
			'width'  => 'medium',
			'id'     => 'cpb_add_item',
		);*/
		
		$items_form = $this->get_items_form();
		
		$widgets_form = $this->get_widgets_form();
		
		return Forms_PB::tabbed_form( array('Items' => $items_form , 'Widgets' => $widgets_form ) , 'close-form-action ajax-add-item-action' , 'Add Item' );
		
	} // end get_form
	
	private function get_items_form(){
		
		$items = array();
		
		foreach ( $this->items as $key => $item ){
					
					if ( in_array( $item->slug , $this->exc ) ) continue;
					
					$items[ $key ] = $item;
					
		} // end foreach
		
		/*$html = '<div class="cpb-form-slider cycle-slideshow" 
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
					
					$html .= $this->get_item_card( $item );
					
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
		
		return $html;*/
		
		return $this->get_slideshow( $items );
		
	}
	
	private function get_slideshow( $items ){
		
		$id = 'sh-' . rand(0,1000000) . '-';
		
		$html = '<div class="cpb-form-slider cycle-slideshow" 
			data-cycle-fx="scrollHorz" 
			data-cycle-timeout="0" 
			data-cycle-slides="> div"
			data-cycle-pager="#' . $id . 'cpb-add-items-pager"
			data-cycle-prev="#' . $id . 'cpb-add-items-prev"
        	data-cycle-next="#' . $id . 'cpb-add-items-next">';
		
			$html .= '<div class="cpb-form-slide active">';
		
				$i = 0;
			
				foreach ( $items as $item ){
					
					$html .= $this->get_item_card( $item );
					
					$i++;
			
					if ( $i == 4 ){
					
						$html .= '</div><div class="cpb-form-slide">';
						
						$i = 0;
					
					} // end if
			
				} // end foreach
			
			$html .= '</div>';
		
		$html .= '</div>';
		
		$html .= '<nav class="cpb-form-add-items-nav">';
		
			$html .= '<a id="' . $id . 'cpb-add-items-prev" href="#">Prev</a>';
			
				$html .= '<div id="' . $id . 'cpb-add-items-pager"></div>';
		
			$html .= '<a id="' . $id . 'cpb-add-items-next" href="#">Next</a>';
		
		$html .= '</nav>';
		
		return $html;
		
	} // end get slideshow
	
	
	private function get_widgets_form(){
		
		global $wp_widget_factory;
		
		$widget_array = array();
		
		foreach( $wp_widget_factory->widgets as $class => $widget ){
			
			$obj = new stdClass();
			
			$obj->name = $widget->name;
			
			$obj->desc = $widget->widget_options['description'];
			
			$obj->slug = 'widget';
			
			$obj->widget_id = $widget->id_base;
			
			$obj->widget_class = $class;
			
			$widget_array[ $class ] = $obj;
			
		}// end foreach
		
		//var_dump( $widget_array );
		
		//var_dump( $wp_widget_factory->widgets );
		
		return $this->get_slideshow( $widget_array );;
	}
	
	
	public function get_item_card( $item ){
		
		if ( ! isset( $item->icon ) ) $item->icon = CWPPBURL . 'images/item-place-holder.jpg';
		
		$html = '<ul class="cpb-add-item-wrapper cpb-toggle-select cpb-radio" data-type="' . $item->slug . '">';
		
			$html .= '<li class="cpb-add-item-icon"><img src="'. $item->icon . '" /><div class="cpb-add-item-select-box"></div></li>';
			
			$html .= '<li class="cpb-add-item-title">' . $item->name . '</li>';
			
			$html .= '<li class="cpb-add-item-desc">' . $item->desc;
			
			$html .= '<input type="hidden" name="item_slug" value="' . $item->slug . '" />';
			
			$html .= '<input type="hidden" name="service" value="add_part" />';
			
			if ( isset( $item->widget_class ) ) $html .= '<input type="hidden" name="settings[widget_class]" value="' . $item->widget_class . '" />';
			
			$html .= '</li>';
	
		$html .= '</ul>';
		
		return $html;
		
	} // end get_item_icon
	
}