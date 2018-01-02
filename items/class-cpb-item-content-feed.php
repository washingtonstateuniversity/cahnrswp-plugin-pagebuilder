<?php

class CPB_Item_Content_Feed extends CPB_Item {
	
	protected $name = 'Content Feed';
	
	protected $slug = 'content_feed';
	
	protected $items = array();
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		$items = array();
		
		if ( ! empty( $settings['feed_type'] ) ){
			
			switch( $settings['feed_type'] ){
				
				case 'feed':
					$items = $this->get_items_feed( $settings );
					break;

			} // end switch
			
		} // end if 
		
		switch( $settings['display'] ){
				
			case 'accordion':
				$html .= $this->get_accordion_display( $items, $settings );
				break;
			case 'list': 
				$html .= $this->get_list_display( $items, $settings );
				
		} // End switch
		
		if ( $html ){
			
			$class = ( ! empty( $settings['csshook'] ) ) ? $settings['csshook'] : '';
			
			$html = '<div class="cpb-item cpb-content-feed-wrap ' . $class . '">' . $html . '</div>';
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	protected function get_accordion_display( $items, $settings ){
		
		$html = '';
		
		$tag_start = ( ! empty( $settings['tag'] ) )? '<' . $settings['tag'] . '>' : '';
		
		$tag_end = ( ! empty( $settings['tag'] ) )? '</' . $settings['tag'] . '>' : '';
		
		foreach( $items as $item ){
			
			$html .= '<dl class="cpb-faq cpb-accordion">';
		
				$html .= '<dt>' . $tag_start . $item['title'] . $tag_end . '</dt>';
			
  				$html .= '<dd>' . $item['content'] . '</dd>';
		
			$html .= '</dl>';
			
		} // End foreach
		
		return $html;
		
	} // End get_accordion_display
	
	
	protected function get_list_display( $items, $settings ){
		
		$html = '<ul class="cpb-post-list">';
		
		$tag_start = ( ! empty( $settings['tag'] ) )? '<' . $settings['tag'] . ' class="cpb-title">' : '';
		
		$tag_end = ( ! empty( $settings['tag'] ) )? '</' . $settings['tag'] . '>' : '';
		
		foreach( $items as $item ){
			
			$link_class = ( ! empty( $item['link'] ) )? 'has-link' : '';
			
			$html .= '<li class="cpb-post-list-item ' . $link_class . '">';
		
				$html .= $tag_start . $item['title'] . $tag_end ;
			
  				$html .= '<span class="excerpt">' . $item['excerpt'] . '</span>';
			
				if ( ! empty( $item['link'] ) ) {
					
					$html .= '<a class="cpb-link" href="' . $item['link'] . '" >Visit '.  $item['title'] . '</a>';
					
				} // End if
		
			$html .= '</li>';
			
		} // End foreach
		
		$html .= '</ul>';
		
		return $html;
		
	} // End get_accordion_display
	
	
	protected function get_items_feed( $settings ){
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-query.php';
		
		$query = new CPB_Query();
		
		$items = $query->get_local_items( $settings );
		
		return $items;
		
	} // End get_items_feed
	
	
	public function form( $settings , $content ){
		
		$displays = array(
			'list' => 'List',
			'accordion' => 'Accordion',
		);
		
		$feed_form = array(
			'name'    => $this->get_input_name( 'feed_type' ),
			'value'   => 'feed',
			'selected' => $settings['feed_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
		
		$html = $this->form_fields->multi_form( array( $feed_form ) );
		
		
		$tags = $this->form_fields->get_header_tags();
		
		$display .= $this->form_fields->select_field( $this->get_input_name('display') , $settings['display'] , $displays , 'Display As' ); 
		
		$display .= $this->form_fields->select_field( $this->get_input_name('tag') , $settings['tag'] , $tags , 'Tag Type' ); 
		
		$display .= '<hr/>';
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_title'), 1, $settings['unset_title'], 'Hide Title' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_excerpt'), 1, $settings['unset_excerpt'], 'Hide Summary' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('unset_link'), 1, $settings['unset_link'], 'Remove Link' );
		
		$display .= $this->form_fields->checkbox_field( $this->get_input_name('as_lightbox'), 1, $settings['as_lightbox'], 'Display Lightbox' );
		
		$adv = $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' ); 
		
		return array( 'Source' => $html , 'Display' => $display , 'Advanced' => $adv );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		if ( ! empty( $settings['feed_type'] ) ){
			
			$clean['feed_type'] = ( ! empty( $settings['feed_type'] ) ) ? sanitize_text_field( $settings['feed_type'] ) : '';
			
			switch( $settings['feed_type'] ){
				
				case 'feed':
				
					$form_clean = $this->form_fields->get_form_local_query_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
					
			} // end switch
		
		} // end if
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) ) ? sanitize_text_field( $settings['tag'] ) : 'h2';
		
		$clean['display'] = ( ! empty( $settings['display'] ) ) ? sanitize_text_field( $settings['display'] ) : '';
		
		$clean['unset_excerpt'] = ( ! empty( $settings['unset_excerpt'] ) ) ? sanitize_text_field( $settings['unset_excerpt'] ) : 0;
		
		$clean['unset_title'] = ( ! empty( $settings['unset_title'] ) ) ? sanitize_text_field( $settings['unset_title'] ) : 0;
		
		$clean['unset_link'] = ( ! empty( $settings['unset_link'] ) ) ? sanitize_text_field( $settings['unset_link'] ) : 0;
		
		$clean['as_lightbox'] = ( ! empty( $settings['as_lightbox'] ) ) ? sanitize_text_field( $settings['as_lightbox'] ) : 0;
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';

		return $clean;
		
	}
	
}