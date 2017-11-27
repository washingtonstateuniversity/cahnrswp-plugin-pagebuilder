<?php
class CPB_Items {
	
	protected $items;
	
	protected $layout_items = array( 'row','column','pagebreak' );
	
	
	public function __construct(){
		
		$this->items = $this->the_items();	
		
	} // end __construct
	
	public function get_layout_items(){ return $this->layout_items; }
	
	public function get_items( $exclude_layout = false ){ 
	
		$items = $this->items;
		
		if ( $exclude_layout ){
			
			foreach(  $this->get_layout_items() as $key ){
				
				if ( ! empty( $items[ $key ] ) ) unset( $items[ $key ] );
				
			} // end foreach
			
		} // end if
		
		return apply_filters( 'cwpb_register_items', $items );
		
	} // end get_items
	
	public function get_items_objs(){
		
		$items = $this->get_items();
		
		$items_objs = array();
		
		foreach( $items as $key => $data ){
			
			$item = $this->get_item( $key );
			
			if ( $item ){
				
				$items_objs[ $key ] = $item;
				
			} // end if
			
		} // end foreach
		
		return $items_objs;
		
	} // end get_items_objs
	
	public function get_name_versions(){
		
		$params = array(); 
		
		$items = $this->get_items_objs();
		
		foreach( $items as $item ){
			
			$params[] = $item->get_slug() . '-' . $item->get_version(); 
			
		} // end foreach
		
		return $params; 
		
	} // end get_name_versions
	
	
	public function get_item( $slug , $settings = array() , $content = '' , $get_children = true ){
		
		$items = $this->get_items();
		
		$new_items = cpb_get_registered_items();
		
		if ( array_key_exists( $slug , $items ) ){
			
			return $this->get_item_old( $items, $slug, $settings, $content, $get_children );
			
		} else if ( array_key_exists( $slug , $new_items ) ) {
			
			return $this->get_item_updated( $slug, $settings, $content, $get_children );
			
		} else {
			
			return false;
			
		}// end if
		
	} // end get_item
	
	
	protected function get_item_old( $items, $slug, $settings = array(), $content = '', $get_children = true ){
		
		$item_array = $items[ $slug ];
			
		include_once $item_array['file_path'];

		if ( class_exists( $item_array['class'] ) ){

			$item = new $item_array['class']( $settings , $content );

			if ( $get_children && $item->get_allowed_children() ){

				$child_content = $item->get_child_content( $content );

				$children = $this->get_items_from_content( $child_content , $item->get_allowed_children() , $item->get_default_child() );

				switch( $item->get_slug() ){

					case 'row':
						$children = $this->get_item_row_columns( $item , $children );
						break;

				} // end switch

				if ( method_exists( $item , 'return_parse_children' ) ){

					$children = $item->return_parse_children( $children );

				} // end if

				$item->set_children( $children );

			} // end if

			return $item;

		} else {

			return false;

		}// end if
		
		
	} // End get_item_old
	
	
	private function get_item_updated( $slug, $settings = array(), $content = '', $get_children = true ){
		
		
		$new_items = cpb_get_registered_items();
		
		if ( array_key_exists( $slug , $new_items ) ) {
		
			$item = $new_items[ $slug ];
			
			if ( $get_children && ! empty( $item['allowed_children'] ) ) {

				$default_child = ( ! empty( $item['default_child'] ) )? $item['default_child'] : false;

				$children = $this->get_items_from_content( $content , $item['allowed_children'] , $default_child );

				$item['children'] = $children;

			} // End if

			return $item;
			
		} else {
			
			return false;
			
		} // End if
		
	} // End get_item_updated
	
	
	private function split_content( $content, $regex ){

		if ( '' == $content ) $content = ' ';

		// Add Delimiter to content. This is required to account for content outside of shortcodes
		$content_set = preg_replace_callback( $regex, function( $matches ) { return '|$|' . $matches[0] . '|$|'; }, $content );

		// Split into an array of content and shortcodes
		$content_set = explode( '|$|', $content_set );

		return $content_set;
		
	}
	
	
	public function get_items_from_content( $content , $allowed_types , $default_type ){
		
		// Populate this with shortcode later if exists
		$items = array();
		
		if ( in_array( 'all' , $allowed_types ) ) {
			
			$allowed_types = $this->get_item_slugs();
			
			//$new_items = array_keys( cpb_get_registered_items( true ) );
			
			//$allowed_types = array_merge( $allowed_types, $new_items );
			
		}
		
		// Get modified regex for parsing shortcodes
		$regex = $this->get_item_regex( $allowed_types );
		
		// Split content to account for malformed shortcodes
		$split_content = $this->split_content( $content , $regex );
		
		// Loop through and add items	
		foreach( $split_content as $index => $item_content ){
			
			// Ignore empty if more than one set
			if ( empty( $item_content ) && 1 < count( $split_content ) ) continue; 
			
			// Look for items
			preg_match_all( $regex, $item_content, $item_data );
			
			if ( ! empty( $item_data[2] ) ){ // item found
				
				// Get the item
				$item = $this->get_item( $item_data[2][0] , shortcode_parse_atts( $item_data[3][0] ) , $item_data[5][0] );
			
			} else if ( $default_type ) { // no items found and default exists set default
			
				$item = $this->get_item( $default_type , array() , $item_content );
			
			} else {
				
				$item = false;
				
			}// end if
			
			if ( $item ) $items[] = $item;
			
		} // end foreach
			
		return $items;
		
	} // get_items_recursive
	
	
	public function get_item_slugs(){
		
		return array_keys( $this->get_items() );
		
	}
	
