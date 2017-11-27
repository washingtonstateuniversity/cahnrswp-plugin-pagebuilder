<?php

class CPB_Item_Table extends CPB_Item {
	
	protected $name = 'Table (Image)';
	
	protected $slug = 'cpbtable';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( $settings['img_src'] ) {
			
			$html .= '<figure class="cpb-figure-table">';
			
				$html .= '<img src="' . $settings['img_src'] . '" style="width: 100%;display:block" />';
				
				$html .= '<figcaption>' . $settings['caption'] . '</figcaption>';
			
			$html .= '</figure>';

		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$form = $this->form_fields->insert_media( $this->get_input_name(), $settings );
		
		$form .= '<hr/>';

		$form .= $this->form_fields->text_field( $this->get_input_name('caption'), $settings['caption'], 'Caption' );

		return $form; 
		
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();

		$clean['img_src'] = ( ! empty( $settings['img_src'] ) ) ? sanitize_text_field( $settings['img_src'] ) : '';

		$clean['img_id'] = ( ! empty( $settings['img_id'] ) ) ? sanitize_text_field( $settings['img_id'] ) : '';

		$clean['caption'] = ( ! empty( $settings['caption'] ) ) ? sanitize_text_field( $settings['caption'] ) : '';

		return $clean;
		
	}
	
	protected function css() {
		/*
		.cpb-action-button a {
			display: block;
			background: #981e32;
			color: #fff;
			padding: 1rem;
		}*/
		
		$style = '.cpb-figure-table {box-sizing:box-sizing:border-box;padding: 0.5rem;border:1px solid #ddd;background:#fff;margin-bottom:1rem;}';
		
		$style .= '.cpb-figure-table figcaption {padding: 0.25rem 0 0.5rem;font-size:0.8rem;}';
		
		return $style;
		
	}
	
	
}