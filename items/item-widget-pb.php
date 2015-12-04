<?php
class Item_Widget_PB extends Item_PB {

	public $slug = 'widget';

	public $name = 'Widget';

	public $form_size = 'small';

	public $widget = false;

	public function __construct( $settings, $content ) {

		if ( ! empty( $settings['widget_type'] ) ) {

			$w = sanitize_text_field( $settings['widget_type'] );

			if ( class_exists( $w ) ) {

				$this->widget = new $w();

			}// end if

		} // end if

		parent::__construct( $settings, $content );

	}

	public function item( $settings, $content ) {

		$args = array(
			'before_widget' => '<div class="cpb-widget cpb-item widget_' . $this->widget->id_base . '">',
			'after_widget'  => '</div>',
		);

		ob_start();

		$this->widget->widget( $args, $settings );

		return ob_get_clean();

	} // end item

	public function editor( $settings, $editor_content ) {

		//$html = $settings['widget_type'];

		//return $this->widget->name; 
		
		$html = $this->get_dynamic_editor( $this->the_item() );
		
		return $html;

	} // end editor

	public function form( $settings ) {

		$html = '';

		if ( $this->widget ) {

			ob_start();

				$this->widget->form( $settings );

			$html .= ob_get_clean();

			$input_name = 'widget-' . $this->widget->id_base . '[]';

			$new_name = $this->get_name_field();

			$html = str_replace( $input_name, $new_name, $html );

			$html .= Forms_PB::hidden_field( $this->get_name_field( 'widget_type' ), $settings['widget_type'] );

		} // end if

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		if ( $this->widget ) {

			if ( method_exists( $this->widget, 'update' ) ) {

				$clean = $this->widget->update( $s, $s );

			} // end if

		} // end if

		if ( ! empty( $s['widget_type'] ) ) $clean['widget_type'] = sanitize_text_field( $s['widget_type'] );

		return $clean;

	} // end clean

	public function to_shortcode( $content ) {

		$code = '[widget ' . $this->encode_settings() . ']';

		return $code;

	}

}