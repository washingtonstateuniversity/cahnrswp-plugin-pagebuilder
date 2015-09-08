<?php
class Query_PB {
	
	public static function get_local_query_args( $settings ){
		
		$query = array();
		
		// Post Pagation
		
		if ( ! empty( $settings['posts_per_page'] ) ) $query['posts_per_page'] = $settings['posts_per_page'];
		
		// Post Type ---------------------------------------
		
		$query['post_type'] = ( ! empty( $settings['post_type'] ) )? $settings['post_type'] : 'page' ;
		
		// Taxonomy ---------------------------------------
		
		if ( ! empty( $settings['taxonomy'] ) && ! empty( $settings['terms'] ) ){
			
			$tax_query = array();
			
			$tax_query['taxonomy'] = $settings['taxonomy'];
			
			$tax_query['field'] = 'name';
			
			if ( ! empty( $settings['terms'] ) ) $tax_query['terms'] = explode( ',' , $settings['terms'] );	
			
			$query['tax_query'] = array( $tax_query );
			
		} // end if
		
		return $query;
		
		
	} // end get_local_query_args
	
	public static function get_local_feed_objs( $args , $settings = array( 'img_size' => 'thumbnail' ) ) {
		
		global $wp_filter;
		
		
		
		$feed = array();
		
		$the_query = new WP_Query( $args );
		
		if ( $the_query->have_posts() ){
			
			$i = 0;
			
			while ( $the_query->have_posts() ) {
				
				$the_query->the_post();
				
				ob_start(); 
				
				the_excerpt();
				
				$feed[$i]['excerpt'] = ob_get_clean();
				
				 
				
				//var_dump( wp_trim_excerpt() );

				
				/*if ( empty( $feed[$i]['excerpt'] ) ){
					
					$feed[$i]['excerpt'] = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $the_query->post->post_content ) ) , 25 );
					
				} // end if
				
				the_excerpt();*/
					
				$feed[$i]['title'] = $the_query->post->post_title; 
					
				$feed[$i]['link'] = get_post_permalink();
					 
				$img_size = ( ! empty( $settings['img_size'] ) ) ? $settings['img_size'] : 'thumbnail';
					
				$img = wp_get_attachment_image_src( get_post_thumbnail_id( $the_query->post->ID ) , $img_size );
					
				$feed[$i]['img'] = ( isset( $img[0] ) && $img[0] ) ? $img[0] : false;
				
				$i++;
				
			} // end while
			
		} // end if
		
		return $feed;
		
	} // end get_local_object 
	
}