<?php

class CPB_Item_Promo extends CPB_Item {
	
	protected $name = 'Promo';
	
	protected $slug = 'promo';
	
	protected $fields = array('title','img','link','excerpt');
	
	protected $items = array();
	
	public function get_fields(){ return $this->fields; }
	
	public function get_items(){ return $this->items; }
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['promo_type'] ) ){
			
			switch( $settings['promo_type'] ){
				
				case 'custom':
					$html = $this->item_custom( $settings , $content );
					break;
				case 'feed':
					$html = $this->item_feed( $settings );
					break;
				case 'select':
					$html = $this->item_select( $settings );
					break;
				case 'remote_feed':
					$html = $this->item_remote( $settings );
					break;
			} // end switch
			
		} // end if 
		
		if ( $html ){
			
			$class = ( ! empty( $settings['csshook'] ) ) ? $settings['csshook'] : '';
			
			$html = '<div class="cpb-item cpb-promo-wrap ' . $class . '">' . $html . '</div>';
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	protected function item_custom( $settings , $content ){
		
		$item = array();
		
		if ( ! empty( $settings['img_src'] )) $item['img'] = $settings['img_src'];
		
		if ( ! empty( $settings['img_id'] )) {
			
			$image_array = cpb_get_image_properties_array( $settings['img_id'] );
			
			$item['img_alt'] = $image_array['alt'];
		
		} // End if
		
		if ( ! empty( $settings['promo_title'] )) $item['title'] = $settings['promo_title'];
		
		if ( ! empty( $settings['subtitle'] )) $item['subtitle'] = $settings['subtitle'];
		
		if ( ! empty( $settings['excerpt'] )) $item['excerpt'] =   $settings['excerpt'];
		
		//if ( ! empty( $settings['excerpt'] )) $item['excerpt'] = $settings['excerpt'];
		
		if ( ! empty( $settings['link'] )) $item['link'] = $settings['link'];
		
		$this->items = array( $item );
		
		return $this->get_promo_display( $item , $settings );
		
	} // end item_custom
	
	protected function item_feed( $settings ){
		
		$html = '';
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_local_items( $settings , '' , $this->get_fields() );
		
		if ( $items ){
			
			foreach( $items as $post_id => $item ){
				
				$html .= $this->get_promo_display( $item , $settings , $post_id );
				
			} // end foreach
			
		} // end if
		
		$this->items = $items;
		
		return $html;
		
	} // end item_feed
	
	protected function item_select( $settings ){
		
		$html = '';
		
		$ids = explode(',', $settings['post_ids'] );
		
		foreach( $ids as $post_id ){
			
			$item = cpb_get_post_item( $post_id, 'medium' );
			
			$html .= $this->get_promo_display( $item , $settings );
			
		} // End foreach
		
		return $html;
		
	} // end item_select
	
	protected function item_remote( $settings ){
		
		$html = '';
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_remote_items_feed( $settings , '' , $this->get_fields() );
		
		if ( $items ){
			
			foreach( $items as $item ){
				
				$html .= $this->get_promo_display( $item , $settings );
				
			} // end foreach
			
		} // end if
		
		$this->items = $items;
		
		return $html;
		
	}
	
	protected function get_promo_display( $item , $settings , $post_id = false ){
		
		$item = $this->check_advanced_display( $item , $settings );
		
		$class = 'cpb-item cpb-promo cpb-promo-' . $settings['promo_type'];
		
		if ( ! empty( $settings['as_lightbox'] ) ){
			
			$class .= ' as-lightbox';
			
			$request_url = $item['link'] . '?cpb-get-template=lightbox';
			
		} else {
			
			$request_url = '';
			
		} // end if
		
		$html = '';
		
		if ( $item ){
			
			if ( ! empty( $item['img'] ) ) $class .= ' has-image';
			
			if ( ! empty( $settings['stack_vertical'] ) ) $class .= ' stack-vertical';
			
			if ( $settings['promo_type'] != 'custom' ) {
				
				$item['excerpt'] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( $item['excerpt'] , true ) ) , 35 , '...' );
				
			} // End if
			
			ob_start();
			
			include cpb_plugin_dir( 'lib/displays/promo/basic.min.php' );
			
			$html .= ob_get_clean();
			
			/*$html .= '<div class="' . $class . '" data-requesturl="' . $request_url . '">';
			
				if ( ! empty( $item['img'] ) ) {
					
					$style = 'background-image:url(' . $item['img'] . ');background-position:center center;background-size:cover;';
					
					$html .= '<div class="cpb-image"><img style="' . $style . '" src="' . plugins_url( 'images/' . $settings['img_ratio'] . '.gif', dirname(__FILE__) ) . '" ';
					
					if ( ! empty( $item['img_alt'] ) ) {
						
						$html .= 'alt="' . $item['img_alt'] . '"';
						
					} // End if
					
					$html .= ' /></div>';
					
				} // end if
				
				if ( ! empty( $item['title'] ) ){
					
					$html .= '<' . $settings['tag'] . ' class="cpb-title">' . $item['title'] . '</' . $settings['tag'] . '>';
					
				} // end if
				
				if ( ! empty( $item['subtitle'] ) ){
					
					$html .= '<div class="cpb-subtitle">' . $item['subtitle'] . '</div>';
					
				} // end if
				
				if ( ! empty( $item['excerpt'] ) ){
					
					if ( $settings['promo_type'] != 'custom' ) $item['excerpt'] = wp_trim_words( strip_shortcodes( wp_strip_all_tags( $item['excerpt'] , true ) ) , 35 , '...' );
					
					$html .= '<div class="cpb-copy">' . $item['excerpt'] . '</div>';
					
				} // end if
			
				if ( ! empty( $item['link'] ) ){
					
					$html .= '<div class="cpb-promo-link"><a href="' . $item['link'] . '" >Visit ' . $item['title'] . '</a></div>';
					
				} // End if
			
			$html .= '</div>';*/
			
		} // end if
		
		return $html;
		
	} // end 
	
	
	public function form( $settings , $content ){
		
		$custom_form = array(
			'name'    => $this->get_input_name( 'promo_type' ),
			'value'   => 'custom',
			'selected' => $settings['promo_type'],
			'title'   => 'Custom',
			'desc'    => 'Add your own image & text',
			'form'    => $this->form_custom( $settings ),
			);
		
		$select_form = array(
			'name'    => $this->get_input_name( 'promo_type' ),
			'value'   => 'select',
			'selected' => $settings['promo_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			//'form'    => $this->form_fields->get_form_select_post( $this->get_input_name() , $settings ),
			'form'    => $this->form_fields->get_insert_posts_field( $this->get_input_name(), $settings, array(), 'Select Content' ),
			);
		
		$feed_form = array(
			'name'    => $this->get_input_name( 'promo_type' ),
			'value'   => 'feed',
			'selected' => $settings['promo_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
			
		$remote_feed_form = array(
			'name'    => $this->get_input_name( 'promo_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['promo_type'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Load external content by category or tag',
			'form'    => $this->form_fields->get_form_remote_feed( $this->get_input_name() , $settings ),
			);
		
		$html = $this->form_fields->multi_form( array( $custom_form , $select_form , $feed_form , $remote_feed_form ) );
		
		$display = $this->form_fields->checkbox_field( $this->get_input_name('stack_vertical'), 1, $settings['stack_vertical'], 'Stack Vertical' );
		
		
		$tags = $this->form_fields->get_header_tags();
		unset( $tags['strong'] );
		$display .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $tags , 'Tag Type' ); 
		
		$img_ratio = array( 'spacer1x1' => 'Square', 'spacer3x4' => '3 x 4 ratio' , 'spacer4x3' => '4 x 3 ratio' );
		$display .= $this->form_fields->select_field( $this->get_input_name('img_ratio') , $settings['img_ratio'] , $img_ratio , 'Image Ratio' ); 
		
		$display .= '<hr/>';
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_img'), 1, $settings['unset_img'], 'Hide Image' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_title'), 1, $settings['unset_title'], 'Hide Title' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_excerpt'), 1, $settings['unset_excerpt'], 'Hide Summary' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_link'), 1, $settings['unset_link'], 'Remove Link' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('as_lightbox'), 1, $settings['as_lightbox'], 'Display Lightbox' );
		
		$adv = $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' ); 
		
		return array( 'Source' => $html , 'Display' => $display , 'Advanced' => $adv );
		
	} // end form
	
	protected function form_custom( $settings ){
		
		$form = '<div class="cpb-form-third">';
		
			$form .= $this->form_fields->insert_media( $this->get_input_name(), $settings );
		
		$form .= '</div>';
		
		$form .= '<div class="cpb-form-two-thirds">';
		
			$form .= $this->form_fields->text_field( $this->get_input_name('promo_title'), $settings['promo_title'], 'Title' , 'cpb-full-width' );
			
			$form .= $this->form_fields->textarea_field( $this->get_input_name('excerpt'), $settings['excerpt'], 'Summary/Text' , 'cpb-full-width' );
			
			$form .= $this->form_fields->text_field( $this->get_input_name('link'), $settings['link'], 'Link' , 'cpb-full-width' );
		
		$form .= '</div>';
		
		return $form;
		
	}
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		if ( ! empty( $settings['promo_type'] ) ){
			
			$clean['promo_type'] = ( ! empty( $settings['promo_type'] ) ) ? sanitize_text_field( $settings['promo_type'] ) : '';
			
			switch( $settings['promo_type'] ){
				
				case 'custom':
				
					$clean['img_src'] = ( ! empty( $settings['img_src'] ) ) ? sanitize_text_field( $settings['img_src'] ) : '';

					$clean['img_id'] = ( ! empty( $settings['img_id'] ) ) ? sanitize_text_field( $settings['img_id'] ) : '';

					$clean['link'] = ( ! empty( $settings['link'] ) ) ? sanitize_text_field( $settings['link'] ) : '';
					
					$clean['promo_title'] = ( ! empty( $settings['promo_title'] ) ) ?  sanitize_text_field( $settings['promo_title'] )  : '';
					
					$clean['subtitle'] = ( ! empty( $settings['subtitle'] ) ) ?  sanitize_text_field( $settings['subtitle'] )  : '';
					
					$clean['excerpt'] = ( ! empty( $settings['excerpt'] ) ) ?   wp_kses_post( str_replace( '&quot;', '"' , $settings['excerpt'] ) )  : '';
					
					break;
				
				case 'feed':
				
					$form_clean = $this->form_fields->get_form_local_query_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
				//case 'select':
				
					//$form_clean = $this->form_fields->get_form_select_post_clean( $settings );
				
					//$clean = array_merge( $clean , $form_clean );
					
					//break;
				
				case 'remote_feed':
				
					$form_clean = $this->form_fields->get_form_remote_feed_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
					
			} // end switch
		
		} // end if
		
		$clean['columns'] = ( ! empty( $settings['columns'] ) ) ? sanitize_text_field( $settings['columns'] ) : 4;
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : 'h2';
		
		$clean['img_ratio'] = ( ! empty( $settings['img_ratio'] ) ) ? sanitize_text_field( $settings['img_ratio'] ) : 'spacer1x1';
		
		$clean['unset_excerpt'] = ( ! empty( $settings['unset_excerpt'] ) ) ? sanitize_text_field( $settings['unset_excerpt'] ) : 0;
		
		$clean['unset_title'] = ( ! empty( $settings['unset_title'] ) ) ? sanitize_text_field( $settings['unset_title'] ) : 0;
		
		$clean['unset_img'] = ( ! empty( $settings['unset_img'] ) ) ? sanitize_text_field( $settings['unset_img'] ) : 0;
		
		$clean['unset_link'] = ( ! empty( $settings['unset_link'] ) ) ? sanitize_text_field( $settings['unset_link'] ) : 0;
		
		$clean['stack_vertical'] = ( ! empty( $settings['stack_vertical'] ) ) ? sanitize_text_field( $settings['stack_vertical'] ) : 0;
		
		$clean['as_lightbox'] = ( ! empty( $settings['as_lightbox'] ) ) ? sanitize_text_field( $settings['as_lightbox'] ) : 0;
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';
		
		$clean = array_merge( $clean, $this->form_fields->get_insert_posts_field_clean( $settings ) );

		return $clean;
		
	}
	
	/*protected function css() {
		
		$style .= '.cpb-promo-wrap {padding-bottom: 1rem;}';
		
		$style .= '.cpb-promo {padding: 1rem 0;}';
		
		$style .= '.cpb-promo:after {content:"";clear:both;display:block;}';
		
		$style .= '.cpb-promo .cpb-image { width: 160px;height:auto;display:block;float:left;} ';
		
		$style .= '.cpb-promo .cpb-image img { width:100%;height:auto;display:block;} ';
		
		$style .= '.cpb-promo.has-image .cpb-title, .cpb-promo.has-image .cpb-copy { margin-left: 175px;}';
		
		$style .= '.cpb-promo .cpb-title { margin-top: 0; margin-bottom: 0.5rem;}';
		
		$style .= '.cpb-promo.stack-vertical .cpb-image { width: auto;float:none;}';
		
		$style .= '.cpb-promo.stack-vertical.has-image .cpb-title, .cpb-promo.stack-vertical.has-image .cpb-copy { margin-left: 0;}';
		
		return $style;
		
	}*/
	
	
}