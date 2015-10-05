<?php
class Query_PB {
	
	public static function get_query_items( $settings , $args = array( 'img_size' => 'thumbnail' ) ){
		
		if ( ! empty( $settings['source'] ) ){
			
			switch( $settings['source'] ){
				
				case 'feed':
					$items = Query_PB::get_feed_items( $settings , $args );
					break;
				case 'remote_feed':
					$items = Query_PB::get_remote_feed_items( $settings , $args );
					break;
				default:
					$items = array();
					break;
					
			} // end switch
			
		} else {
			
			$items = array(); // Return empty set
			
		} // end if
		
		return $items;
		
	} // end do_query
	
	
	public static function get_remote_feed_items( $settings , $args ){
		
		$items = array();
		
		if ( ! empty( $settings['remote_url'] ) ){
			
			$response = wp_remote_get( Query_PB::build_remote_query( $settings , $args ) );
			
			if ( is_array( $response ) ){
				
				$body = wp_remote_retrieve_body( $response );
			
				$json = json_decode( $body , true );
			
				if ( is_array( $json ) && $json ){
					
					if ( ! empty( $args['supports'] ) ) { 
					
						$supports = $args['supports'];
						
					} else {
						
						$supports = array('title','content','excerpt','link','image' );
						
					} // end if 
				
					foreach( $json as $post ){
						
						$item = array();
						
						// Set the title
						if ( in_array( 'title' , $supports ) ) {
							
							$item['title'] = $post['title'];
							
						} // end if
							
			
						// Set the content
						if ( in_array( 'content' , $supports ) ) {
			
							$item['content'] = $post['content'];
							
						} // end if
			
						// Set the excerpt
						if ( in_array( 'excerpt' , $supports ) && empty( $settings['hide_excerpt'] ) ) {
			
							$item['excerpt'] = $post['excerpt'];
							
						} // end if
			
						// Set the link
						if ( in_array( 'link' , $supports ) && empty( $settings['hide_link'] ) ) {
			
							$item['link'] = $post['link'];
							
						} // end if
			
						// Set the image
						if ( in_array( 'image' , $supports ) && empty( $settings['hide_image'] ) ) {
							
							if ( ! empty( $args['image_size'] ) ){
								
								$image_size = $args['image_size'];
								
							} else {
								
								$image_size = 'thumbnail';
								
							} // end if
							
							if ( ! empty( $post['featured_image']['attachment_meta']['sizes'] ) ) {
								
								$image_meta = $post['featured_image']["attachment_meta"]['sizes'];
								
								if ( array_key_exists( $image_size , $image_meta ) ){
									
									$image = $image_meta[ $image_size ];
									
								} else {
									
									$image = $image_meta[ 'thumbnail' ];
									
								} // end if
			
								$item['image'] = $image['url'];
								
							} // end if
							
						} // end if
						
						// Set ID
						$item['ID'] = $post['ID'];
				
						$items[ 'item' . '-' . $post['ID'] ] = $item;
						
					} // end foreach
					
				}// end if
				
			} // end if
			
		} // end if
		
		return $items;
		
	}
	
	public static function build_remote_query( $settings , $args ){
		
		$query = array();
		
		// Handle post type
		if ( ! empty( $settings['remote_post_type'] ) ){
			
			$query[] = 'type=' . $settings['remote_post_type'];
			
		} // end if
		
		// Handle posts per page
		if ( ! empty( $settings['remote_posts_per_page'] ) ){
			
			$query[] = 'filter[posts_per_page]=' . $settings['remote_posts_per_page'];
			
		} // end if
		
		if ( ! empty( $settings['remote_taxonomy'] ) && ! empty( $settings['remote_terms'] ) ){
			
			$query[] = 'filter[taxonomy]=' . $settings['remote_taxonomy'];
			
			$query[] = 'filter[term]=' . $settings['remote_terms'];
			
			
		} // end if
		
		return $settings['remote_url'] . '/wp-json/posts?' . implode( '&' , $query );
		
	} // end build_remote_query
	
