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