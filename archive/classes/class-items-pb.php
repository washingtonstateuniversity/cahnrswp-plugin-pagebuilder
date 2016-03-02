<?php
class Items_PB {

	// Registered Items
	public $items = false;

	public function get_items_from_content( $content, $types, $default_type, $recursive = false ) {

		if ( ! is_array( $types ) ) $types = array( $types );

		$regex = $this->get_item_regex( $types );

		$split_content = $this->get_split_content( $content, $regex );

		$shortcodes = $this->get_extract_shortcode( $split_content, $regex, $default_type );

		$items = array();

		foreach( $shortcodes as $shortcode ) {

			$items[] = $this->get_item( $shortcode['shortcode'], $shortcode['content'], $shortcode['settings'], $recursive );

		} // end $shortcode

		return $items;

	} // end get_items_from_content

	/*
	 * Get the item. If recursive is set child items in content will be added as
	 * as the children property of the item
	*/
	public function get_item( $name, $content = '', $settings = array(), $recursive = false ) {

		// check is array and item is registered
		if ( is_array( $this->items ) && array_key_exists( $name, $this->items ) ) {

			// Get it's file
			$file = $this->items[ $name ]['file_path'];

			// Get it's class
			$class = $this->items[ $name ]['class'];

			// Require the file
			require_once $file;

			// Build item obj
			$item = new $class( $settings, $content );

			// Get children
			if ( $recursive ) {

				$allowed = $this->get_allowed_children( $item );

				$item->children = $this->get_items_from_content( $content, $allowed, $item->default_child, true );

				if ( 'row' == $name ) {

					$this->check_columns( $item, $item->children );

				} // end if

				if ( $item->children ) {

					foreach( $item->children as $i => $child ) {

						$item->children[ $i ]->i = $i;

					} // end foreach

				} // end if

			} // end if

			return $item;

		} // end if


	} // end get_item

	/*
	 * Extract allowed children from item
	*/
	public function get_allowed_children( $item ) {

		if ( ! empty( $item->allowed_children ) ) {

			if ( 'all' == $item->allowed_children ) {

				$allowed = array_keys( $this->items );

			} else {

				$allowed = $item->allowed_children;

			} //

		} else {

			$allowed = array();

		} // end if

		return $allowed;

	}

	/*
	 * Using an array of items, foreach item get html from the editor method
	 * of the item. This is recursive and will get child items as well
	*/
	public function get_editor_items( $items, $recursive = true ) {

		$html = '';

		foreach( $items as $item ) {

			$html .= $this->get_editor_item( $item, $recursive );

		} // end foreach

		return $html;

	} // end get_editor_items

	/*
	 * Get the editor htm for a single item. If recursive is true
	 * then child items will be included.
	*/
	public function get_editor_item( $item, $recursive = true ) {

		$editor_content = '';

		if ( $recursive && ! empty( $item->children ) ) {

			$editor_content = $this->get_editor_items( $item->children );

		} // end if

		$html = $item->the_editor( $editor_content );

		return $html;

	} // end get_editor_item

	public function flatten_array( $items ) {

		$flat = array();

		foreach( $items as $item ) {

			$flat[] = $item;

			if ( $item->children ) {

				$flat = array_merge( $flat, $this->flatten_array( $item->children ) );

			} // end if

		} // end foreach

		return $flat;

	}

	public function check_columns( $item, &$children ) {

		$layout_name = ( ! empty( $item->settings['layout'] ) ) ? $item->settings['layout'] : 'single';

		$layout = $this->get_layout( $layout_name );

		while ( count( $children ) < count( $layout ) ) {

			$children[] = $this->get_item( 'column', '', array(), true );

		} // end if

	} // end check_columns

	/*
	* Returns an array of all items with default settings
	*/
	public function get_all_items() {

		$items = array();

		foreach( $this->items as $slug => $item ) {

			$items[] = $this->get_item( $slug );

		} // end foreach

		return $items;

	} // end get_all_items

