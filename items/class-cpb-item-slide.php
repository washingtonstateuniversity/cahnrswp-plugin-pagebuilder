<?php

class CPB_Item_Slide extends CPB_Item {
	
	protected $name = 'Slide';
	
	protected $slug = 'slide';
	
	protected $fields = array('title','img','link','excerpt');
	
	public function get_fields(){ return $this->fields; }
	
	
	
	public function item( $settings , $content , $is_editor = false ){
		
		$html = '';
		
			$items = array();
		
			if ( ! empty( $settings['slide_type'] ) ){
			
				switch( $settings['slide_type'] ){
					
					case 'custom':
						$items = $this->get_items_custom( $settings , $content );
						break;
					case 'feed':
						$items = $this->get_items_feed( $settings );
						break;
					case 'select':
						$items = $this->get_items_select( $settings );
						break;
					case 'remote_feed':
						$items = $this->get_items_remote( $settings );
						break;
				} // end switch
				
			} // end if 
		
			if ( $items ) {
				
				switch( $settings['slide_display'] ){
					
					case 'gallery':
					default:
						$html .= $this->get_gallery_slide_html( $items , $settings );
						break;
					
				} // end switch
				
			} // end if
		
		return $html;
		
	}// end item
	
	
	public function get_gallery_slide_html( $items , $settings ) {
		
		$html = '';
		
		foreach( $items as $item ){
			
			$html .= '<div class="slide gallery-slide">';
			
				$html .= '<div class="slide-image" style="background-image:url(' . $item['img'] . ')">';
				
				$html .= '</div>';
			
			$html .= '</div>';
			
		} // end foreach
		
		return $html;
		
	} // end public
	
	public function item_editor( $settings , $content  ){
			
	  		$html = '';
		
			$items = array();
		
			if ( ! empty( $settings['slide_type'] ) ){
			
				switch( $settings['slide_type'] ){
					
					case 'custom':
						$items = $this->get_items_custom( $settings , $content );
						break;
					case 'feed':
						$items = $this->get_items_feed( $settings );
						break;
					case 'select':
						$items = $this->get_items_select( $settings );
						break;
					case 'remote_feed':
						$items = $this->get_items_remote( $settings );
						break;
				} // end switch
				
			} // end if 
		
			if ( ! $items ) {
				
				$item = array();
				
				$item['title'] = 'Slide Title Here';
		
				$item['excerpt'] = 'Summary text - Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin pulvinar bibendum ornare. In in venenatis lacus. In sodales malesuada enim, lobortis consectetur mauris congue ac.';
				
				$items = array( $item );
				
			} // end if
			
			$html .= $this->get_slide_display( $items , $settings );
		
		return $html;
		
	}// end item
	
	protected function get_slide_display( $items , $settings ){
		
		$html = '';
		
		foreach( $items as $item ){
			
			$html .= '<div class="cpb-slide editor-slide">';
			
				$img = ( ! empty( $item['img'] ) ) ? $item['img'] :  plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) );
				
				$title = ( ! empty( $item['title'] ) ) ? $item['title'] :  'Item Title Here';
					
				$html .= '<img src="' . $img . '" />';
					
				$html .= '<div class="copy"><h4>' . $item['title'] . '</h4>' . $item['excerpt'] . '</div>';
			
