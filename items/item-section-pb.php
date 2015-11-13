<?php
class Item_Section_PB extends Item_PB {

	public $slug = 'section';

	public $name = 'Page Section';

	public $allowed_children = array('row','pagebreak');

	public $default_child = 'row';
	
	public $is_layout = true;
	
	public $layouts = array(
		'single'            => 'Single Column',
		'halves'            => 'Two Column',
		'side-right'        => 'Sidebar Right',
		'side-left'         => 'Sidbar Left',
		'thirds'            => 'Three Column',
		/*'thirds-half-left'  => 'Three Column: Left 50%',
		'thirds-half-right' => 'Three Column: Right 50% ',
		'triptych'          => 'Three Column: Middle 50%',*/
		'quarters'          => 'Four Column',
		'pagebreak'          => 'Page Break',
	);

	public function item( $settings, $content ) {

		$class = 'cpb-section';

		if ( ! empty( $settings['bgcolor'] ) ) {

			$class .= ' ' . $settings['bgcolor'] . '-back bg-color';

		} // end if

		if ( ! empty( $settings['fullbleed'] ) ) {

			$class .= ' full-bleed-bg';

		} // end if

		if ( ! empty( $settings['csshook'] ) ) {

			$class .= ' ' . $settings['csshook'];

		} // end if

		$html = '<div class="' . $class . '">' . $content . '</div>';

		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$title = ( ! empty( $settings['title'] ) ) ? $settings['title'] : $this->name;

		$html = '<div class="cpb-item-set cpb-item-' . $this->slug . '-set">';

			$html .= $editor_content;

		$html .= '</div>';

		$html .= Forms_PB::hidden_field( $this->get_name_field( 'children', false ), implode( ',', $this->get_child_ids() ), 'cpb-input-items-set' );

		//$html .= '<input class="cpb-input-items-set" type="text" name="_cwpb[' . $this->id . '][children]" value="' . implode( ',', $this->get_child_ids() ) . '" />';

		$html .= '<footer>';

			$html .= $this->add_row_form( $settings );

		$html .= '</footer>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$html = Forms_PB::text_field( $this->get_name_field( 'title' ), $settings['title'], 'Title', 'cpb-form-100' );

		$html .= Forms_PB::select_field( $this->get_name_field('bgcolor'), $settings['bgcolor'], Forms_PB::get_wsu_colors(), 'Background Color' );

		$html .= Forms_PB::checkbox_field( $this->get_name_field('fullbleed'), 1, $settings['fullbleed'], 'Full Bleed Background' );

		$html .= Forms_PB::text_field( $this->get_name_field('csshook'), $settings['csshook'], 'CSS Hook' );

		return $html;

	} // end form
	
	
	/*
	 * Builds form for adding rows and pagebreak object
	*/
	public function add_row_form( $settings ){
		
		$sid = 'cpb-add-row-' . rand( 0 , 10000000 );
		
		$html = '<div class="cpb-add-row-form" id="' . $sid . '">';
			
		$html .= '<header>';
			
			$html .= 'Add Row';
		
		$html .= '</header>';
		
		$html .= '<nav class="cycle-slideshow" 
			data-cycle-fx="scrollHorz" 
			data-cycle-timeout="0" 
			data-cycle-slides="> div"
			data-cycle-prev="#cpb-pager-prev-' . $sid . '"
			data-cycle-pager="#cpb-pager-' . $sid . '"
        	data-cycle-next="#cpb-pager-next-' . $sid . '">';
			
			$html .= '<div class="cpb-slide">';
			
				$i = 0;
		
				foreach( $this->layouts as $slug => $name ){
					
					if ( $i == 6 ){
						
						$html .= '</div><div class="cpb-slide">';
						
						$i = 0;
						
					} // end if
					
					$html .= '<ul class="cpb-row-option cpb-row-' . $slug . '" data-type="row" data-layout="' . $slug . '">';
					
						$type = ( 'pagebreak' == $slug )? 'pagebreak' : 'row';
					
						$html .= '<li class="cpb-icon"><img src="' . CWPPBURL . 'images/video-spacer.png" /></li>';
						
						$html .= '<li class="cpb-title">' . $name . '<input type="hidden" name="settings[layout]" value="' . $slug . '" /><input type="hidden" name="item_slug" value="' . $type . '" /></li>';
						
					
					$html .= '</ul>';
					
					$i++;
					
				} // end foreach
			
			$html .= '</div>';
		
			$html .= '</nav>';
			
			$html .= '<div class="cpb-pager">';
			
				$html .= '<a href="#" id="cpb-pager-prev-' . $sid . '" class="cpb-pager-prev"><span>Prev</span></a>';
				
				$html .= '<div id="cpb-pager-' . $sid . '" class="cpb-pager-bullets"></div>';
				
				$html .= '<a href="#" id="cpb-pager-next-' . $sid . '" class="cpb-pager-next"><span>Next</span></a>';
			
			$html .= '</div>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end add_row_form

	public function clean( $s ) {

		$clean = array();

		$clean['title'] = ( ! empty( $s['title'] ) ) ? sanitize_text_field( $s['title'] ) : 'Page Section';

		$clean['bgcolor'] = ( ! empty( $s['bgcolor'] ) ) ? sanitize_text_field( $s['bgcolor'] ) : '';

		$clean['fullbleed'] = ( ! empty( $s['fullbleed'] ) ) ? sanitize_text_field( $s['fullbleed'] ) : 0;

		$clean['csshook'] = ( ! empty( $s['csshook'] ) ) ? sanitize_text_field( $s['csshook'] ) : '';

		return $clean;

	} // end clean_settings

}