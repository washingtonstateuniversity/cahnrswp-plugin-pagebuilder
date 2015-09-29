<?php
class Display_PB {
	
	public $settings = array();
	
	public function __construct( $settings = array() ){
		
		if ( ! is_array( $settings ) ) $settings = array();
		
		$this->settings = $settings;
		
	} // end __construct
	
	public function get_display( $items ){
		
		$html = '';
		
		$display = ( ! empty( $this->settings['display_type'] ) ) ? $this->settings['display_type'] : 'promo';
		
		switch ( $display ){
			case 'gallery':
				$html .= $this->get_gallery( $items );
				break;
				
			default:
				$html .= $this->get_promos( $items );
				break;

		} // end switch

		return $html;

	} // end get_display

	
	public function get_gallery( $items ){
		
		$layout_classes = array('not-set','one-column','two-column','three-column','four-column','five-column' );
		
		$display = ( ! empty( $this->settings['display_columns'] ) ) ? $this->settings['display_columns'] : 4;
		
		$html = '<div class="cpb-gallery-set cpb-item ' . $layout_classes[ $display ] . '">';
		
		$html .= '<style type="text/css" scoped>
			.cpb-gallery-set{margin: 0 -1rem;} 
			.cpb-gallery-item {margin-bottom: 1rem}
			.cpb-gallery-item .cpb-image {background-color: #ddd; background-repeat: no-repeat;background-size:cover;background-position: center center}
			.cpb-gallery-item .cpb-image img {display:block;width:100%;height:auto;}
			.cpb-gallery-item .cpb-image,.cpb-gallery-item .cpb-caption {margin: 0 1rem;}
			.cpb-gallery-set.four-column .cpb-gallery-item {display:inline-block;width: 25%;vertical-align:top}
			.cpb-gallery-set:after{content:"";display: block;clear:both;margin: 0 -0.5rem;}
			</style>';
		
		foreach( $items as $item ){
			
			$image_class = ( ! empty( $item['img'] ) ) ? 'has-image ':'';
			
			if ( ! empty( $item['link'] ) ){
				
				$l_start = '<a href="' . $item['link'] . '" >';
				
				$l_end = '</a>';
				
			} else {
				
				$l_start = '';
				
				$l_end = '';
				
			} // end if

			$html .= '<article class="' . $image_class . 'cpb-gallery-item">';
				
			$html .= $this->get_image( $item , $l_start , $l_end );
			
			$html .= $this->get_caption( $item , $l_start , $l_end );
			
			
			$html .= '</article>';
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	
	public function get_promos( $items ){
		
		$html = '<div class="cpb-promo-set cpb-item">';
		
		foreach( $items as $item ){
			
			$image_class = ( ! empty( $item['img'] ) ) ? 'has-image ':'';
			
			if ( ! empty( $item['link'] ) ){
				
				$l_start = '<a href="' . $item['link'] . '" >';
				
				$l_end = '</a>';
				
			} else {
				
				$l_start = '';
				
				$l_end = '';
				
			} // end if

			$html .= '<article class="' . $image_class . 'cpb-promo">';
			
			if ( ! empty( $item['img'] ) ){
				
				$html .= $this->get_image( $item , $l_start , $l_end );
			
			} // end if
			
			$html .= $this->get_caption( $item , $l_start , $l_end );	
			
			$html .= '</article>';
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	public function get_image( $item , $l_start = '' , $l_end = '' , $class = '' ){
			
		$html = '<div class="cpb-image" style="background-image:url(' . $item['img'] . ');">';
		
			$html .= $l_start . '<img src="' . CWPPBURL . 'images/3x4spacer.png" />' . $l_end;
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_image
	
	
	public function get_caption( $item , $l_start = '' , $l_end = '' , $class = '' ){
		
		$html = '<div class="cpb-caption ' . $class . '">';
					
			$html .= '<h3>' . $l_start . $item['title'] . $l_end . '</h3>';
			
			$html .= '<div class="cpb-excerpt">' . $item['excerpt'] . '</div>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_caption
	
}