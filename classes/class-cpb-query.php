<?php
class CPB_Query {
	
	protected $fields = array('title','content','img','link','excerpt');
	
	public function get_fields(){ return $this->fields; }
	
	public function get_local_items( $settings , $prefix = '' , $fields = false ) {
		
		$items = array();
		
		if ( ! $fields ) $fields = $this->get_fields();
		
		$query_args = $this->get_query_args( $settings, $prefix );
		
		$the_query = new WP_Query( $query_args );
		
		if ( $the_query->have_posts() ) {
			
			while ( $the_query->have_posts() ) {
				
				$the_query->the_post();
				
				$item = array();
				
				if ( in_array( 'title' , $fields ) ) $item['title'] = get_the_title();
				
				if ( in_array( 'content' , $fields ) ) $item['content'] = get_the_content();
				
				if ( in_array( 'excerpt' , $fields ) ) $item['excerpt'] = $this->get_local_excerpt( $the_query->post->ID , $settings );
				
				if ( in_array( 'img' , $fields ) ) $item['img'] = $this->get_local_img( $the_query->post->ID , $settings );
				
				if ( in_array( 'link' , $fields ) ) $item['link'] = get_post_permalink();
				
				$items[ $the_query->post->ID ] = $item;
				
			} // end while
			
		} // end if
		
		return $items;
		
	}
	
