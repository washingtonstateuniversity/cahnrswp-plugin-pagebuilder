<?php
class Item_Action_PB extends Item_PB {

	public $slug = 'action';

	public $name = 'Action Button';

	public $desc = 'Adds Linked Button';

	public $form_size = 'small';

	public function item( $settings, $content ) {

		$html = '<div class="cpb-item cpb-action-button">';

		if ( ! empty( $settings['label'] ) ) {

			$html .= '<a href="' . $settings['link'] . '" class="cpb-action-button-item ' . $settings['csshook'] . '">' . $settings['label'] . '</a>';
			
			$html .= '<style type="text/css" scoped>
				.cpb-action-button-item {display: block; background: #981e32; color: #fff; padding: 0.5rem 1rem; border-radius: 2px; margin-bottom: 0.5rem;}
				</style>';

		} // end if
		
		$html .= '</div>';

		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		//$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';

		//$html = '<a href="' . $settings['link'] . '" class="cpb-action-button ' . $settings['csshook'] . '">' . $settings['label'] . '</a>';
		
		$html = $this->get_dynamic_editor( $this->the_item() );
		
		return $html;

	} // end editor

	public function form( $settings ) {

		$html .= Forms_PB::text_field( $this->get_name_field('label'), $settings['label'], 'Label' );

		$html .= Forms_PB::text_field( $this->get_name_field('link'), $settings['link'], 'Link' );

		$html .= Forms_PB::text_field( $this->get_name_field('csshook'), $settings['csshook'], 'CSS Hook' );

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		$clean['label'] = ( ! empty( $s['label'] ) ) ? sanitize_text_field( $s['label'] ) : '';

		$clean['link'] = ( ! empty( $s['link'] ) ) ? sanitize_text_field( $s['link'] ) : '#';

		$clean['csshook'] = ( ! empty( $s['csshook'] ) ) ? sanitize_text_field( $s['csshook'] ) : '';

		return $clean;

	} // end clean

}