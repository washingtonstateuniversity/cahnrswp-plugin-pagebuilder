<?php
class Form_PB {
	
	
	public function wrap_form( $forms, $args = array() ){
		
		if ( ! is_array( $forms ) ) $forms = array( 'Settings' => $forms );
		
		$form_args = array(
			'action' => 'close-form-action',
			'label'  => 'Done',
			'width'  => 'medium',
			'class'  => '',
			'id'     => 'cpb_' . rand(0,10000000),
		);
		
		foreach( $form_args as $key => $value ){
			
			if ( ! isset( $args[ $key ] ) ) $args[ $key ] = $value; 
			
		} // end foreach
		
		$tabs = $this->get_form_tabs( $forms );
		
		$sections = $this->get_form_sections( $forms );
		
		$html = '<fieldset class="cpb-form cpb-form-' . $args['width'] . ' ' . $args['class'] . '" id="form_' . $args['id'] . '">';

			$html .= '<div class="cpb-form-frame">';

				$html .= '<a href="#" class="close-form-action">Close X</a>';
				
				$html .= '<nav>' . $tabs . '</nav>';

				$html .= $sections;

				$html .= '<footer>';

					$html .= '<a href="#" class="cpb-button ' . $args['action'] . '" >' . $args['label'] . '</a>';

				$html .= '</footer>';

			$html .= '</div>';

		$html .= '</fieldset>';

		return $html;
		
	} // end wrap_form
	
	
	public function get_form_tabs( $forms ){
		
		$tabs = '';
		
		$active = 'active';
		
		foreach( $forms as $label => $form ){
			
			$tabs .= '<a href="#" class="' . $active . '">' . $label . '</a>';
			
			$active = '';
			
		} // end foreach
		
		return $tabs;
		
	} // end get_form_tabs
	
	
	public function get_form_sections( $forms ){
		
		$sec = '';
		
		$active = 'active';
		
		foreach( $forms as $label => $form ){
			
			$sec .= '<div class="cpb-form-content ' . $active . '"><div class="cpb-form-content-inner">' . $form . '</div></div>';
			
			$active = '';
			
		} // end foreach
		
		return $sec;
		
	} // end get_form_sections
	
	
	public function cpb_is_selected( $a , $b ){
		
		if ( $a == $b ){
			
			return ' selected';
			
		} else {
			
			return '';
			
		} // end if
		
	} // end cpb_is_selected
	
	
	public function radio_toggle_button( $name , $value , $current_value , $label , $class = '' ){
		
		$html = '<a href="#" class="cpb-radio-button' . $this->cpb_is_selected( $value , $current_value ) . ' ' . $class . '">';
		
		$html .= $label . '<input type="radio" name="' . $name . '" value="' . $value . '" ' . checked( $value , $current_value , false ) . '/></a>';
		
		return $html;
		
	} // end radio_toggle_button
	
	public function accordion_radio( $name , $value , $current_value , $label , $form , $summary = '' , $class = '' ){
		
		$html = '<a href="#" class="cpb-accordion' . $this->cpb_is_selected( $value , $current_value ) . ' ' . $class . '">' . $label;
		
			if ( $summary ) $html .= '<span class="cpb-summary">' . $summary . '</span>';
		
			$html .= '<input type="radio" value="' . $value . '" name="' . $name . '" ' . checked( $value , $current_value , false ) . ' />';
		
		$html .= '</a>';
		
		$html .= '<div class="cpb-accordion-content' . $this->cpb_is_selected( $value , $current_value ) . '">' . $form . '</div>';
		
		return $html;
		
	}
	
}