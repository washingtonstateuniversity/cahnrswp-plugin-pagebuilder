<?php
class Item_Textblock_PB extends Item_PB {

	public $slug = 'textblock';

	public $name = 'Textblock';

	public $desc = 'Add additional text/html';

	public $form_size = 'large';
	
	//public $allow_ajax_update = true;

	public function item( $settings, $content ) {
		
		$html = wpautop( $content );

		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$empty = array( ' ', '&nbsp;' );

		$content = ( $this->content && ! in_array( $this->content, $empty ) ) ? $this->content : '<div class="cpb-empty">Click to add text ...</div>';

		$html = $content;

		return $html;

	} // end editor

	public function form( $settings ) {

		$html = Forms_PB::wp_editor_field( $this->id, $this->content , false , 'cpb-field-one-column' );

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		return $clean;

	} // end clean

}