<?php
class Display_PB {
	
	
	public static function get_display( $items , $settings ){
		
		$html = '';
		
		$display = ( ! empty( $settings['display_type'] ) ) ? $settings['display_type'] : 'promo';
		
		switch ( $display ){
			case 'gallery':
				$html .= Display_PB::get_gallery( $items , $settings );
				break;
				
			default:
				$html .= Display_PB::get_promos( $items , $settings );
				break;

		} // end switch

		return $html;

	} // end get_display

	
	public static function get_gallery( $items , $settings ){
		
		$style = '<style type="text/css" scoped>
				.cpb-gallery-set {margin: 0 -0.5rem;}
				.cpb-gallery-set .cpb-gallery-item {display:inline-block;vertical-align:top;}
				.cpb-gallery-set.quarters .cpb-gallery-item {width:25%}
				.cpb-gallery-item article { margin: 0 0.5rem; padding-bottom: 1rem; }
				.cpb-gallery-item article > img, .cpb-gallery-item article > a > img {width:100%;display:block;background-repeat:no-repeat;background-size:cover;background-position:center center;}
				</style>';
		
		$columns = ( ! empty( $settings['columns'] ) ) ? $settings['columns'] : 'quarters'; 
		
		$html = '<div class="cpb-gallery-set cpb-item ' . $columns . '">';
		
		$html .= $style;
		
		foreach( $items as $item ){
			
			$html .= Display_PB::get_summary_view( $item , 'cpb-gallery' ); 
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	
	public static function get_promo( $items, $settings ){
		
		$html = '<div class="cpb-promo-set cpb-item">';
		
		foreach( $items as $item ){
			
			$image_class = ( ! empty( $item['img'] ) ) ? 'has-image ':'';
			
			$html .= Display_PB::get_summary_view( $item , 'cpb-promo' , $image_class ); 
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	public static function get_summary_view( $item , $type , $class = '' ){
		
		if ( ! empty( $item['link'] ) ){
			
			$ls = '<a href="' . $item['link'] . '">';
			
			$le = '</a>';
			
		} else {
			
			$ls = '';
			
			$le = '';
			
		} // end if
		
		$html .= '<div class="cpb-summary-item ' . $type . '-item ' . $class . '">';
		
			$html .= '<article>';
				
				// Add Image
				if ( ! empty( $item['image'] ) ){
		
						$html .= $ls . '<img src="' . CWPPBURL . 'images/3x4spacer.png" style="background-image:url(' . $item['image'] . ');" />' . $le;
					
				} // end if
				
				if ( ! empty( $item['title'] ) || ! empty( $item['excerpt'] ) || ! empty( $item['link'] ) ){
				
					$html .= '<div class="cpb-caption ' . $class . '">';
						
						$html .= '<h5>' . $ls . $item['title'] . $le. '</h5>';
						
						$html .= $item['excerpt'];
					
					$html .= '</div>';
				
				} // end if
			
			$html .= '</article>';
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	
	
}