	public function get_query_args( $settings ,  $prefix = '' , $defaults = array() ){
		
		$args = array();
		
		$args['post_type'] = ( ! empty( $settings[ $prefix . 'post_type'] ) ) ? $settings[ $prefix . 'post_type'] : 'post';
		
		$args['posts_per_page'] = ( ! empty( $settings[ $prefix . 'count'] ) ) ? $settings[ $prefix . 'count'] : 5;
		
		if ( ! empty( $settings[ $prefix . 'offset'] ) ) {
			
			$args['offset'] = $settings[ $prefix . 'offset'];
			
		} // end if
		
		if ( ! empty( $settings[ $prefix . 'order_by'] ) ) {
			
			$args['orderby'] = $settings[ $prefix . 'order_by'];
			
		} // end if
		
		if ( ! empty( $settings[ $prefix . 'order'] ) ) {
			
			$args['order'] = $settings[ $prefix . 'order'];
			
		} // end if
		
		// Handle Taxonomy Query 
		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['terms'] ) ){
			
			$tax_query = array();
			
			$tax_query['taxonomy'] = $settings['taxonomy'];
			
			$tax_query['field'] = 'id';
			
			$terms = explode( ',' , $settings['terms'] ); 
			
			foreach( $terms as $term ){
				
				$wp_term = get_term_by( 'name', trim( $term ), $settings['taxonomy'] );
				
				//$tax_query['terms'][] = trim( $term );
				
				$tax_query['terms'][] = $wp_term->term_id;
				
			} // end foreach
			
			if ( ! empty( $settings['term_operator'] ) ){
				
				$tax_query['operator'] = $settings['term_operator'];
				
			} // end if
			
			$args['tax_query'] = array( $tax_query );
			
		} // end if
		
		return $args;
		
	}
	
	protected function get_local_img( $post_id , $settings ) {
		
		$img_src = '';
		
		$img_id = get_post_thumbnail_id( $post_id );
					
		if ( $img_id ){
		
			$image = wp_get_attachment_image_src( $img_id , 'single-post-thumbnail' );
			
			$img_src = $image[0];
		
		} // end if
		
		return $img_src;
		
	} // end get_local_img
	
	protected function get_local_excerpt( $post_id , $settings ){
		
		$excerpt = get_the_excerpt();
					
		if ( ! $excerpt ) {
			
			$excerpt = wp_trim_words( strip_shortcodes( wp_strip_all_tags( get_the_content() , true ) ) , 35 , '...' );
			
		} // end if
		
		return $excerpt;
		
	} // end get_local_excerpt
	
	public function get_remote_items( $settings , $prefix = '' , $fields = false ){
		
		if ( ! $fields ) $fields = $this->get_fields();
		
		$items = array();
		
		if( is_array( $settings[ $prefix . 'remote_items' ] ) && ! empty( $settings[ $prefix . 'remote_items' ] ) ){
			
			foreach( $settings[ $prefix . 'remote_items' ] as $request_item ){
				
				$url = $request_item['site'] . '/wp-json/posts/' . $request_item['id'];
				
				//var_dump( url );
				
				$response = wp_remote_get( $url ) ;
			
				if ( ! is_wp_error( $response ) ) {
			
					$body = wp_remote_retrieve_body($response);
					
					$json = json_decode( $body , true );
					
					if ( $json ){
						
						$item = array();
						
						if ( in_array( 'title' , $fields ) ) $item['title'] = $json['title'];
				
						if ( in_array( 'content' , $fields ) ) $item['content'] = $json['content'];
						
						if ( in_array( 'excerpt' , $fields ) ) $item['excerpt'] = $json['excerpt'];
						
						
						
						if ( in_array( 'img' , $fields ) ) {
							
							 $item['img'] = $this->get_remote_img( $json , $settings );
							 
						} // end if
						
						if ( in_array( 'link' , $fields ) ) $item['link'] = $json['link'];
						
						$items[ $request_item['id'] ] = $item;
						
						//var_dump( $json['featured_image']['attachment_meta']['sizes'] );
						
					} // end if
			
				} // end if
				
			} // end foreach
			
		} // end if
		
		return $items;
		
	} // end get_remote_items
	
	public function get_remote_items_feed( $settings , $prefix = '' , $fields = false ){
		
		if ( ! $fields ) $fields = $this->get_fields();
		
		$items = array();
		
		if ( ! empty( $settings[ $prefix . 'site_url' ] ) ){
			
			$query = $this->get_query_args_remote( $settings , $prefix = '' );
			
			if ( $query ){
				
				$url = $settings[ $prefix . 'site_url' ] . '/wp-json/posts' . $query;
				
			} else {
			
				$url = $settings[ $prefix . 'site_url' ];
				
			} // End if
			
			$response = wp_remote_get( $url ) ;
			
			if ( ! is_wp_error( $response ) ) {
		
				$body = wp_remote_retrieve_body($response);
				
				$json = json_decode( $body , true );
				
				if ( $json ){
						
					foreach( $json as $json_item ){
					
						$item = array();
						
						if ( in_array( 'title' , $fields ) ) $item['title'] = $json_item['title']['rendered'];
				
						if ( in_array( 'content' , $fields ) ) $item['content'] = $json_item['content']['rendered'];
						
						if ( in_array( 'excerpt' , $fields ) ) $item['excerpt'] = $json_item['excerpt']['rendered'];
						
						if ( ! empty( $json_item['post_images'])){
							
							$item['img'] = $json_item['post_images']['full'];
							
							$item['images'] = $json_item['post_images'];
							
						} // End if
						
						if ( in_array( 'link' , $fields ) ) $item['link'] = $json_item['link'];
						
						$items[ $json_item['id'] ] = $item;
					
					} // end foreach
					
				} // end if
				
			} // end if
			
		} // end if
		
		return $items;
		
	} // end get_remote_items
	
	protected function get_remote_img ( $item , $settings , $prefix = '' ){
		
		$size = ( ! empty( $settings[ $prefix . 'img_size'] ) ) ? $settings[ $prefix . 'img_size'] : 'medium';
		
		$url = '';
		
		if ( $item['featured_image'] ){
			
			$sizes = $item['featured_image']['attachment_meta']['sizes'];
			
			if ( array_key_exists( $size , $sizes ) ){
				
				$url = $sizes[ $size ]['url'];
				
			} // end if
			
		} // end if
		
		return $url;
		
	} // eng eget_remote_img
	
	protected function get_query_args_remote( $settings , $prefix = '' ){
		
		$get = '';
		
		$query = array();
		
		if ( ! empty( $settings[ $prefix . 'post_type'] ) ) $query[] = 'type=' . $settings[ $prefix . 'post_type'];
		
		if ( ! empty( $settings[ $prefix . 'taxonomy'] ) ) $query[] = 'filter[taxonomy]=' . $settings[ $prefix . 'taxonomy'];
		
		if ( ! empty( $settings[ $prefix . 'terms'] ) ) $query[] = 'filter[term]=' . $settings[ $prefix . 'terms'];
		
		if ( ! empty( $settings[ $prefix . 'count'] ) ) $query[] = 'filter[posts_per_page]=' . $settings[ $prefix . 'count'];
		
		if ( $query ){
			
			$get = '?' . implode( '&' , $query );
			
		} // end if
		
		return $get;
		
	} // end get_query_args_remote
	
}