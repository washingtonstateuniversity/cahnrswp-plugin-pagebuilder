<?php

require_once CWPPBDIR. 'forms/form-pb.php';

class Item_PB extends Form_PB {
/*class Item_PB extends Forms_PB {*/

	public $layout_types = array('section','row','column','pagebreak');
	
	public $is_layout = false;

	public $i_array = array('one','two','three','four');

	public $settings = array();

	public $content = '';

	public $id;

	public $forms = '';

	public $form_size = 'small';

	public $children = array();

	public $default_settings = array();

	public $allowed_children = false;

	public $default_child = false;
	
	public $enclosed = true;
	
	public $allow_ajax_update = false;

	public $i = 0;

	public function __construct( $settings, $content ) {

		$this->id = $this->slug . '_' . rand( 0, 10000000 );

		$this->settings = $this->the_settings( $settings );

		$this->content = $content;

	} // end __construct

	public function the_settings( $settings ) {

		$clean_set = array();

		if ( method_exists( $this, 'clean' ) ) {
			
			if ( defined( 'CAHNRSWPPBSAVE' ) && CAHNRSWPPBSAVE && method_exists( $this, 'clean_save' ) ){
				
				$clean_set = $this->clean_save( $settings );
				
			} else {
				
				$clean_set = $this->clean( $settings );
				
			}// end if

		} // end if

		return $clean_set;

	} // end the_settings

	public function the_item() {

		$html = '';

		if ( method_exists( $this, 'item' ) ) {

			$html .= $this->item( $this->settings, do_shortcode( $this->content ) );

		} // end if

		return $html;

	}

	public function the_editor( $editor_content ) {

		$html = '';

		if ( method_exists( $this, 'editor' ) ) {

			$html .= $this->editor( $this->settings, $editor_content );

			if ( $html ) {

				$full = ( ! in_array( $this->slug, $this->layout_types ) ) ? true : false;

				$html = $this->wrap_item( $html, $this->settings, $full ); // end if

			} // end if

			return $html;

		} else {

			return '';

		} // end if

	} // end the_editor

	public function the_form() {

		$forms = $this->form( $this->settings );

		if ( ! is_array( $forms ) ) {

			$forms = array( 'Settings' => $forms );

		} // end if
		
		$actions = 'close-form-action';
		
		if ( $this->allow_ajax_update ) $actions .= ' update_item_editor';

		return Forms_PB::get_item_form( $forms , $actions );

	}

	public function to_shortcode( $content ) {
		
		if ( $this->enclosed ){

			$code = '[' . $this->slug . $this->encode_settings() . ']' . $content . '[/' . $this->slug . ']';
		
		} else {
			
			
			
		} // end if

		return $code;

	} // end to_shortcode

	public function get_name_field( $field = 'na', $is_setting = true , $is_array = false ) {

		$name = '_cpb[' . $this->id . ']';

		if ( $is_setting ) $name .= '[settings]';

		if ( $field != 'na' && ! $is_array ) {

			$name .= '[' . $field . ']';

		} else if ( $is_array ){
			
			$name .= $field;
			
		}// end if

		return $name;

	} // end get_name_field

	/*
	 * Items have a standardized wrapper around them. The editor in this case only
	 * returns the inner content of the item.
	*/
	public function wrap_item( $content, $settings, $is_item = true ) {

		$item_class = ( $is_item ) ? 'cpb-item cpb-column-item ' : 'cpb-item ';

		$html = '<div class="cpb-item ' . $this->id . ' ' . $item_class . 'cpb-' . $this->slug . ' ' . $this->i_array[ $this->i ] . '" data-id="' .  $this->id . '">';

		$html .= Forms_PB::hidden_field( $this->get_name_field( 'type', false ), $this->slug );

			if ( $is_item ) {

				$title = $this->name;

				if( ! empty( $settings['title'] ) ) $title = $settings['title'] . ' | ' . $title;

				$html .= '<header class="cpb-item-header">';
				
					$html .= '<nav>';

						$html .= '<div class="title">' . $title . '</div>';
		
						$html .= '<a href="#" class="remove-item-action"></a>';
					
					$html .= '</nav>';

				$html .= '</header>';

				$html .= '<div class="cpb-item-set">';

					$html .= '<div class="cpb-item-content">';

						$html .= $content;

					$html .= '</div>';

					$html .= '<a href="#" class="cpb-edit-item" data-id="' . $this->id . '"></a>';
					
					$html .= '<div class="cpb-edit-hover-icon"></div>';
					
				$html .= '</div>';

				$html .= '<footer>';

				$html .= '</footer>';

			} else {

				$html .= $content;

			} // end if

		$html .= '</div>';

		return $html;

	} // end wrap_item

	public function get_child_ids() {

		$child_ids = array();

		if ( $this->children ) {

			foreach( $this->children as $child ) {

				$child_ids[] = $child->id;

			} // end foreach

		} // end if

		return $child_ids;

	} // end get_child_ids

	public function encode_settings() {
		
		if ( method_exists( $this, 'clean' ) ) {
				
			$default = $this->clean( array() );

		} else {
			
			$default = array();
			
		}// end if
		
		$sets = array();

		foreach( $this->settings as $key => $value ) { 
			
			if ( ! ( isset( $default[ $key ] ) && $default[ $key ] == $value ) ) { 
				
				$sets[] = $key . '="' . $value . '"';
			
			} // end if

		} // end foreach
		
		//var_dump( $sets );

		return ' ' . implode( ' ', $sets );

	} // encode_settings

}