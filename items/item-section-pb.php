<?php
class Item_Section_PB extends Item_PB {

	public $slug = 'section';

	public $name = 'Page Section';

	public $allowed_children = 'row';

	public $default_child = 'row';
	
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

			$html .= '<div class="cpb-add-row-form">';
			
			$html .= '<header>';
				
				$html .= 'Add Row';
			
			$html .= '</header>';
			
			$html .= '<nav>';
			
				foreach( $this->layouts as $id => $name ){
					
					$html .= '<ul class="cpb-row-option" data-type="row" data-layout="' . $id . '">';
					
						$html .= '<li class="cpb-icon"><img src="' . CWPPBURL . 'images/video-spacer.png" /></li>';
						
						$html .= '<li class="cpb-title">' . $name . '<input type="hidden" name="settings[layout]" value="' . $id . '" /></li>';
					
					$html .= '</ul>';
					
				} // end foreach
			
				$html .= '</nav>';
			
			$html .= '</div>';

		$html .= '</footer>';
		
		$html .= '<header class="cpb-item-' . $this->slug . '-header">';
		
			$html .= '<div class="cpb-arrow-up"></div>';
			
			$html .= '<nav>';

				$html .= '<div class="title">' . $title . '</div>';

				$html .= '<a href="#" class="cpb-edit-item" data-id="' . $this->id . '"></a>';

				$html .= '<a href="#" class="remove-item-action"></a>';
			
			$html .= '</nav>';
			
			$html .= '<div class="cpb-arrow-down add-section-action">+</div>';

		$html .= '</header>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$html = $this->text_field( $this->get_name_field( 'title' ), $settings['title'], 'Title', 'cpb-form-100' );

		$html .= Forms_PB::select_field( $this->get_name_field('bgcolor'), $settings['bgcolor'], Forms_PB::get_wsu_colors(), 'Background Color' );

		$html .= Forms_PB::checkbox_field( $this->get_name_field('fullbleed'), 1, $settings['fullbleed'], 'Full Bleed Background' );

		$html .= Forms_PB::text_field( $this->get_name_field('csshook'), $settings['csshook'], 'CSS Hook' );

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		$clean['title'] = ( ! empty( $s['title'] ) ) ? sanitize_text_field( $s['title'] ) : 'Page Section';

		$clean['bgcolor'] = ( ! empty( $s['bgcolor'] ) ) ? sanitize_text_field( $s['bgcolor'] ) : '';

		$clean['fullbleed'] = ( ! empty( $s['fullbleed'] ) ) ? sanitize_text_field( $s['fullbleed'] ) : 0;

		$clean['csshook'] = ( ! empty( $s['csshook'] ) ) ? sanitize_text_field( $s['csshook'] ) : '';

		return $clean;

	} // end clean_settings

}