			$html .= '</div>';
			
		} // end foreach
		
		return $html;
		
	}
	
	
	protected function get_items_custom( $settings , $content ){
		
		$item = array();
		
		if ( ! empty( $settings['img_src'] )) $item['img'] = $settings['img_src'];
		
		if ( ! empty( $settings['item_title'] )) $item['title'] = $settings['item_title'];
		
		if ( ! empty( $settings['subtitle'] )) $item['subtitle'] = $settings['subtitle'];
		
		if ( ! empty( $settings['excerpt'] )) $item['excerpt'] =   $settings['excerpt'];
		
		//if ( ! empty( $settings['excerpt'] )) $item['excerpt'] = $settings['excerpt'];
		
		if ( ! empty( $settings['link'] )) $item['link'] = $settings['link'];
		
		$items = array( $item );
		
		return $items;
		
	} // end item_custom
	
	protected function get_items_feed( $settings ){
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_local_items( $settings , '' , $this->get_fields() );
		
		return $items;
		
	} // end item_feed
	
	protected function get_items_select( $settings ){
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_remote_items( $settings , '' , $this->get_fields() );
		
		return $items;
		
	} // end item_select
	
	protected function get_items_remote( $settings ){
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_remote_items_feed( $settings , '' , $this->get_fields() );
		
		return $items;
		
	}
	
	
	public function form( $settings , $content ){
		
		$custom_form = array(
			'name'    => $this->get_input_name( 'slide_type' ),
			'value'   => 'custom',
			'selected' => $settings['slide_type'],
			'title'   => 'Custom',
			'desc'    => 'Add your own image & text',
			'form'    => $this->form_custom( $settings ),
			);
		
		$select_form = array(
			'name'    => $this->get_input_name( 'slide_type' ),
			'value'   => 'select',
			'selected' => $settings['slide_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			'form'    => $this->form_fields->get_form_select_post( $this->get_input_name() , $settings ),
			);
		
		$feed_form = array(
			'name'    => $this->get_input_name( 'slide_type' ),
			'value'   => 'feed',
			'selected' => $settings['slide_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
			
		$remote_feed_form = array(
			'name'    => $this->get_input_name( 'slide_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['slide_type'],
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
		
			$form .= $this->form_fields->text_field( $this->get_input_name('item_title'), $settings['item_title'], 'Title' , 'cpb-full-width' );
			
			$form .= $this->form_fields->textarea_field( $this->get_input_name('excerpt'), $settings['excerpt'], 'Summary/Text' , 'cpb-full-width' );
			
			$form .= $this->form_fields->text_field( $this->get_input_name('link'), $settings['link'], 'Link' , 'cpb-full-width' );
		
		$form .= '</div>';
		
		return $form;
		
	}
	
	protected function editor_default_html( $settings , $content ){
		
		$html = '<a class="cpb-action-button" href="#" class="cpb-action-button-item">Action Button</a>';
		
		return $html;
		
	} // end editor_default_html
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		if ( ! empty( $settings['slide_type'] ) ){
			
			$clean['slide_type'] = ( ! empty( $settings['slide_type'] ) ) ? sanitize_text_field( $settings['slide_type'] ) : '';
			
			switch( $settings['slide_type'] ){
				
				case 'custom':
				
					$clean['img_src'] = ( ! empty( $settings['img_src'] ) ) ? sanitize_text_field( $settings['img_src'] ) : '';

					$clean['img_id'] = ( ! empty( $settings['img_id'] ) ) ? sanitize_text_field( $settings['img_id'] ) : '';

					$clean['link'] = ( ! empty( $settings['link'] ) ) ? sanitize_text_field( $settings['link'] ) : '';
					
					$clean['item_title'] = ( ! empty( $settings['item_title'] ) ) ?  sanitize_text_field( $settings['item_title'] )  : '';
					
					$clean['subtitle'] = ( ! empty( $settings['subtitle'] ) ) ?  sanitize_text_field( $settings['subtitle'] )  : '';
					
					$clean['excerpt'] = ( ! empty( $settings['excerpt'] ) ) ?   wp_kses_post( str_replace( '&quot;', '"' , $settings['excerpt'] ) )  : '';
					
					break;
				
				case 'feed':
				
					$form_clean = $this->form_fields->get_form_local_query_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
				case 'select':
				
					$form_clean = $this->form_fields->get_form_select_post_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
				
				case 'remote_feed':
				
					$form_clean = $this->form_fields->get_form_remote_feed_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
					
			} // end switch
		
		} // end if
		
		$clean['columns'] = ( ! empty( $settings['columns'] ) ) ? sanitize_text_field( $settings['columns'] ) : 4;
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : 'h5';
		
		$clean['img_ratio'] = ( ! empty( $settings['img_ratio'] ) ) ? sanitize_text_field( $settings['img_ratio'] ) : 'spacer1x1';
		
		$clean['unset_excerpt'] = ( ! empty( $settings['unset_excerpt'] ) ) ? sanitize_text_field( $settings['unset_excerpt'] ) : 0;
		
		$clean['unset_title'] = ( ! empty( $settings['unset_title'] ) ) ? sanitize_text_field( $settings['unset_title'] ) : 0;
		
		$clean['unset_img'] = ( ! empty( $settings['unset_img'] ) ) ? sanitize_text_field( $settings['unset_img'] ) : 0;
		
		$clean['unset_link'] = ( ! empty( $settings['unset_link'] ) ) ? sanitize_text_field( $settings['unset_link'] ) : 0;
		
		$clean['stack_vertical'] = ( ! empty( $settings['stack_vertical'] ) ) ? sanitize_text_field( $settings['stack_vertical'] ) : 0;
		
		$clean['as_lightbox'] = ( ! empty( $settings['as_lightbox'] ) ) ? sanitize_text_field( $settings['as_lightbox'] ) : 0;
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';

		return $clean;
		
	}
	
	public function css(){
		
		$style = '.cpb-slide.editor-slide {background-color: #ddd; padding: 0.5rem; box-sizing: border-box; border-radius: 3px; margin-bottom: 1rem;}';
		
		$style .= '.cpb-slide.editor-slide:after {content:"";display:block;clear:both;}';
		
		$style .= '.cpb-slide.editor-slide img {width: 120px; height: auto; float: left;display:block; background-color: #555;}';
		
		$style .= '.cpb-slide.editor-slide .copy { margin-left: 135px; color: #555;}';
		
		$style .= '.cpb-slide.editor-slide .copy h4 { margin: 0;padding: 0;}';
		
		$style .= '.cpb-slideshow .slide.gallery-slide {padding-bottom: 56.25%;position: relative;}';
		
		$style .= '.cpb-slideshow .slide.gallery-slide .slide-image {position: absolute;top:0;left:0;width: 100%;height:100%;background-size:cover;background-position: center center;}';
		
		$style .= '@media (max-width:400px){';
		
			$style .= '.cpb-slide.editor-slide img {width: 100%; height: auto; float: none; }';
			
			$style .= '.cpb-slide.editor-slide .copy { margin-left: 0;}';
			
			$style .= '.cpb-slide.editor-slide .copy { font-size: 0;}';
			
			$style .= '.cpb-slide.editor-slide .copy  h4 { font-size: 0.7rem; padding: 0.5rem 0;}';
		
		$style .= '}';
		
		return $style;
		
	} // end admin_css
	
	
}