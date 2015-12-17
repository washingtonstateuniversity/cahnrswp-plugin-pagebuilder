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
		
		$content = ( in_array( $this->content, $empty ) ) ? '<div class="cpb-empty">Click to add text ...</div>':false;

		//$content = ( $this->content && ! in_array( $this->content, $empty ) ) ? $this->content : '<div class="cpb-empty">Click to add text ...</div>';

		//$html = $content;
		
		//$html = '<textarea class="cpb-dynamic-editor" style="display:none">' . apply_filters( 'the_content' , $content ) . '</textarea>';
		
		//$html .= '<iframe class="cpb-dynamic-editor" src="' . home_url() . '?cpb-dynamic-editor=true" scrolling="no"></iframe>';
		
		$html = $this->get_dynamic_editor( $content , true );
		
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