	/*
   	 * Builds the items and sets $items property. This is done
   	 * later instead of off construct so that other plugins can
   	 * filter the items added.
	*/
	public function register_items( $post = false ) {

		$registered_items = array(
			'section'     => array(
				'class'   => 'Item_Section_PB',
				'file_path' => CWPPBDIR . 'items/item-section-pb.php',
				'priority'  => 0,
			),
			'pagebreak'         => array(
				'class'   => 'Item_Pagebreak_PB',
				'file_path' => CWPPBDIR . 'items/item-pagebreak-pb.php',
				'priority'  => 0,
			),
			'row'         => array(
				'class'   => 'Item_Row_PB',
				'file_path' => CWPPBDIR . 'items/item-row-pb.php',
				'priority'  => 0,
			),
			'column'      => array(
				'class'   => 'Item_Column_PB',
				'file_path' => CWPPBDIR . 'items/item-column-pb.php',
				'priority'  => 0,
			),
			'textblock'   => array(
				'class'   => 'Item_Textblock_PB',
				'file_path' => CWPPBDIR . 'items/item-textblock-pb.php',
				'priority'  => 2,
			),
			'widget'   => array(
				'class'   => 'Item_Widget_PB',
				'file_path' => CWPPBDIR . 'classes/class-item-widget-pb.php',
				'priority'  => 0,
			),
			'image'   => array(
				'class'   => 'Item_Image_PB',
				'file_path' => CWPPBDIR . 'items/item-image-pb.php',
				'priority'  => 5,
			),
			'subtitle'   => array(
				'class'   => 'Item_Subtitle_PB',
				'file_path' => CWPPBDIR . 'items/item-subtitle-pb.php',
				'priority'  => 3,
			),
			'video'   => array(
				'class'   => 'Item_Video_PB',
				'file_path' => CWPPBDIR . 'items/item-video-pb.php',
				'priority'  => 4,
			),
			/*'feed'   => array(
				'class'   => 'Item_Feed_PB',
				'file_path' => CWPPBDIR . 'items/item-feed-pb.php',
			),*/
			'cwpiframe'   => array(
				'class'   => 'Item_Iframe_PB',
				'file_path' => CWPPBDIR . 'items/item-iframe-pb.php',
				'priority'  => 9,
			),
			'action'   => array(
				'class'   => 'Item_Action_PB',
				'file_path' => CWPPBDIR . 'items/item-action-pb.php',
				'priority'  => 5,
			),

			'postgallery'   => array( 
				'class'   => 'Item_Postgallery_PB', 
				'file_path' => CWPPBDIR . 'items/item-postgallery-pb.php',
				'priority'  => 6,
			),
			'promo'       => array(
				'class'   => 'Item_Promo_PB',
				'file_path' => CWPPBDIR . 'items/item-promo-pb.php',
				'priority'  => 7,
			),
			'list'       => array(
				'class'   => 'Item_List_PB',
				'file_path' => CWPPBDIR . 'items/item-list-pb.php',
				'priority'  => 8,
			),
			'tabs'       => array(
				'class'   => 'Item_Tabs_PB',
				'file_path' => CWPPBDIR . 'items/item-tabs-pb.php',
				'priority'  => 9,
			),
			/*'subtitle'    => array(
				'class'   => 'Item_Subtitle_CPB',
				'file_path' => CWPPBDIR . 'items/item-subtitle-cpb.php',

			),
			
			'insertpost'  => array(
				'class'   => 'Item_Insertpost_CPB',
				'file_path' => CWPPBDIR . 'items/item-insertpost-cpb.php',
			),
			'inserttable' => array(
				'class'   => 'Item_Inserttable_CPB',
				'file_path' => CWPPBDIR . 'items/item-inserttable-cpb.php',
			),
			'figure'      => array(
				'class'   => 'Item_Figure_CPB',
				'file_path' => CWPPBDIR . 'items/item-figure-cpb.php',
			),*/
		);
		
		$items = apply_filters( 'cwpb_register_items', $registered_items, $post );
		
		uasort( $items , function( $a , $b ){ 
		
			if ( empty( $a['priority'] ) ) $a['priority'] = 100;
			
			if ( empty( $b['priority'] ) ) $b['priority'] = 100; 
			
			return $a['priority'] - $b['priority'];
			 
		}); // end usort

		$this->items = $items;

	} // end register_items

	/*
	 * Takes registered items and registers any shortcodes
	*/
	public function register_shortcodes() {

		if ( $this->items ) { // Check if items exist

			foreach( $this->items as $name => $info ) {

				add_shortcode( $name, array( $this, 'render_shortcodes' ) );

			} // end foreach

		} // end if

	} // end register_shortcodes

	/*
	 * Handles rendering registered shortcodes
	*/
	public function render_shortcodes( $atts, $content, $name ) {

		// Get the item object
		$item = $this->get_item( $name, $content, $atts );

		// Get the item public
		return $item->the_item();

	} // end reg_shortcode

	/*
	 * Takes an array of types and modifies the built in wp regex
	 * to search for only those items.
	*/
	public function get_item_regex( array $types ) {

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

	/*
	 * Split content by recognized shortcodes using the built regex
	*/
	public function get_split_content( $content, $regex ) {

		if ( '' == $content ) $content = ' ';

		// Add Delimiter to content. This is required to account for content outside of shortcodes
		$content_set = preg_replace_callback( $regex, function( $matches ) { return '|$|' . $matches[0] . '|$|'; }, $content );

		// Split into an array of content and shortcodes
		$content_set = explode( '|$|', $content_set );

		return $content_set;

	} // end get_split_content

	/*
	 * Extract all shortcodes and return as an array with
	 * shortcode, settings, and content.
	*/
	public function get_extract_shortcode( $split_content, $regex, $default ) {

		$shortcodes = array();

		foreach( $split_content as $content ) {

			if ( $content ) {

				$sc = array();

				// Check section for top level shortcode
				preg_match_all( $regex, $content, $shortcode );

				if ( $shortcode[2][0] ) {

					$sc['shortcode'] = $shortcode[2][0];

					$sc['settings'] = shortcode_parse_atts( $shortcode[3][0] );

					$sc['content'] = $shortcode[5][0];

					$shortcodes[] = $sc;

				} else if ( $default ) {

					$sc['shortcode'] = $default;

					$sc['settings'] = array();

					$sc['content'] = $content;

					$shortcodes[] = $sc;

				}// end if

			} // end if

		} // end foreach

		return $shortcodes;

	} // end get_extract_shortcode

	public function get_layout( $layout_name ) {

		switch( $layout_name ) {

			case 'halves':
				$layout = array('50%','50%');
				break;
			case 'side-right':
				$layout = array('70%','30%');
				break;
			case 'side-left':
				$layout = array('30%','70%');
				break;
			case 'thirds':
				$layout = array( '33.33%','33.33%','33.33%');
				break;
			case 'thirds-half-left':
				$layout = array( '50%','25%','25%');
				break;
			case 'thirds-half-right':
				$layout = array( '25%','25%','50%');
				break;
			case 'quarters':
				$layout = array('25%','25%','25%','25%');
				break;
			case 'triptych':
				$layout = array('25%','50%','25%');
				break;
			default:
				$layout = array('auto');
				//$layout = array( 2, array('50%','50%') );
				break;
		} // end switch

		return $layout;

	}

}