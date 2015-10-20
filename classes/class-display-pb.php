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
			
			case 'accordion':
				$html .= $this->get_accordions( $items );
				break;
				
			default:
				$html .= $this->get_promo( $items );
				break;

		} // end switch

		return $html;

	} // end get_display
	
	public function get_accordions( $items ){
		
		$id = 'cpb-accordions-' . rand( 0 , 10000000 );
		
		$html = '<div id="' . $id . '" class="cahnrs-accordion-set ' . $this->settings['csshook'] . '">';
		
		foreach( $items as $item ){
			
			$style = ( ! empty( $this->settings['bg_image'] ) ) ? 'background-image:url(' . $item['image'] . ');' : '';
		
			$html .= '<dl class="cahnrs-accordion" style="' . $style . '">';
	
				$html .= '<dt><h2>' . $item['title'] . '</h2></dt>';
								
				$html .= '<dd style="display: none;">'; 
				
				$html .= '<p>' . $item['content'] . '</p>';
				
				$html .= '<p class="more-button center"><a title="Visit the 4-H website" href="http://4h.wsu.edu/" target="_blank">Visit the website</a></p>';
					
				$html .= '</dd>';
				
			$html .= '</dl>';
		
		} // end foreach
		
		/*
		if (typeof jQuery != "undefined") {
			jQuery("#' . $id . '").on("click",".cahnrs-accordion dt",function(){
				var p = jQuery( this ).closest(".cahnrs-accordion");
				p.find("dd").stop().slideToggle("fast");
				p.siblings(".cahnrs-accordion").find("dd").stop().slideUp("fast");
			}); 
		}
		*/
		
		$script = '<script>/*"undefined"!=typeof jQuery&&jQuery("#' . $id . '").on("click",".cahnrs-accordion dt",function(){var d=jQuery(this).closest(".cahnrs-accordion");
					d.find("dd").stop().slideToggle("fast"),d.siblings(".cahnrs-accordion").find("dd").stop().slideUp("fast")});*/</script>';
		
		$html .= '</div>';
		
		return $html;
		
	} // endget_accordions

	
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
				.cpb-promo-item .cpb-caption > strong {display:block;}
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
					
						$tag = ( ! empty( $this->settings['headline_tag'] ) ) ? $this->settings['headline_tag'] : 'strong';
						
						$html .= '<' . $tag . '>' . $ls . $item['title'] . $le. '</' . $tag . '>';
						
						$html .= $item['excerpt'];
					
					$html .= '</div>';
				
				} // end if
			
			$html .= '</article>';
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	
	
}