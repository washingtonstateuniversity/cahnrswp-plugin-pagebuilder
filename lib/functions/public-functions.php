<?php

function cpb_get_image_properties_array( $image_id, $image_size = 'single-post-thumbnail'){
	
	$image_array = array();
	
	$image = wp_get_attachment_image_src( $image_id , $image_size );
			
	$image_array['alt'] = get_post_meta( $image_id, '_wp_attachment_image_alt', true);

	$image_array['src'] = $image[0];
	
	return $image_array;
	
} // End cpb_get_image_properties_array

function cpb_get_post_image_array( $post_id, $image_size = 'single-post-thumbnail' ){
	
	$image_array = array();
	
	if ( has_post_thumbnail( $post_id ) ){
		
		$image_id = get_post_thumbnail_id( $post_id );
		
		$image_array = cpb_get_image_properties_array( $image_id, $image_size );
		
	} // End if
	
	return $image_array;
	
} // end cpb_get_post_image_array


function cpb_plugin_dir( $path = ''){
	
	$full_path = CAHNRS_Pagebuilder_Plugin::$dir . $path;
	
	return $full_path;
	
} // End cpb_plugin_dir


function cpb_plugin_url( $path = ''){
	
	$full_path = CAHNRS_Pagebuilder_Plugin::$url . $path;
	
	return $full_path;
	
} // End cpb_plugin_dir


function cpb_register_item( $slug, $args = array(), $form_callback = false, $shortcode_callback = false, $clean_callback = false ){
	
	global $cpb_builder_items;
	
	if ( ! isset( $cpb_builder_items) || ! is_array( $cpb_builder_items ) ){
		
		$cpb_builder_items = array();
		
	} // End if
	
	$cpb_builder_items[ $slug ] = array(
		'slug'					=> $slug,	
		'args'					=> $args,
		'form_callback'			=> $form_callback,
		'shortcode_callback' 	=> $shortcode_callback,
		'clean_callback' 		=> $clean_callback,
	);
	
} // End cpb_register_item


function cpb_get_registered_items( $exclude_layout = false ){
	
	global $cpb_builder_items;
	
	if ( ! isset( $cpb_builder_items) || ! is_array( $cpb_builder_items ) ){
		
		$cpb_builder_items = array();
		
	} // End if
	
	$items = $cpb_builder_items;
	
	if ( $exclude_layout ){
		
		foreach( $items as $slug => $item_args ){
			
			if ( ! empty( $item_args['args']['is_layout'])){
				
				unset( $items[ $slug ] );
				
			} // End if
			
		} // End foreach
		
	} // End if
	
	return $items;
	
} // End cpb_get_registered_items


function cpb_get_public_posts( $post_types = array(), $as_options = false, $include_empty = false ){
	
	$posts = array();
	
	if ( $include_empty ){
		
		$posts[''] = 'None Selected';
		
	} // End if
	
	if ( empty( $post_types ) ){
	
		$post_type_args = array(
			'publicly_queryable' => true,
		);

		$post_types = get_post_types( $post_type_args );
		
	} // End if
	
	$args = array(
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> -1,
		'post_type' 		=> $post_types,
	);
	
	$posts_array = get_posts( $args );
	
	//var_dump( $posts_array );

	if ( ! empty( $posts_array ) ) {
		
		foreach( $posts_array as $post_item ) {

			if ( $as_options ){
				
				$posts[ $post_item->ID ] = $post_item->post_title;
				
			} else {
				
				$posts[ $$post_item->ID ] = $post_item;
				
			} //  End if

		} // End foreacd
		
	} // End if
	
	return $posts;
	
} // End cpb_get_posts


function cpb_get_post_item( $post, $image_size = 'medium', $include_content = false ){
	
	$item = array();
	
	if ( is_numeric( $post ) ){
		
		$post = get_post( $post );
		
	} // End if
	
	if ( isset( $post->ID ) ){
		
		$post_image_array = cpb_get_post_image_array( $post->ID, 'medium' );
		
		if ( ! empty( $post_image_array ) ){
			
			$item['img'] = $post_image_array['src'];
			
			$item['img_alt'] = $post_image_array['alt'];
			
		} // End if
		
		$item['title'] = get_the_title( $post->ID );
		
		$item['excerpt'] = get_the_title( $post->ID );
			
		$item['link'] = get_post_permalink( $post->ID );
		
	} // End if
	
	return $item;
	
} // End cpb_get_post_item
