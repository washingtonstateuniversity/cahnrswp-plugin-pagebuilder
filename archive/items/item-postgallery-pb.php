<?php
class Item_Postgallery_PB extends Item_PB {
	
	public $slug = 'postgallery';
	
	public $name = 'Post Gallery';
	
	public $desc = 'Add gallery of posts/pages';
	
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
		
		return apply_filters( 'cpb_return_postgallery' , $html , $items , $settings );
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		/*$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';
		
		$html = '<h2>' . $title . '</h2>';*/
		
		$html = $this->get_dynamic_editor( $this->the_item() );
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		/*$feed_source = array(
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
		);*/
		
		$source .= $this->accordion_radio( 
			$this->get_name_field('source') , 
			'feed' , 
			$settings['source'] , 
			'Feed (This Site)' , 
			Forms_PB::local_feed( $this->get_name_field() , $settings ),
			'I want to dynamically include other posts/pages from this site based on categories, tages, or content type.' 
			); 
			
		$source .= $this->accordion_radio( 
			$this->get_name_field('source') , 
			'remote_feed' , 
			$settings['source'] , 
			'Feed (Another Site)' , 
			Forms_PB::remote_feed( $this->get_name_field() , $settings ),
			'I want to dynamically include posts/pages from another site.' 
			); 
		
		//$source .= Forms_PB::get_subform( $ext_feed_source ); 
		
		$display = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$display .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , array('h2' => 'H2','h3'=>'H3','h4'=>'H4' ) , 'Tag Type' );
		
		$display .= Forms_PB::checkbox_field( $this->get_name_field('hide_excerpt'), 1, $settings['hide_excerpt'], 'Hide Summary' );
		
		$display .= Forms_PB::checkbox_field( $this->get_name_field('hide_image'), 1, $settings['hide_image'], 'Hide Image' );
		
		$display .= Forms_PB::checkbox_field( $this->get_name_field('hide_link'), 1, $settings['hide_link'], 'Remove Link' );
		
		$form = array( 
			'Source' => $source,
			'Display Style' => $display,
		);
		
		
		/*$source = array(
			'Basic Feed' => array(
				'form' => $this->get_basic_feed( $settings ),
				'value' => 'feed',
			),
			'External Feed' => array(
				'form' => $this->get_remote_feed( $settings ),
				'value' => 'external',
			),
		);
		
		$form = array(
			'Source' => Forms_PB::get_sub_form( $source , $this->get_name_field('feed_source') , $settings['feed_source'] ),
			'Display Style' => $this->get_display_form( $settings ),
		);
		
		/*$tags = array(
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
		);
		
		$html = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$html .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , $tags , 'Tag Type' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' );*/ 
		
		return $form; 
		
	} // end form
	
	
	public function get_basic_feed( $settings ){
		
		$p_types = get_post_types();
		
		$post_types = array( 'any' => 'Any' );
		
		foreach( $p_types as $type ){
			
			$post_types[ $type ] = ucfirst( $type );
			
		} // end foreach
		
		$taxonomies = array(
			'post_tag' => 'Tags',
			'category' => 'Categories',
		);
		
		$html = Forms_PB::select_field( $this->get_name_field('post_type') , $settings['post_type'] , $post_types , 'Content Type' );
		
		$html .= Forms_PB::select_field( $this->get_name_field('taxonomy') , $settings['taxonomy'] , $taxonomies , 'Type' ); 
		
		$html .= Forms_PB::text_field( $this->get_name_field('tax_terms') , $settings['tax_terms'] , 'Terms' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('posts_per_page') , $settings['posts_per_page'] , 'Count' );
		
		return $html;
		
	} // end get_basic_feed
	
	
	public function get_remote_feed( $settings ){
		
		$taxonomies = array(
			'post_tag' => 'Tags',
			'category' => 'Categories',
		);
		
		$html .= Forms_PB::text_field( $this->get_name_field('ext_source') , $settings['ext_source'] , 'Source (Homepage URL)' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('ext_post_type') , $settings['post_type'] , 'Content Type (post,page,etc...)' );
		
		$html .= Forms_PB::select_field( $this->get_name_field('ext_taxonomy') , $settings['taxonomy'] , $taxonomies , 'Type' ); 
		
		$html .= Forms_PB::text_field( $this->get_name_field('ext_tax_terms') , $settings['tax_terms'] , 'Terms' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('ext_posts_per_page') , $settings['posts_per_page'] , 'Count' );
		
		return $html;
		
	} // end get_basic_feed
	
	
	
	public function get_display_form( $settings ){
		
		$html = Forms_PB::text_field( $this->get_name_field('excerpt_length') , $settings['excerpt_length'] , 'Excerpt Length (# Words)' );
		
		return $html;
		
	} // end get_display_form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		/*$clean['tag'] = ( ! empty( $s['tag'] ) )? sanitize_text_field( $s['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $s['title'] ) )? sanitize_text_field( $s['title'] ) : '';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';*/
		
		$clean['display_type'] = 'gallery';
		
		$clean['display_columns'] = '4';
		
		$clean['title'] = ( ! empty( $s['title'] ) ) ? $s['title'] : '';
		
		$clean['tag'] = ( ! empty( $s['tag'] ) ) ? $s['tag'] : 'h2';
		
		$clean['hide_excerpt'] = ( ! empty( $s['hide_excerpt'] ) ) ? $s['hide_excerpt'] : '';
		
		$clean['hide_image'] = ( ! empty( $s['hide_image'] ) ) ? $s['hide_image'] : '';
		
		$clean['hide_link'] = ( ! empty( $s['hide_link'] ) ) ? $s['hide_link'] : '';
		
		$clean['posts_per_page'] =  ( ! empty( $s['posts_per_page'] ) ) ? sanitize_text_field( $s['posts_per_page'] ) : 5;
		
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