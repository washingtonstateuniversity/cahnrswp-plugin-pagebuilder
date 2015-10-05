<?php
class Display_PB {
	
	public $settings;
	
	
	public function __construct( $settings ){
		
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
				$html .= $this->get_promo( $items );
				break;

		} // end switch

		return $html;

	} // end get_display

	
	public function get_gallery( $items ){
		
		$style = '<style type="text/css" scoped>
				.cpb-gallery-set {margin: 0 -0.5rem;}
				.cpb-gallery-set .cpb-gallery-item {display:inline-block;vertical-align:top;}
				.cpb-gallery-set.quarters .cpb-gallery-item {width:25%}
				.cpb-gallery-item article { margin: 0 0.5rem; padding-bottom: 1rem; }
				.cpb-gallery-item article > img, .cpb-gallery-item article > a > img {width:100%;display:block;background-repeat:no-repeat;background-size:cover;background-position:center center;}
				</style>';
		
		$columns = ( ! empty( $this->settings['columns'] ) ) ? $this->settings['columns'] : 'quarters'; 
		
		$html = '<div class="cpb-gallery-set cpb-item ' . $columns . '">';
		
		$html .= $style;
		
		foreach( $items as $item ){
			
			$html .= $this->get_summary_view( $item , 'cpb-gallery' ); 
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	
	public function get_promo( $items ){
		
		$style = '<style type="text/css" scoped >
				.cpb-promo-item article > img, .cpb-promo-item article > a > img {width:160px;display:block;background-repeat:no-repeat;background-size:cover;background-position:center center;float:left;}
				.cpb-promo-item article.has-image > .cpb-caption{ margin-left: 175px;}
				.cpb-promo-item article:after {content:"";display: block;clear:both;}
				.cpb-promo-item article{padding-bottom: 1.5rem;}
				.cpb-promo-item h2,.cpb-promo-item h3,.cpb-promo-item h4,.cpb-promo-item h5 {padding-top: 0;}
				</style>';
		
		$html = '<div class="cpb-promo-set cpb-item">';
		
		$html .= $style;
		
		foreach( $items as $item ){
			
			$html .= $this->get_summary_view( $item , 'cpb-promo' ); 
			
		} // end foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_promos
	
	
	public function get_summary_view( $item , $type , $class = '' ){
		
		if ( ! empty( $item['link'] ) ){
			
			$ls = '<a href="' . $item['link'] . '">';
			
			$le = '</a>';
			
		} else {
			
			$ls = '';
			
			$le = '';
			
		} // end if
		
		$html .= '<div class="cpb-summary-item ' . $type . '-item ' . $class . '">';
		
			$image_class = ( ! empty( $item['image'] ) )? 'has-image':'';
		
			$html .= '<article class="' . $image_class . '">';
				
				// Add Image
				if ( ! empty( $item['image'] ) ){
		
						$html .= $ls . '<img src="' . CWPPBURL . 'images/3x4spacer.png" style="background-image:url(' . $item['image'] . ');" />' . $le;
					
				} // end if
				
				if ( ! empty( $item['title'] ) || ! empty( $item['excerpt'] ) || ! empty( $item['link'] ) ){
				
					$html .= '<div class="cpb-caption ' . $class . '">';
					
						$tag = ( ! empty( $this->settings['headline_tag'] ) ) ? $this->settings['headline_tag'] : 'h5';
						
						$html .= '<' . $tag . '>' . $ls . $item['title'] . $le. '</' . $tag . '>';
						
						$html .= $item['excerpt'];
					
					$html .= '</div>';
				
				} // end if
			
			$html .= '</article>';
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	
	
}