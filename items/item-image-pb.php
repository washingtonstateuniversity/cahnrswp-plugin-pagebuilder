<?php
class Item_Image_PB extends Item_PB {

	public $slug = 'image';

	public $name = 'Image';

	public $form_size = 'small';

	public function item( $settings, $content ) {

		$html .= '<div class="cpb-image">';

			if ( ! empty( $settings['url'] ) ) $html .= '<a href="' . $settings['url'] . '">';

			$html .= ( ! empty( $settings['img_src'] ) ) ? '<img src="' . $settings['img_src'] . '" style="width: 100%;display:block" />' : '';

			if ( ! empty( $settings['url'] ) ) $html .= '</a>';

		$html.= '</div>';

		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$html = ( ! empty( $settings['img_src'] ) ) ? '<img src="' . $settings['img_src'] . '" />' : '<div class="cpb-image-item-empty">Set Image</div>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$html = Forms_PB::insert_media( $this->get_name_field(), $settings );

		$html .= Forms_PB::text_field( $this->get_name_field('url'), $settings['url'], 'Link' );

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		$clean['img_src'] = ( ! empty( $s['img_src'] ) ) ? sanitize_text_field( $s['img_src'] ) : '';

		$clean['img_id'] = ( ! empty( $s['img_id'] ) ) ? sanitize_text_field( $s['img_id'] ) : '';

		$clean['url'] = ( ! empty( $s['url'] ) ) ? sanitize_text_field( $s['url'] ) : '';

		return $clean;

	} // end clean

}