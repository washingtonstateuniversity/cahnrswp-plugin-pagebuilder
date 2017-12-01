<?php

class CPB_Item_Image extends CPB_Item {
	
	protected $name = 'Image';
	
	protected $slug = 'image';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$image_array = cpb_get_image_properties_array( $settings['img_id'] );
		
		$html = '';
		
		if ( $settings['img_src'] ) {
		
			ob_start();
			
			include cpb_plugin_dir( 'lib/displays/image/basic.min.php' );
			
			$html .= ob_get_clean();

		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$form = $this->form_fields->insert_media( $this->get_input_name(), $settings );
		
		$form .= '<hr/>';

		$form .= $this->form_fields->text_field( $this->get_input_name('url'), $settings['url'], 'Link Image To:' );

		return $form; 
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();

		$clean['img_src'] = ( ! empty( $settings['img_src'] ) ) ? sanitize_text_field( $settings['img_src'] ) : '';

		$clean['img_id'] = ( ! empty( $settings['img_id'] ) ) ? sanitize_text_field( $settings['img_id'] ) : '';

		$clean['url'] = ( ! empty( $settings['url'] ) ) ? sanitize_text_field( $settings['url'] ) : '';

		return $clean;
		
	}
	
	
}