	/*public static function get_remote_items( $query_args , $supports = array() , $image_size = 'thumbnail' ){
		
		$items = array();
		
		$query_url = Query_PB::build_remote_query_url( $query_args );
		
		$response = wp_remote_get( $query_url );
		
		if ( is_array( $response ) ){
			
			$body = wp_remote_retrieve_body( $response );
			
			$json = json_decode( $body , true );
			
			if ( is_array( $json ) && $json ){
				
				foreach( $json as $post ){
					
					$item = array();
					
					// Set the title
					if ( empty( $supports ) || in_array( 'title' , $supports ) ) {
						
						$item['title'] = $post['title'];
						
					} // end if
			
					// Set the content
					if ( empty( $supports ) || in_array( 'content' , $supports ) ) {
		
						$item['content'] = $post['content'];
						
					} // end if
			
					// Set the excerpt
					if ( empty( $supports ) || in_array( 'excerpt' , $supports ) ) {
		
						$item['excerpt'] = $post['excerpt'];
						
						if ( isset( Query_PB::args['excerpt_length'] ) ){
							
							$item['excerpt'] = wp_trim_words( $item['excerpt'] , Query_PB::args['excerpt_length'] , '' );
							
						} // end if
						
					} // end if
			
					// Set the excerpt
					if ( empty( $supports ) || in_array( 'link' , $supports ) ) {
		
						$item['link'] = $post['link'];
						
					} // end if
			
					// Set the image
					if ( empty( $supports ) || in_array( 'image' , $supports ) ) {
						
						if ( ! empty( $post['featured_image']['attachment_meta']['sizes'] ) ) {
							
							$image_meta = $post['featured_image']["attachment_meta"]['sizes'];
							
							if ( array_key_exists( $image_size , $image_meta ) ){
								
								$image = $image_meta[ $image_size ];
								
							} else {
								
								$image = $image_meta[ 'thumbnail' ];
								
							} // end if
		
							$item['img'] = $image['url'];
							
						} // end if
						
					} // end if
			
					$items[ 'post' . '-' . $post['ID'] ] = $item;
					
					
				} // end foreach
				
			} // end if
			
		} else {
		}
		
		return $items;
		
	} // end if
	
	
	
	public static function get_items( $query_args , $supports = array() , $image_size = 'thumbnail' ){
		
		$items = array();
		
		$the_query = new WP_Query( $query_args );
		
		while ( $the_query->have_posts() ) {
			
			$the_query->the_post();
			
			$item = array();
			
			// Set the title
			if ( empty( $supports ) || in_array( 'title' , $supports ) ) {
				
				$item['title'] = get_the_title();
				
			} // end if
			
			// Set the content
			if ( empty( $supports ) || in_array( 'content' , $supports ) ) {

				$item['content'] = get_the_content();
				
			} // end if
			
			// Set the excerpt
			if ( empty( $supports ) || in_array( 'excerpt' , $supports ) ) {

				$item['excerpt'] = get_the_excerpt();
				
				if ( isset( Query_PB::args['excerpt_length'] ) ){
					
					$item['excerpt'] = wp_trim_words( $item['excerpt'] , Query_PB::args['excerpt_length'] , '' );
					
				} // end if
				
			} // end if
			
			// Set the excerpt
			if ( empty( $supports ) || in_array( 'link' , $supports ) ) {

				$item['link'] = get_permalink();
				
			} // end if
			
			// Set the image
			if ( empty( $supports ) || in_array( 'image' , $supports ) ) {
				
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $the_query->post->ID ), $image_size );
				
				if ( $image ) {

					$item['img'] = $image[0];
					
				} // end if
				
			} // end if
			
			$items[ 'post' . '-' . $the_query->post->ID ] = $item;
			
		} // end while
		
		return $items;
		
	} // end  get_query
	
	public static function build_remote_query_url( $query_args ){
		
		$params = array();
		
		if ( ! empty( $query_args['post_type'] ) ){
			
			$params[] = 'type=' . $query_args['post_type'];
			
		} // end if
		
		if ( ! empty( $query_args['posts_per_page'] ) ){
			
			$params[] = 'filter[posts_per_page]=' . $query_args['posts_per_page'];
			
		} // end if
		
		$url = $query_args['source'] . '/wp-json/posts?' . implode( '&' , $params );
		
		return $url;
		
	}
	
	
	public static function get_query_args(){
		
		$query = array();
		
		if ( ! empty( Query_PB::args['ext_source'] ) ){
			
			$query['source'] = Query_PB::args['ext_source'];
			
		} // end if
		
		// Get count args
		$query['posts_per_page'] = Query_PB::get_post_per_page();
		
		// Get post type args
		$query['post_type'] = Query_PB::get_post_type();
		
		// Get taxonomy args
		if ( ! empty( Query_PB::args['taxonomy'] ) && ! empty( Query_PB::args['terms'] ) ) {
			
			$query['tax_query'] = Query_PB::get_tax_query();
			
		} // end if
		
		return $query;
		
	} // get_query_args
	
	
	public static function get_post_per_page() {
		
		if ( ! empty( Query_PB::args['posts_per_page'] ) ){
			
			$n = Query_PB::args['posts_per_page'];
			
		} else {
			
			$n = get_option( 'posts_per_page' );
			
		} // end if
		
		return $n;
		
	} // end get_post_per_page
	
	
	public static function get_post_type(){
		
		$type = ( ! empty( Query_PB::args['post_type'] ) ) ? $type = Query_PB::args['post_type'] : 'post';
			
		return $type;
		
	} // end get_post_type
	
	
	public static function get_tax_query(){
		
		$tax_query = array();
			
		$tax_query['taxonomy'] = Query_PB::args['taxonomy'];
			
		$tax_query['field'] = 'name';
		
		Query_PB::args['terms'] = explode( ',' , Query_PB::args['terms'] );	
		
		return array( $tax_query );
		
	} // end get_tax_query
	
	
	
	
	/*public static static function get_local_query_args( $settings ){
		
		$query = array();

		// Post Pagation

		if ( ! empty( $settings['posts_per_page'] ) ) $query['posts_per_page'] = $settings['posts_per_page'];

		// Post Type ---------------------------------------

		$query['post_type'] = ( ! empty( $settings['post_type'] ) ) ? $settings['post_type'] : 'page' ;

		// Taxonomy ---------------------------------------

		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['terms'] ) ) {

			$tax_query = array();

			$tax_query['taxonomy'] = $settings['taxonomy'];

			$tax_query['field'] = 'name';

			if ( ! empty( $settings['terms'] ) ) $tax_query['terms'] = explode( ',', $settings['terms'] );

			$query['tax_query'] = array( $tax_query );

		} // end if

		return $query;


	} // end get_local_query_args

	public static static function get_local_feed_objs( $args, $settings = array( 'img_size' => 'thumbnail' ) ) {

		global $wp_filter;

		$feed = array();

		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) {

			$i = 0;

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				ob_start();

				the_excerpt();

				$feed[$i]['excerpt'] = ob_get_clean();

				/*if ( empty( $feed[$i]['excerpt'] ) ) {

					$feed[$i]['excerpt'] = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $the_query->post->post_content ) ), 25 );

				} // end if

				the_excerpt();*/
					
				/*$feed[$i]['title'] = $the_query->post->post_title; 
					
				$feed[$i]['link'] = get_post_permalink();

				$img_size = ( ! empty( $settings['img_size'] ) ) ? $settings['img_size'] : 'thumbnail';

				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $the_query->post->ID ), $img_size );

				$feed[$i]['img'] = ( isset( $img[0] ) && $img[0] ) ? $img[0] : false;

				$i++;

			} // end while

		} // end if

		return $feed;
		
	} // end get_local_object*/ 
	
}