<?php

require_once CWPPBDIR . 'classes/class-parse-shortcode-pb.php';

class Item_Row_PB extends Item_PB {

	public $slug = 'row';

	public $name = 'Page Row';

	public $allowed_children = 'column';

	public $default_child = 'column';
	
	public $is_layout = true;

	public function item( $settings, $content ) {
		
		global $cpb_column_i;

		$cpb_column_i = 1;
		
		$class = $this->get_classes();
		
		$parse_shortcode = new Parse_Shortcode_PB( $content );
		
		$columns = $parse_shortcode->get_shortcode_array( $content , array('column') );
		
		if ( $this->settings['layout'] == 'side-right' ) {
			
			$is_empty = array( '',' ','[textblock ][/textblock]' , '[textblock ] [/textblock]' );
			
			if ( in_array( $columns[1]['content'] , $is_empty ) ){
				
				$layout = 'single'; 
				
				unset( $columns[1] );
				
			} else {
				
				$layout = $this->settings['layout'];
				
			} // end if
			
		} else {
			
			$layout = $this->settings['layout'];
			
		} // end if
		
		$html = '<div class="row ' . $layout . $class . '">';
		
			foreach( $columns as $index => $column ){
				
				$html .= do_shortcode( $column['fullcontent'] );
				
			} // end if
		
		$html .= '</div>';
		
		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;

		$html .= '<header class="cpb-item-' . $this->slug . '-header">';
			
			$html .= '<nav>';

				$html .= '<div class="title">' . $title . '</div>';

				$html .= '<a href="#" class="cpb-edit-item" data-id="' . $this->id . '"></a>';

				$html .= '<a href="#" class="remove-item-action"></a>';
			
			$html .= '</nav>';

		$html .= '</header>';

		$html .= '<div class="cpb-item-set cpb-' . $settings['layout'] . '  cpb-item-' . $this->slug . '-set">';

			$html .= $editor_content;

		$html .= '</div>';

		$html .= Forms_PB::hidden_field( $this->get_name_field( 'children', false  ), implode( ',', $this->get_child_ids() ), 'cpb-input-items-set' );

		$html .= '<footer>';

		$html .= '</footer>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$basic = Forms_PB::hidden_field( $this->get_name_field( 'layout' ), $settings['layout'] );
		
		$basic .= Forms_PB::text_field( $this->get_name_field('title'), $settings['title'], 'Title' );
		
		$basic .= Forms_PB::select_field( $this->get_name_field('title_tag'), $settings['title_tag'], Forms_PB::get_header_tags() , 'Title Tag' );
		
		$basic .= Forms_PB::select_field( $this->get_name_field('padding'), $settings['padding'], Forms_PB::get_padding(), 'Padding' );

		$basic .= Forms_PB::select_field( $this->get_name_field('gutter'), $settings['gutter'], Forms_PB::get_gutters(), 'Gutter' );

		$advanced = Forms_PB::select_field( $this->get_name_field('bgcolor'), $settings['bgcolor'], Forms_PB::get_wsu_colors(), 'Background Color' );

		$advanced .= Forms_PB::select_field( $this->get_name_field('textcolor'), $settings['textcolor'], Forms_PB::get_wsu_colors(), 'Text Color' );

		$advanced .= Forms_PB::text_field( $this->get_name_field('csshook'), $settings['csshook'], 'CSS Hook' );
		
		//$advanced .= Forms_PB::checkbox_field( $this->get_name_field('is_tab'), 1, $settings['is_tab'], 'Display as Tab' );

		return array( 'Basic' => $basic , 'Advanced' => $advanced );

	} // end form
	
	public function the_item() {

		$html = $this->item( $this->settings, $this->content );

		return $html;

	}

	public function clean( $s ) {

		$clean = array();
		
		$clean['title'] = ( isset( $s['title'] ) ) ? sanitize_text_field( $s['title'] ) : '';
		
		$clean['title_tag'] = ( isset( $s['title_tag'] ) ) ? sanitize_text_field( $s['title_tag'] ) : 'h2';

		$clean['layout'] = ( ! empty( $s['layout'] ) ) ? sanitize_text_field( $s['layout'] ) : 'single';

		$clean['bgcolor'] = ( ! empty( $s['bgcolor'] ) ) ? sanitize_text_field( $s['bgcolor'] ) : '';

		$clean['textcolor'] = ( ! empty( $s['textcolor'] ) ) ? sanitize_text_field( $s['textcolor'] ) : '';

		$clean['padding'] = ( isset( $s['padding'] ) ) ? sanitize_text_field( $s['padding'] ) : 'pad-top';

		$clean['gutter'] = ( ! empty( $s['gutter'] ) ) ? sanitize_text_field( $s['gutter'] ) : 'gutter';

		$clean['csshook'] = ( ! empty( $s['csshook'] ) ) ? sanitize_text_field( $s['csshook'] ) : '';
		
		//$clean['is_tab'] = ( isset( $s['is_tab'] ) ) ? sanitize_text_field( $s['is_tab'] ) : 0;

		return $clean;

	} // end clean_settings
	
	
	private function get_classes(){
		
		$class = '';
		
		if ( ! empty( $this->settings['bgcolor'] ) ) {
			$class .= ' ' . $this->settings['bgcolor'] . '-back';
		}

		if ( ! empty( $this->settings['textcolor'] ) ) {
			$class .= ' ' . $this->settings['textcolor'] . '-text';
		}

		if ( ! empty( $this->settings['padding'] ) ) {
			$class .= ' ' . $this->settings['padding'];
		}

		if ( ! empty( $this->settings['gutter'] ) ) {
			$class .= ' ' . $this->settings['gutter'];
		}

		if ( ! empty( $this->settings['csshook'] ) ) {
			$class .= ' ' . $this->settings['csshook'];
		}
		
		return $class;
		
	} 

}