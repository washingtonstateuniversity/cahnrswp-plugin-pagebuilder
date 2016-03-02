<?php

class Item_Widget_PB extends Item_PB {
	
	public $slug = 'widget';

	public $name = 'Widget';
	
	public $form_size = 'small';
	
	public $widget = false;
	
	
	public function __construct( $settings, $content ) {
		
		global $wp_widget_factory;
		
		if ( isset( $settings['widget_class'] ) && array_key_exists( $settings['widget_class'] , $wp_widget_factory->widgets ) ){
			
			if ( class_exists ( $settings['widget_class'] ) ){
				
				$this->widget = new $settings['widget_class']();
				
			} // end if
			
		} // end if
		
		parent::__construct( $settings, $content );

	} // end __construct
	
	
	
	
	public function item( $settings, $content ) {
		
		if ( $this->widget ){
			
			ob_start();
			
			the_widget( $settings['widget_class'], $settings, array() );
			
			$html = ob_get_clean();
			
		} else {
			
			$html = '';
			
		} // end if

		return $html;

	} // end item
	
	
	public function editor( $settings, $editor_content ) {
		
		//if ( $this->widget ){
			
		//} // end if

		//return 'widget';
		
		$html = $this->get_dynamic_editor( $this->the_item() );
		
		return $html;

	} // end editor
	
	public function form( $settings ) {
		
		if ( $this->widget ){
			
			$html = Forms_PB::text_field( $this->get_name_field('widget_class'), $settings['widget_class'], 'Widget Class' );
			
			ob_start();
			
			$this->widget->form( $settings );
			
			$form = ob_get_clean();
			
			$replace = 'widget-' . $this->widget->id_base . '[]';
			
			$html .= str_replace( $replace , $this->get_name_field(), $form );
			
		} // end if
		
		/*global $wp_widget_factory;
		
		if ( isset( $settings['widget_class'] ) && array_key_exists( $settings['widget_class'] , $wp_widget_factory->widgets ) ){
			
			if ( class_exists ( $settings['widget_class'] ) ){
				
				$widget = new $settings['widget_class']();
				
				var_dump( $widget );
			
				$html = Forms_PB::text_field( $this->get_name_field('widget_class'), $settings['widget_class'], 'Widget Class' );
			
				ob_start();
				
				$widget->form( $settings );
				
				$form = ob_get_clean();
				
				$replace = 'widget-' . $widget->id_base . '[]';
				
				$html .= str_replace( $replace , $this->get_name_field(), $form ); 
			
			} // end if
			
		} // end if*/

		return $html;
	}
	
	public function clean( $s ) {
		
		//var_dump( $s );

		$clean = array();
		
		if ( $this->widget ){
			
			$clean = $this->widget->update( $s , array() );
			
		} else {
			
			$clean = array();
			
		}
		
		if ( isset( $s['widget_class'] ) ) $clean['widget_class'] = sanitize_text_field( $s['widget_class'] );
		
		return $clean;

	} // end clean
	
}