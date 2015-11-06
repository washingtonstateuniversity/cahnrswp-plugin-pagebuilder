<?php
class Item_List_PB extends Item_PB {
	
	public $slug = 'list';
	
	public $name = 'List';
	
	public $desc = 'Adds a List of Posts, Pages, etc...';
	
	public $form_size = 'medium';
	
	public function item( $settings , $content ){
		
		$html .= '';
		
		if ( ! empty( $settings['title'] ) ){
			
			$html .= '<' . $settings['tag'] . '>' . $settings['title'] . '</' . $settings['tag'] . '>';
			
		} // end if
		
		$query = new Query_PB( $settings );
		
		$display = new Display_PB( $settings );
			
		$items = $query->get_query_items();
		
		$html .= $display->get_display( $items , $settings );
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';
		
		$html = '<h2>' . $title . '</h2>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$feed_source = array(
			'form'        => Forms_PB::local_feed( $this->get_name_field() , $settings ),
			'field_name'  => $this->get_name_field('source'),
			'val'         => 'feed',
			'current_val' => $settings['source'],
			'title'       => 'Feed (This Site)',
			'summary'     => 'I want to dynamically include other posts/pages from this site based on categories, tages, or content type.'
		);
		
		$ext_feed_source = array(
			'form'        => Forms_PB::remote_feed( $this->get_name_field() , $settings ),
			'field_name'  => $this->get_name_field('source'),
			'val'         => 'remote_feed',
			'current_val' => $settings['source'],
			'title'       => 'Feed (Another Site)',
			'summary'     => 'I want to dynamically include posts/pages from another site.'
		);
		
		$source .= Forms_PB::get_subform( $feed_source ); 
		
		$source .= Forms_PB::get_subform( $ext_feed_source ); 
		
		$display = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$display .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , array('h2' => 'H2','h3'=>'H3','h4'=>'H4' ) , 'Tag Type' );
		
		$display .= '<hr />';
		
		$display .= Forms_PB::select_field( $this->get_name_field('headline_tag') , $settings['headline_tag'] , array('h2' => 'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5' ,'strong' => 'Bold' , 'span' => 'None' ) , 'Headline Tag' );
		
		$display .= Forms_PB::checkbox_field( $this->get_name_field('display_accordion'), 1, $settings['display_accordion'], 'Display as Accordion' );
		
		$display .= '<hr />';
		
		$display .= Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' );
		
		$adv = Forms_PB::checkbox_field( $this->get_name_field('hide_excerpt'), 1, $settings['hide_excerpt'], 'Hide Summary' );
		
		$adv .= Forms_PB::checkbox_field( $this->get_name_field('hide_link'), 1, $settings['hide_link'], 'Remove Link' );
		
		$adv .= Forms_PB::checkbox_field( $this->get_name_field('bg_image'), 1, $settings['bg_image'], 'Image as Background','','***Not supported by all displays.' );
		
		$form = array( 
			'Source' => $source,
			'Display' => $display,
			'Advanced' => $adv,
		); 
		
		return $form; 
		
	} // end form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		/*$clean['tag'] = ( ! empty( $s['tag'] ) )? sanitize_text_field( $s['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $s['title'] ) )? sanitize_text_field( $s['title'] ) : '';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';*/
		
		
		$clean['display_type'] = 'list';
		
		$clean['img_size'] = 'large';
		
		if ( ! empty( $s['display_accordion'] ) ){
			
			$clean['display_accordion'] = sanitize_text_field( $s['display_accordion'] );
			
			$clean['display_type'] = 'accordion';
			
		} else {
			
			$clean['display_accordion'] = '';
			
		}// end if
		
		$clean['title'] = ( ! empty( $s['title'] ) ) ? $s['title'] : '';
		
		$clean['tag'] = ( ! empty( $s['tag'] ) ) ? $s['tag'] : 'h2';
		
		$clean['headline_tag'] = ( ! empty( $s['headline_tag'] ) ) ? $s['headline_tag'] : 'h5';
		
		$clean['hide_excerpt'] = ( ! empty( $s['hide_excerpt'] ) ) ? $s['hide_excerpt'] : '';
		
		$clean['hide_link'] = ( ! empty( $s['hide_link'] ) ) ? $s['hide_link'] : '';
		
		$clean['posts_per_page'] =  ( ! empty( $s['posts_per_page'] ) ) ? sanitize_text_field( $s['posts_per_page'] ) : 5;
		
		$clean['display_accordion'] =  ( ! empty( $s['display_accordion'] ) ) ? sanitize_text_field( $s['display_accordion'] ) : '';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) ) ? sanitize_text_field( $s['csshook'] ) : '';
		
		$clean['bg_image'] = ( ! empty( $s['bg_image'] ) ) ? sanitize_text_field( $s['bg_image'] ) : '';
		
		if ( ! empty( $s['source'] ) ) $clean['source'] = sanitize_text_field( $s['source'] );
		
		if ( ! empty( $s['post_type'] ) ) $clean['post_type'] = sanitize_text_field( $s['post_type'] );
		
		if ( ! empty( $s['taxonomy'] ) ) $clean['taxonomy'] = sanitize_text_field( $s['taxonomy'] );
		
		if ( ! empty( $s['terms'] ) ) $clean['terms'] = sanitize_text_field( $s['terms'] );
		
		if ( isset( $s['term_operator'] ) ) $clean['term_operator'] = sanitize_text_field( $s['term_operator'] );
			
		if ( ! empty( $s['remote_url'] ) ) $clean['remote_url'] = sanitize_text_field( $s['remote_url'] );
		
		if ( ! empty( $s['remote_post_type'] ) ) $clean['remote_post_type'] = sanitize_text_field( $s['remote_post_type'] );
		
		if ( ! empty( $s['remote_taxonomy'] ) ) $clean['remote_taxonomy'] = sanitize_text_field( $s['remote_taxonomy'] );

		if ( ! empty( $s['remote_terms'] ) ) $clean['remote_terms'] = sanitize_text_field( $s['remote_terms'] );
		
		$clean['remote_posts_per_page'] = ( ! empty( $s['remote_posts_per_page'] ) ) ? sanitize_text_field( $s['remote_posts_per_page'] ) : 5;
		
		return $clean;
		
	} // end clean
	
}