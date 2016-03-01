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
		
		return $html;
		
	}// end item
	
	
	protected function item_custom( $settings , $content ){
		
		$item = array();
		
		if ( ! empty( $settings['img_src'] )) $item['img'] = $settings['img_src'];
		
		if ( ! empty( $settings['promo_title'] )) $item['title'] = $settings['promo_title'];
		
		if ( ! empty( $settings['excerpt'] )) $item['excerpt'] = $settings['excerpt'];
		
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
			
			foreach( $items as $item ){
				
				$html .= $this->get_promo_display( $item , $settings );
				
			} // end foreach
			
		} // end if
		
		$this->items = $items;
		
		return $html;
		
	} // end item_feed
	
	protected function item_select( $settings ){
		
		
		$html = '';
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_remote_items( $settings , '' , $this->get_fields() );
		
		if ( $items ){
			
			foreach( $items as $item ){
				
				$html .= $this->get_promo_display( $item , $settings );
				
			} // end foreach
			
		} // end if
		
		$this->items = $items;
		
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
	
	protected function get_promo_display( $item , $settings){
		
		$item = $this->check_advanced_display( $item , $settings );
		
		$html = '';
		
		$ls = ( ! empty( $item['link'] ) ) ? '<a href="' . $ls . '" >' : '';
		
		$le = ( ! empty( $item['link'] ) ) ? '</a>' : '';
		
		if ( $item ){
			
			$class = 'cpb-promo cpb-promo-' . $settings['promo_type'];
			
			if ( ! empty( $item['img'] ) ) $class .= ' has-image';
			
			$html .= '<article class="' . $class . '">';
			
				if ( ! empty( $item['img'] ) ) {
					
					$style = 'background-image:url(' . $item['img'] . ');background-position:center center;background-size:cover;';
					
					$html .= $ls . '<img style="' . $style . '" src="' . plugins_url( 'images/spacer3x4.gif', dirname(__FILE__) ) . '" />' . $le ;
					
				} // end if
				
				if ( ! empty( $item['title'] ) ){
					
					$html .= '<' . $settings['tag'] . ' class="cpb-title">' . $ls . $item['title'] . $le . '</' . $settings['tag'] . '>';
					
				} // end if
				
				if ( ! empty( $item['excerpt'] ) ){
					
					$html .= '<div class="cpb-copy">' . wp_trim_words( strip_shortcodes( wp_strip_all_tags( $item['excerpt'] , true ) ) , 35 , '...' ) . '</div>';
					
				} // end if
			
			$html .= '</article>';
			
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
			'form'    => $this->form_fields->get_form_select_post( $this->get_input_name() , $settings ),
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
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('stack_vertical'), 1, $settings['stack_vertical'], 'Stack Vertical' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $this->form_fields->get_header_tags() , 'Tag Type' ); 
		
		$display .= '<hr/>';
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_img'), 1, $settings['unset_img'], 'Hide Image' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_title'), 1, $settings['unset_title'], 'Hide Title' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_excerpt'), 1, $settings['unset_excerpt'], 'Hide Summary' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_link'), 1, $settings['unset_link'], 'Remove Link' );
		
		return array( 'Source' => $html , 'Display' => $display );
		
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
					
					$clean['promo_title'] = ( ! empty( $settings['promo_title'] ) ) ? sanitize_text_field( $settings['promo_title'] ) : '';
					
					$clean['excerpt'] = ( ! empty( $settings['excerpt'] ) ) ? wp_kses_post( $settings['excerpt'] ) : '';
					
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
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : 'strong';
		
		$clean['unset_excerpt'] = ( ! empty( $settings['unset_excerpt'] ) ) ? sanitize_text_field( $settings['unset_excerpt'] ) : 0;
		
		$clean['unset_title'] = ( ! empty( $settings['unset_title'] ) ) ? sanitize_text_field( $settings['unset_title'] ) : 0;
		
		$clean['unset_img'] = ( ! empty( $settings['unset_img'] ) ) ? sanitize_text_field( $settings['unset_img'] ) : 0;
		
		$clean['unset_link'] = ( ! empty( $settings['unset_link'] ) ) ? sanitize_text_field( $settings['unset_link'] ) : 0;
		
		$clean['stack_vertical'] = ( ! empty( $settings['stack_vertical'] ) ) ? sanitize_text_field( $settings['stack_vertical'] ) : 0;

		return $clean;
		
	}
	
	protected function css() {
		
		$style .= '.cpb-promo {padding: 1rem 0; overflow: auto;}';
		
		$style .= '.cpb-promo img { width: 160px;height:auto;display:block;float:left;} ';
		
		$style .= '.cpb-promo.has-image .cpb-title, .cpb-promo.has-image .cpb-copy { margin-left: 175px;}';
		
		$style .= '.cpb-promo .cpb-title { margin-top: 0; margin-bottom: 0.5rem;}';
		
		return $style;
		
	}
	
	
}