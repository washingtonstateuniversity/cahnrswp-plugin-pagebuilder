<?php
class Item_Pagebreak_PB extends Item_PB {

	public $slug = 'pagebreak';

	public $name = 'Page Break';
	
	public $is_layout = true;


	public function item( $settings, $content ) {

		$html = '<!-- pagebreak -->';

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

		$html = '';

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		return $clean;

	} // end clean_settings

}