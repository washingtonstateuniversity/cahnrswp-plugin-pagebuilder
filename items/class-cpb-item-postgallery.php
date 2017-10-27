<?php

class CPB_Item_Postgallery extends CPB_Item {
	
	protected $name = 'Post Gallery';
	
	protected $slug = 'postgallery';
	
	protected $fields = array('title','img','link','excerpt');
	
	public function get_fields(){ return $this->fields; }
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['source_type'] ) ){
		
			require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
			
			$query = new CPB_Query();
			
			switch( $settings['source_type'] ){
				
				case 'feed':
					$items = $query->get_local_items( $settings , '' , $this->get_fields() );
					break;
				case 'remote_feed':
					$items = $query->get_remote_items_feed( $settings , '' , $this->get_fields() );
					break;
					
				default:
					$items = array();
					break;
			
			} // end switch
			
			if ( $items ){
				
				$html .= '<div class="cpb-gallery-wrapper">';
				
				foreach( $items as $item ){
					
					$html .= $this->get_item_display( $item , $settings );
					
				} // end foreach
				
				$html .= '</div>';
				
			} // end if
			
		} // end if
		
		return $html;
		
	}// end item
	
	protected function get_item_display( $item , $settings){
		
		$item = $this->check_advanced_display( $item , $settings );
		
		$html = '';
		
		$ls = ( ! empty( $item['link'] ) ) ? '<a href="' . $item['link'] . '" >' : '';
		
		$le = ( ! empty( $item['link'] ) ) ? '</a>' : '';
		
		if ( $item ){
			
			$class = 'cpb-gallery cpb-gallery-columns-' . $settings['columns'] . ' cpb-gallery-' . $settings['promo_type'];
			
			if ( ! empty( $item['img'] ) ) $class .= ' has-image';
			
			$html .= '<div class="' . $class . '">';
			
				$html .= '<div class="cpb-gallery-inner">';
			
					if ( ! empty( $item['img'] ) ) {
						
						$html .= '<div class="cpb-image" style="background-image:url(' . $item['img'] . ');background-position:center center;background-size:cover;">';
						
						$html .= '</div>';
						
					} // end if
					
					$html .= '<div class="cpb-caption">';
					
					if ( ! empty( $item['title'] ) ){
						
						$html .= '<' . $settings['tag'] . ' class="cpb-title">' . $item['title'] . '</' . $settings['tag'] . '>';
						
					} // end if
					
					if ( ! empty( $item['excerpt'] ) ){
						
						$html .= '<div class="cpb-excerpt">' . strip_shortcodes( wp_strip_all_tags( $item['excerpt'] , true ) ) . '</div>';
						
					} // end if
					
					$html .= '</div>';
			
				
				$html .= '</div>';
			
			$html .= '<div class="item-link">' . $ls . $item['title'] . $le . '</div>';
			
			$html .= '</div>';
			
		} // end if
		
		return $html;
		
	} // end 
	
	
	public function form( $settings , $content ){
		
		$select_form = array(
			'name'    => $this->get_input_name( 'source_type' ),
			'value'   => 'select',
			'selected' => $settings['source_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			'form'    => $this->form_fields->get_form_select_post( $this->get_input_name() , $settings ),
			);
		
		$feed_form = array(
			'name'    => $this->get_input_name( 'source_type' ),
			'value'   => 'feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
			
		$remote_feed_form = array(
			'name'    => $this->get_input_name( 'source_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Load external content by category or tag',
			'form'    => $this->form_fields->get_form_remote_feed( $this->get_input_name() , $settings ),
			);
			
		$display = $this->form_fields->select_field( $this->get_input_name('columns') , $settings['columns'] , array( 1 => 1, 2 => 2,3 => 3, 4 => 4, 5 => 5 ) , 'Columns' );
		
		
		$excerpt_length = array( 'short' => 'Short' , 'medium' => 'Medium' , 'long' => 'Long' , 'full' => 'Full' );
		$display .= $this->form_fields->select_field( $this->get_input_name('excerpt_length') , $settings['excerpt_length'] , $excerpt_length , 'Summary Length' );
		
		$tags = $this->form_fields->get_header_tags();
		unset( $tags['strong'] );
		$display .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $tags , 'Tag Type' ); 
		
		$display .= '<hr/>';
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_img'), 1, $settings['unset_img'], 'Hide Image' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_title'), 1, $settings['unset_title'], 'Hide Title' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_excerpt'), 1, $settings['unset_excerpt'], 'Hide Summary' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_link'), 1, $settings['unset_link'], 'Remove Link' );
		
		$html = $this->form_fields->multi_form( array( $select_form , $feed_form , $remote_feed_form ) );
		
		return array( 'Source' => $html , 'Display' => $display );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		if ( ! empty( $settings['source_type'] ) ){
			
			$clean['source_type'] = ( ! empty( $settings['source_type'] ) ) ? sanitize_text_field( $settings['source_type'] ) : '';
			
			switch( $settings['source_type'] ){
				
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
		
		$clean['unset_excerpt'] = ( ! empty( $settings['unset_excerpt'] ) ) ? sanitize_text_field( $settings['unset_excerpt'] ) : 0;
		
		$clean['unset_title'] = ( ! empty( $settings['unset_title'] ) ) ? sanitize_text_field( $settings['unset_title'] ) : 0;
		
		$clean['unset_img'] = ( ! empty( $settings['unset_img'] ) ) ? sanitize_text_field( $settings['unset_img'] ) : 0;
		
		$clean['unset_link'] = ( ! empty( $settings['unset_link'] ) ) ? sanitize_text_field( $settings['unset_link'] ) : 0;
		
		$clean['excerpt_length'] = ( ! empty( $settings['excerpt_length'] ) ) ? sanitize_text_field( $settings['excerpt_length'] ) : 'medium';
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : 'h3';

		return $clean;
		
	} // end clean
	
	protected function css() {
		
		$style .= '.cpb-gallery-wrapper {margin: 0 -1rem;} ';
		
		$style .= '.cpb-gallery {display: inline-block;vertical-align:top;margin:0 0 0.75rem;padding:0;} ';
		
		$style .= '.cpb-gallery-columns-1 {width: 100%;} ';
		
		$style .= '.cpb-gallery-columns-2 {width: 50%;} ';
		
		$style .= '.cpb-gallery-columns-3 {width: 33.33%;} ';
		
		$style .= '.cpb-gallery-columns-4 {width: 25%;} ';
		
		$style .= '.cpb-gallery-columns-5 {width: 20%;} ';
		
		$style .= '.cpb-gallery .cpb-gallery-inner {margin: 0 0.75rem;} ';
		
		$style .= '.cpb-gallery .cpb-gallery-inner a > img, .cpb-gallery .cpb-gallery-inner  img {display:block;width:100%;} ';
		
		$style .= '.cpb-gallery .cpb-gallery-inner .cpb-image {padding-bottom: 75%;} ';
		
		$style .= '@media only screen and (max-width: 850px) { .cpb-gallery-columns-4 { width: 50%;} }';
		
		$style .= '@media only screen and (max-width: 450px) { .cpb-gallery-columns-4 { width: auto; display: block;} }';
		
		return $style;
		
	}
	
	
}