<?php

function cpb_plugin_dir( $path = ''){
	
	$full_path = CAHNRS_Pagebuilder_Plugin::$dir . $path;
	
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
