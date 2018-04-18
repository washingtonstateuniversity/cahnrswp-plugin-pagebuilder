<?php

class CPB_Item_Banner extends CPB_Item {
	
	protected $name = 'Banner Image';
	
	protected $slug = 'banner';
	
	protected $form_size = 'medium';
	
	
	public function item( $settings , $content ){
		
		$settings['caption'] = htmlspecialchars_decode( str_replace( '&amp;', '&',  $settings['caption'] ) );
		
		$settings['content'] = htmlspecialchars_decode( str_replace( '&amp;', '&',  $settings['content'] ) );
		
		$html = '';
		
		switch( $settings['display'] ){
			
			case 'full-width':
				$html .= $this->get_display_full_width( $settings );
				break;
			default:
				//$html .= $this->get_display_basic( $settings );
				break;
			
		} // End switch
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$settings['caption'] = htmlspecialchars_decode( str_replace( '&amp;', '&',  $settings['caption'] ) );
		
		$settings['content'] = htmlspecialchars_decode( str_replace( '&amp;', '&',  $settings['content'] ) );
		
		$basic = $this->form_fields->insert_media( $this->get_input_name(), $settings );
		
		$banner_styles = array(
			'basic' 							=> 'Basic',
			'full-width' 						=> 'Full Width',
		);
		
		$basic .= $this->form_fields->select_field( $this->get_input_name('display') , $settings['display'] , $banner_styles , 'Display Style' );
		
		$basic .= $this->form_fields->text_field( $this->get_input_name('height') , $settings['height'] , 'Banner Height' );
		
		$basic .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' );
		
		$content .= $this->form_fields->textarea_field( $this->get_input_name('caption'), $settings['caption'], 'Banner Caption');
		
		$content .= $this->form_fields->textarea_field( $this->get_input_name('content'), $settings['content'], 'Banner Inner Content');
		/*
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('link') , $settings['link'] , 'Link' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('style'), $settings['style'], $styles, 'Style' );*/
		
		return array('Basic' => $basic , 'Content' => $content, 'Advanced' => '' );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['img_src'] = ( ! empty( $settings['img_src'] ) ) ? sanitize_text_field( $settings['img_src'] ) : '';

		$clean['img_id'] = ( ! empty( $settings['img_id'] ) ) ? sanitize_text_field( $settings['img_id'] ) : '';
		
		$clean['display'] = ( ! empty( $settings['display'] ) ) ? sanitize_text_field( $settings['display'] ) : '';
		
		$clean['height'] = ( ! empty( $settings['height'] ) ) ? sanitize_text_field( $settings['height'] ) : '';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) )? sanitize_text_field( $settings['csshook'] ) : '';
		
		$clean['caption'] = ( ! empty( $settings['caption'] ) )? htmlspecialchars( $settings['caption'] ) : '';
		
		$clean['content'] = ( ! empty( $settings['content'] ) )? htmlspecialchars( $settings['content'] ) : '';
		
		/*$clean['tag'] = ( ! empty( $settings['tag'] ) )? sanitize_text_field( $settings['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) )? sanitize_text_field( $settings['textcolor'] ) : '';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) )? sanitize_text_field( $settings['csshook'] ) : '';
		
		$clean['anchor'] = ( ! empty( $settings['anchor'] ) )? sanitize_text_field( $settings['anchor'] ) : '';
		
		$clean['link'] = ( ! empty( $settings['link'] ) )? sanitize_text_field( $settings['link'] ) : '';
		
		$clean['style'] = ( ! empty( $settings['style'] ) )? sanitize_text_field( $settings['style'] ) : '';*/
		
		return $clean;
	}
	
	
	protected function get_display_full_width( $settings ){
		
		$html = '';
		
		$height_style = ( $settings['height'] ) ? 'height:' . $settings['height'] . ';' : '';
		
		if ( ! empty( $settings['img_src'] ) ){
			
			ob_start();
		
			include 'displays/full-width.php';
		
			$html .= ob_get_clean();
			
		} // End if
		
		return $html;
		
	} // End get_display_basic
	
	
}