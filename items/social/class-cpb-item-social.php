<?php

class CPB_Item_Social extends CPB_Item {
	
	protected $name = 'Social Media';
	
	protected $slug = 'social';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		/*$ls = ( ! empty( $settings['link'] ) ) ? '<a href="' . $settings['link'] . '" >' : '';
		
		$le = ( ! empty( $settings['link'] ) ) ? '</a>' : '';
		
		$classes = array();
		
		if ( ! empty( $settings['style'] ) ){
			
			$classes[] = $settings['style'];
			
		} // End if
		
		if ( ! empty( $settings['csshook'] ) ){
			
			$classes[] = $settings['csshook'];
			
		} // End if
		
		if ( ! empty( $settings['textcolor'] ) ){
			
			$classes[] = $settings['textcolor'] . '-text';
			
		} // End if
		
		$html = '<' . $settings['tag'] . ' class="' . implode( ' ', $classes ) . '">' . $ls . $settings['title'] . $le . '</' . $settings['tag'] . '>';
		
		if ( ! empty( $settings['anchor'] ) ){
			
			$html = '<a name="' . $settings['anchor'] . '"></a>' . $html;
			
		} // end if*/
		
		if ( empty( $settings['height'] ) ){
			
			$settings['height'] = 800;
			
		} // end if
		
		$feeds = array();
		
		if ( ! empty( $settings['twitter'] ) ){
			
			$order = ( ! empty( $settings['twitter_order'] ) )? $settings['twitter_order'] : 2;
			
			$feeds[ $order ] = array(
				'type' 		=> 'twitter',
				'src' 		=> $settings['twitter'],
				'height' 	=> $settings['height'],
				);
			
		} // End if
		
		if ( ! empty( $settings['facebook'] ) ){
			
			$order = ( ! empty( $settings['facebook_order'] ) )? $settings['facebook_order'] : 3;
			
			$feeds[ $order ] = array(
				'type' 		=> 'facebook',
				'src' 		=> $settings['facebook'],
				'height' 	=> $settings['height'],
				);
			
		} // End if
		
		$html = '<div class="cpb-social-item">';
		
		ksort( $feeds );
		
		$icon_html = '<div class="cpb-social-icons-wrapper">';
		
		$content_html = '<div class="cpb-social-content-wrapper">';
		
		$i = 0;
		
		foreach( $feeds as $index => $feed ){
			
			$active = ( $i == 0 )? ' active' : '';
			
			$content_html .= '<div class="cpb-social-content cpb-social-content-' . $feed['type'] . $active . '">';
			
			switch( $feed['type'] ){
					
				case 'facebook':
					$icon_html .= $this->get_facebook_html( $feed, $i, true );
					$content_html .= $this->get_facebook_html( $feed, $i );
					break;
				case 'twitter':
					$icon_html .= $this->get_twitter_html( $feed, $i, true );
					$content_html .= $this->get_twitter_html( $feed, $i );
					break;
			} // End 
			
			$content_html .= '</div>';
			
			$i++;
			
		} // end foreach
		
		$icon_html .= '</div>';
		
		$content_html .= '</div>';
		
		$html .= $icon_html . $content_html . '</div>';
		
		return $html;
		
	}// end item
	
	
	protected function get_facebook_html( $feed, $i, $is_icon = false ){
		
		$html = '';
		
		$height = $feed['height'];
		
		if ( $is_icon ){
			
			$html .= '<div class="cpb-social-icon cpb-social-icon-facebook"></div>';
			
		} else {
			
			$facebook_src = $feed['src'];
			
			ob_start();
			
			include 'parts/facebook.php';
			
			$html .= ob_get_clean();
			
		} // End if
		
		return $html;
		
	} // End get_facebook_html
	
	protected function get_twitter_html( $feed, $i, $is_icon = false ){
		
		$html = '';
		
		$height = $feed['height'];
		
		if ( $is_icon ){
			
			$html .= '<div class="cpb-social-icon cpb-social-icon-twitter"></div>';
			
		} else {
			
			$twitter_src = $feed['src'];
			
			ob_start();
			
			include 'parts/twitter.php';
			
			$html .= ob_get_clean();
			
		} // End if
		
		return $html;
		
	} // End get_facebook_html
	
	
	public function form( $settings , $content ){
		
		$html = $this->form_fields->text_field( $this->get_input_name('twitter') , $settings['twitter'] , 'Twitter URL' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('twitter_order') , $settings['twitter_order'] , 'Twitter Order' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('facebook') , $settings['facebook'] , 'Facebook URL' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('facebook_order') , $settings['facebook_order'] , 'Facebook Order' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('height') , $settings['height'] , 'height (no px)' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		/*$styles = array(
			'' 									=> 'None',
			'underline-heading' 				=> 'Underlined Heading',
			'underline-heading small-heading' 	=> 'Underlined Heading (small font)',
		);
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $this->form_fields->get_header_tags() , 'Tag Type' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('link') , $settings['link'] , 'Link' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('style'), $settings['style'], $styles, 'Style' );*/
		
		return array('Basic' => $html);
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['twitter'] = ( ! empty( $settings['twitter'] ) )? sanitize_text_field( $settings['twitter'] ) : '';
		
		$clean['twitter_order'] = ( ! empty( $settings['twitter_order'] ) )? sanitize_text_field( $settings['twitter_order'] ) : '';
		
		$clean['facebook'] = ( ! empty( $settings['facebook'] ) )? sanitize_text_field( $settings['facebook'] ) : '';
		
		$clean['facebook_order'] = ( ! empty( $settings['facebook_order'] ) )? sanitize_text_field( $settings['facebook_order'] ) : '';
		
		$clean['height'] = ( ! empty( $settings['height'] ) )? sanitize_text_field( $settings['height'] ) : '';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) )? sanitize_text_field( $settings['csshook'] ) : '';
		
		return $clean;
	}
	
	
}