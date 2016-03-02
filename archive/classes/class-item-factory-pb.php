<?php
class Item_Factory_PB {
	
	public function get_item_by_slug( $slug , $settings = array(), $content = '' ){
		
		$items = $this->get_items();
		
		$item = false;
		
		if ( array_key_exists( $slug , $items ) ){
			
			$item_data = $items[ $slug ];
			
			if ( file_exists ( $item_data['file_path'] ) ){
				
				require_once $item_data['file_path'];
				
				if ( class_exists ( $item_data['class'] ) ){
					
					$item = new $item_data['class']( $settings , $content );
					
				} // end if
				
			} // end if
			
		} // end if
		
		return $item;
		
	}
	
	
	public function get_items(){
		
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
		
		return apply_filters( 'cwpb_register_items', $registered_items );
		
	}
	
	
}