	/*
	 * Takes an array of types and modifies the built in wp regex
	 * to search for only those items.
	*/
	public function get_item_regex( $types ) {

		// Create empty array to populate later
		$tags = array();

		// Populate array with $types as keys
		foreach( $types as $type ) {

			$tags[$type] = true;

		} // end foreach

		// The keys from $shortcode_tags are used to populate the regex in parsing code
		global $shortcode_tags;

		// Temporarily write tags to temp
		$temp = $shortcode_tags;

		// Override with custom set
		$shortcode_tags = $tags;

		// Get regex code using WP function
		$regex = get_shortcode_regex();

		// Set back to original
		$shortcode_tags = $temp;

		$regex = '/' . $regex . '/';

		return $regex;

	} // end get_item_regex
	
	private function the_items(){
		
		$items = array( 
			/*'pagebreak'   => array(
				'class'     => 'Item_Pagebreak_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-pagebreak-pb.php',
				'priority'  => 0,
			),*/
			'row'         => array(
				'class'     => 'CPB_Item_Row',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-row.php',
				'priority'  => 0,
			),
			'column'      => array(
				'class'   	=> 'CPB_Item_Column',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-column.php',
				'priority'  => 0,
			),
			'textblock'   => array(
				'class'   	=> 'CPB_Item_Textblock',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-textblock.php',
				//'exclude' => true,
				'priority'  => 2,
			),
			'subtitle'    => array(
				'class'   	=> 'CPB_Item_Subtitle',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-subtitle.php',
				'priority'  => 3,
			),
			'widget'      => array(
				'class'   	=> 'CPB_Item_Widget',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-widget.php',
				'priority'  => 0,
			),
			'image'       => array(
				'class'   	=> 'CPB_Item_Image',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-image.php',
				'priority'  => 5,
			),
			'video'       => array(
				'class'   	=> 'CPB_Item_Video',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-video.php',
				'priority'  => 4,
			),
			'action'   	  => array(
				'class'   	=> 'CPB_Item_Action',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-action.php',
				'priority'  => 5,
			),

			'postgallery' => array( 
				'class'   	=> 'CPB_Item_Postgallery', 
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-postgallery.php',
				'priority'  => 6,
			),
			'promo'       => array(
				'class'   	=> 'CPB_Item_Promo',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-promo.php',
				'priority'  => 7,
			),
			'list'        => array(
				'class'   	=> 'CPB_Item_List',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-list.php',
				'priority'  => 8,
			),
			'sidebar'        => array(
				'class'   	=> 'CPB_Item_Sidebar',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-sidebar.php',
				'priority'  => 8,
			),
			'iframe'        => array(
				'class'   	=> 'CPB_Item_Iframe',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-iframe.php',
				'priority'  => 8,
			),
			'faq'        => array(
				'class'   	=> 'CPB_Item_FAQ',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-faq.php',
				'priority'  => 8,
			),
			'pagebreak'        => array(
				'class'   	=> 'CPB_Item_Pagebreak',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-pagebreak.php',
				'priority'  => 8,
			),
			'figure'        => array(
				'class'   	=> 'CPB_Item_Figure',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-figure.php',
				'priority'  => 8,
			),
			'cpbtable'        => array(
				'class'   	=> 'CPB_Item_Table',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-table.php',
				'priority'  => 8,
			),
			'slideshow'        => array(
				'class'   	=> 'CPB_Item_Slideshow',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-slideshow.php',
				'priority'  => 8,
			),
			'slide'        => array(
				'class'   	=> 'CPB_Item_Slide',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/class-cpb-item-slide.php',
				'priority'  => 8,
			),
			'slider'        => array(
				'class'   	=> 'CPB_Item_Slider',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/slider/class-cpb-item-slider.php',
				'priority'  => 8,
			),
			'banner'        => array(
				'class'   	=> 'CPB_Item_Banner',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/banner/class-cpb-item-banner.php',
				'priority'  => 8,
			),
			'social'        => array(
				'class'   	=> 'CPB_Item_Social',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/social/class-cpb-item-social.php',
				'priority'  => 8,
			),
			'azindex'        => array(
				'class'   	=> 'CPB_Item_AZ_Index',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/a-z-index/class-cpb-item-a-z-index.php',
				'priority'  => 8,
			),
			'cpbtabs'        => array(
				'class'   	=> 'CPB_Item_Tabs',
				'file_path' => plugin_dir_path( dirname ( __FILE__ ) ) . 'items/tabs/class-cpb-item-tabs.php',
				'priority'  => 8,
			),
			
			
		);
		
		return apply_filters( 'cahnrs_pagebuilder_items_array' , $items );
		
	} // end set_items
	
	public function get_item_row_columns( $item , $found_children ){
		
		$children = array();
		
		$layouts = $item->get_layouts();
		
		$settings = $item->get_settings();
		
		$layout_slug = ( ! empty( $settings['layout'] ) && array_key_exists( $settings['layout'] , $layouts ) ) ? $settings['layout'] : 'single';
		
		$layout = $layouts[ $layout_slug ];
		
		for ( $i = 0; $i < count( $layout['columns'] ); $i++ ){
			
			if ( ! empty( $found_children[ $i ] ) ){
				
				$found_children[ $i ]->set_index( ( $i + 1 ) );
				
				$children[] = $found_children[ $i ];
				
			} else {
				
				$column = $this->get_item( 'column' );
				
				$column->set_index( $i + 1 );
				
				$children[] = $column;
				
			} // end if
			
		} // end for
		
		
		return $children;
		
	}
	
	
	
}