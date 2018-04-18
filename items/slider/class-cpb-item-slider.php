<?php

class CPB_Item_Slider extends CPB_Item {
	
	protected $name = 'Horizontal Slider';
	
	protected $slug = 'slider';
	
	protected $form_size = 'medium';
	
	
	public function item( $settings , $content ){
	
		$html = '<div class="cpb-slider">';
		
		$default_settings = array(
			'display' => 'single-slider',
			'source' => 'remote',
		);
		
		$settings = shortcode_atts( $default_settings, $settings, $this->slug );
		
		$slides = array();
		
		switch( $settings['source'] ){
			
			case 'remote':
				$slides = $this->get_remote_slides( $settings );
				break;
			
		} // Ens switch
		
		switch( $settings['display'] ){
			
			case 'four-up':
				$html .= $this->get_display_4_up_slider( $settings );
				break;
			
			default:
				$html .= $this->get_display_single_slider( $slides, $settings );
				break;
			
		} // End switch
		
		$html .= '</div>';
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		$display =  $this->form_fields->select_field( 
			$this->get_input_name('display'), 
			$settings['display'], 
			array(
				'single-slider' => 'Slider',
				'four-up'		=> '4 Up Slider',
			), 
			'Display Type' );
		
		return array('Basic' => $html, 'Display' => $display );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['display'] = ( ! empty( $settings['display'] ) )? sanitize_text_field( $settings['display'] ) : '';
		
		return $clean;
	}
	
	
	protected function get_display_single_slider( $slides, $settings ){
		
		$html = '';
		
		ob_start();
		
		include 'display/single-slider.php';
		
		$html .= ob_get_clean();
		
		return $html;
		
	} // End get_display_single_slider
	
	protected function get_display_4_up_slider( $settings ){
		
		$html = '';
		
		ob_start();
		
		include 'display/4-up-slider.php';
		
		$html .= ob_get_clean();
		
		return $html;
		
	} // End get_display_single_slider
	
	
	protected function get_remote_slides( $settings ){
		
		$slides = array();
		
		global $cahnrs_displayed_posts;
		
		$exclude = ( ! empty( $cahnrs_displayed_posts['article']['remote'] ) )?  implode( ',', $cahnrs_displayed_posts['article']['remote'] ) : false;
		
		$remote_args = array( 'post_type' => 'article', 'per_page' => 1 );
		
		if ( $exclude ) $remote_args['exclude'] = $exclude;
		
		require_once CAHNRS_Pagebuilder_Plugin::$dir . 'classes/class-cpb-remote-request.php';
		
		$remote_request = new CPB_Remote_Request( $remote_args );
		
		$response = $remote_request->response;
		
		if ( ! isset( $cahnrs_displayed_posts ) ) $cahnrs_displayed_posts = array();
		
		if ( ! isset( $cahnrs_displayed_posts['article'] ) ) $cahnrs_displayed_posts['article'] = array();
		
		if ( ! isset( $cahnrs_displayed_posts['article']['local'] ) ) $cahnrs_displayed_posts['article']['local'] = array();
		
		if ( ! isset( $cahnrs_displayed_posts['article']['remote'] ) ) $cahnrs_displayed_posts['article']['remote'] = array();
		
		foreach( $remote_request->response as $remote_slide ){
				
			$slide = array();
					
			$slide['image'] = ( ! empty( $remote_slide['post_images'] ) ) ? $remote_slide['post_images']['medium'] : '';
			
			$slide['link'] = ( ! empty( $remote_slide['link'] ) ) ? $remote_slide['link'] : '';
			
			$slide['title'] = ( ! empty( $remote_slide['title']['rendered'] ) ) ? $remote_slide['title']['rendered'] : '';
			
			$slide['excerpt'] = ( ! empty( $remote_slide['excerpt']['rendered'] ) ) ? $remote_slide['excerpt']['rendered'] : '';
			
			$slide['date'] = ( ! empty( $remote_slide['date'] ) ) ? $remote_slide['date'] : '';
			
			$slide['id'] = ( ! empty( $remote_slide['id'] ) ) ? $remote_slide['id'] : '';
			
			$slide['link_start'] = '<a href="' . $slide['link'] . '">';
		
			$slide['link_end'] = '</a>';
			
			$cahnrs_displayed_posts['article']['remote'][] = $slide['id'];
			
			$slides[] = $slide;
			
		} // End foreach
		
		return $slides;
		
	} // End get_remote_slides
	
}