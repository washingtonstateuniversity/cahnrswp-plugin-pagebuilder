<?php
class Item_Postgallery_PB extends Item_PB {
	
	public $slug = 'postgallery';
	
	public $name = 'Post Gallery';
	
	public $desc = 'Add gallery of posts/pages';
	
	public $form_size = 'medium';
	
	public function item( $settings , $content ){
		
		require_once CWPPBDIR . 'classes/class-query-pb.php';
		
		require_once CWPPBDIR . 'classes/class-display-pb.php';
		
		$query = new Query_PB( $settings );
		
		$display = new Display_PB( $settings );
			
		$items = $query->get_query_items();
		
		$html = $display->get_display( $items );
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';
		
		$html = '<h2>' . $title . '</h2>';
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$source = array(
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
		
		$clean['excerpt_length'] = ( isset( $s['excerpt_length'] ) )? sanitize_text_field( $s['excerpt_length'] ) : 25;
		
		$clean['feed_source'] = ( ! empty( $s['feed_source'] ) )? sanitize_text_field( $s['feed_source'] ) : 'feed';
		
		if ( 'feed' == $clean['feed_source'] ){
			
			$clean['post_type'] = ( ! empty( $s['post_type'] ) )? sanitize_text_field( $s['post_type'] ) : 'post';
		
			$clean['taxonomy'] = ( ! empty( $s['taxonomy'] ) )? sanitize_text_field( $s['taxonomy'] ) : false;
		
			$clean['tax_terms'] = ( ! empty( $s['tax_terms'] ) )? sanitize_text_field( $s['tax_terms'] ) : false;
			
		} // end if
		
		if ( 'external' == $clean['feed_source'] ){
			
			$clean['ext_source'] = ( ! empty( $s['ext_source'] ) )? sanitize_text_field( $s['ext_source'] ) : '';
			
			$clean['post_type'] = ( ! empty( $s['post_type'] ) )? sanitize_text_field( $s['post_type'] ) : 'post';
			
			if ( ! empty( $s['ext_post_type'] ) ) $clean['post_type'] = sanitize_text_field( $s['ext_post_type'] );
		
			$clean['taxonomy'] = ( ! empty( $s['ext_taxonomy'] ) )? sanitize_text_field( $s['ext_taxonomy'] ) : false;
		
			$clean['tax_terms'] = ( ! empty( $s['ext_tax_terms'] ) )? sanitize_text_field( $s['ext_tax_terms'] ) : false;
			
		} // end if
		
		if ( ! empty( $s['posts_per_page'] ) ) $clean['posts_per_page'] = sanitize_text_field( $s['posts_per_page'] );
		
		return $clean;
		
	} // end clean
	
}