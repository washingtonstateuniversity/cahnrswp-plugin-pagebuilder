<?php

class CPB_Item_Image extends CPB_Item {
	
	protected $name = 'Image';
	
	protected $slug = 'image';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( $settings['img_src'] ) {
		
			$html .= '<div class="cpb-image">';

				if ( ! empty( $settings['url'] ) ) $html .= '<a href="' . $settings['url'] . '">';

				$html .= '<img src="' . $settings['img_src'] . '" style="width: 100%;display:block" />';

				if ( ! empty( $settings['url'] ) ) $html .= '</a>';

			$html.= '</div>';

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