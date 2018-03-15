<?php

class CPB_Item_Widget extends CPB_Item {
	
	protected $name = 'Widget';
	
	protected $slug = 'widget';
	
	protected $form_size = 'small';
	
	protected $widget = false;
	
	
	public function __construct( $settings, $content ) {

		if ( ! empty( $settings['widget_type'] ) ) {

			$w = sanitize_text_field( $settings['widget_type'] );

			if ( class_exists( $w ) ) {

				$this->widget = new $w();

			}// end if

		} // end if

		parent::__construct( $settings, $content );

	} // end __construct
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['widget_type'] ) ) {
			
			$args = array(
				'before_widget' => '<div class="cpb-widget cpb-item widget_' . $this->widget->id_base . '">',
				'after_widget'  => '</div>',
				);
			
			ob_start();
			
			the_widget( $settings['widget_type'] , $settings , $args );
			
			$html .= ob_get_clean();
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = '';

		if ( $this->widget ) {

			ob_start();

				$this->widget->form( $settings );

			$html .= ob_get_clean();

			$input_name = 'widget-' . $this->widget->id_base . '[]';

			$new_name = $this->get_input_name( false , true  );

			$html = str_replace( $input_name, $new_name, $html );

			$html .= $this->form_fields->hidden_field( $this->get_input_name( 'widget_type' ), $settings['widget_type'] );

		} // end if

		return $html;
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();

		if ( $this->widget ) {

			if ( method_exists( $this->widget, 'update' ) ) {

				$clean = $this->widget->update( $settings, $settings );

			} // end if

		} // end if
		
		$clean['widget_type'] = ( ! empty( $settings['widget_type'] ) ) ? sanitize_text_field( $settings['widget_type'] ) : '';

		return $clean;
	}
	
	
}