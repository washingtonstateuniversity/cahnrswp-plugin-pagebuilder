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
		
		$parse_shortcode = new Parse_Shortcode_PB( $content );
		
		$columns = $parse_shortcode->get_shortcode_array( $content , array('column') );
		
		if ( ! empty( $settings['bg_src'] ) && strpos( $settings['bg_src'] , ',' ) ){
			
			$bgs = explode ( ',' , $settings['bg_src'] );
			
			$key = array_rand ( $bgs );
			
			$settings['bg_src'] = $bgs[ $key ];
			
		} // end if
		
		$html = $this->get_row_start( $settings , $columns );
		
		if ( ! empty( $settings['bg_src'] ) && ! empty( $settings['full_bleed'] ) ) {
			
			$html .= $this->get_full_bg( $settings );
			
		} // end if
		
		//$html = '<div class="row ' . $layout . $class . '">';
		
			//if ( ! empty( $settings['bg_src'] ) ){
				
				//$html .= '<div style="position:absolute;left:0;background-image:url(' . $settings['bg_src'] . ');height: 200px;width:100%;"></div>';
				
			//} // end if
		
		
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

		$html .= Forms_PB::hidden_field( $this->get_input_name( 'children', false  ), implode( ',', $this->get_child_ids() ), 'cpb-input-items-set' );

		$html .= '<footer>';

		$html .= '</footer>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$basic = Forms_PB::hidden_field( $this->get_input_name( 'layout' ), $settings['layout'] );
		
		$basic .= Forms_PB::text_field( $this->get_input_name('title'), $settings['title'], 'Title' );
		
		$basic .= Forms_PB::select_field( $this->get_input_name('title_tag'), $settings['title_tag'], Forms_PB::get_header_tags() , 'Title Tag' );
		
		$basic .= Forms_PB::select_field( $this->get_input_name('padding'), $settings['padding'], Forms_PB::get_padding(), 'Padding' );

		$basic .= Forms_PB::select_field( $this->get_input_name('gutter'), $settings['gutter'], Forms_PB::get_gutters(), 'Gutter' );
		
		$basic .= Forms_PB::select_field( $this->get_input_name('textcolor'), $settings['textcolor'], Forms_PB::get_wsu_colors(), 'Text Color' );
		
		$basic .= Forms_PB::text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );

		$bg = Forms_PB::select_field( $this->get_input_name('bgcolor'), $settings['bgcolor'], Forms_PB::get_wsu_colors(), 'Background Color' );
		
		$bg .= Forms_PB::checkbox_field( $this->get_input_name('full_bleed'), 1, $settings['full_bleed'], 'Full Bleed Color' );
		
		$bg .= Forms_PB::text_field( $this->get_input_name('bg_src'), $settings['bg_src'], 'Background Image URL' );
		
		$adv = Forms_PB::text_field( $this->get_input_name('min_height'), $settings['min_height'], 'Minimum Height (px)' );

		
		
		//$advanced .= Forms_PB::checkbox_field( $this->get_input_name('is_tab'), 1, $settings['is_tab'], 'Display as Tab' );

		return array( 'Basic' => $basic , 'Background' => $bg , 'Advanced' => $adv );

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
		
		if ( ! empty( $s['full_bleed'] ) ) $clean['full_bleed'] = sanitize_text_field( $s['full_bleed'] );
		
		if ( ! empty( $s['bg_src'] ) ) $clean['bg_src'] = sanitize_text_field( $s['bg_src'] );
		
		if ( ! empty( $s['min_height'] ) ) $clean['min_height'] = sanitize_text_field( $s['min_height'] );
		
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
		
		if ( ! empty( $this->settings['full_bleed'] ) ) {
			
			if ( ! empty( $this->settings['bg_src'] ) ){
				
				$class .= ' full-bleed-img';
				
			} else {
				
				$class .= ' full-bleed';
				
			} // end if
		}
		
		return $class;
		
	} 
	
	private function get_row_start( $sett , $columns ){
		
		$class = array();
		
		$class[] = $this->get_layout( $sett , $columns );
		
		$class[] = $this->get_classes();
		
		$html = '<div class="row ' . implode( ' ' , $class ) . '" style="' . $this->get_style( $sett ) . '" >';
		
		return $html;
		
	}
	
	
	private function get_layout( $sett , $columns ){
		
		if ( $sett['layout'] == 'side-right' ) {
			
			$is_empty = array( '',' ','[textblock ][/textblock]' , '[textblock ] [/textblock]' );
			
			if ( in_array( $columns[1]['content'] , $is_empty ) ){
				
				$layout = 'single'; 
				
				unset( $columns[1] );
				
			} else {
				
				$layout = $sett['layout'];
				
			} // end if
			
		} else {
			
			$layout = $sett['layout'];
			
		} // end if
		
		return $layout;
		
	}
	
	
	private function get_style( $sett ){
		
		$style = '';
		
		if ( ! empty( $sett['bg_src'] ) /*&& empty( $sett['full_bleed'] )*/ ){
			
			$style .= 'background-image:url(' . $sett['bg_src'] . ');';
			
		} // end if
		
		if ( ! empty( $sett['min_height'] ) /*&& empty( $sett['full_bleed'] )*/ ){
			
			$style .= 'min-height:' . $sett['min_height'] . 'px;';
			
		} // end if
		
		return $style;
		
	}
	
	
	private function get_full_bg( $settings ){
		
		$html .= '<div class="cpb-bg-image" style="';
		
		$html .= 'background-image:url(' . $settings['bg_src'] . ');';
		
		if ( ! empty( $settings['min_height'] ) /*&& empty( $sett['full_bleed'] )*/ ){
			
			$html .= 'min-height:' . $settings['min_height'] . 'px;';
			
		} // end if
		
		$html .= '"></div>';
		
		return $html;
		
	}

}