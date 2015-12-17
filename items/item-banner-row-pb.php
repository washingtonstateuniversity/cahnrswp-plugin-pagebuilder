<?php
class Item_Banner_Row_PB extends Item_PB {

	public $slug = 'bannerrow';

	public $name = 'Banner Row';
	
	public $is_layout = true;


	public function item( $settings, $content ) {

		$html .= '<div class="cpb-banner-image" style="height:500px">';
		
			$html .= '<div class="cpb-bg unbound recto verso" style="height:500px;background-image: url(' . $settings['img_src'] . ');" >';

			$html .= '</div>';
			
			if ( ! empty( $settings['title'] ) ) {
				
				$html .= '<div class="cpb-banner-title">' . $settings['title'] . '</div>';
				
			} // end if
			
			if ( ! empty( $settings['subtitle'] ) ) {
				
				$html .= '<div class="cpb-banner-subtitle">' . $settings['subtitle'] . '</div>';
				
			} // end if

		$html.= '</div>';

		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;

		$html .= '<header class="cpb-item-' . $this->slug . '-header">';
			
			$html .= '<nav>';

				$html .= '<div class="title">' . $title . '</div>';

				$html .= '<a href="#" class="remove-item-action"></a>';
			
			$html .= '</nav>';

		$html .= '</header>';

		return $html;

	} // end editor

	public function form( $settings ) {
		
		$banner = Forms_PB::insert_media( $this->get_name_field(), $settings );

		$banner .= Forms_PB::text_field( $this->get_name_field('url'), $settings['url'], 'Link' );
		
		$banner .= '<hr/>';
		
		$banner .= Forms_PB::text_field( $this->get_name_field('title'), $settings['title'], 'Title' );
		
		$banner .= Forms_PB::text_field( $this->get_name_field('subtitle'), $settings['subtitle'], 'subtitle' );

		return $banner;

	} // end form

	public function clean( $s ) {

		$clean = array();
		
		$clean['img_src'] = ( ! empty( $s['img_src'] ) ) ? sanitize_text_field( $s['img_src'] ) : '';

		$clean['img_id'] = ( ! empty( $s['img_id'] ) ) ? sanitize_text_field( $s['img_id'] ) : '';

		$clean['url'] = ( ! empty( $s['url'] ) ) ? sanitize_text_field( $s['url'] ) : '';
		
		if ( ! empty( $s['title'] ) ) {
			
			$clean['title'] = sanitize_text_field( $s['title'] );
			
		} // end if
		
		if ( ! empty( $s['subtitle'] ) ) {
			
			$clean['subtitle'] = sanitize_text_field( $s['subtitle'] );
			
		} // end if

		return $clean;

	} // end clean_settings

}