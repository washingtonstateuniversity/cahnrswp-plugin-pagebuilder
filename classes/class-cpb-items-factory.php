<?php
class CPB_Items_Factory {
	
	private $items;
	
	public function __construct(){
		
		$this->items = $this->pb_items();	
		
	} // end __construct
	
	public function get_items(){
		
		return $this->items;
		
	} // end get_items
	
	
	
	public function pb_items(){
		
		$items = array( 
			/*'section'     => array(
				'class'     => 'Item_Section_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-section-pb.php',
				'priority'  => 0,
			),
			'pagebreak'   => array(
				'class'     => 'Item_Pagebreak_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-pagebreak-pb.php',
				'priority'  => 0,
			),*/
			'row'         => array(
				'class'     => 'Item_Row_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::$dir . 'items/item-row-pb.php',
				'priority'  => 0,
			),
			/*'column'      => array(
				'class'   	=> 'Item_Column_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-column-pb.php',
				'priority'  => 0,
			),
			'textblock'   => array(
				'class'   	=> 'Item_Textblock_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-textblock-pb.php',
				'priority'  => 2,
			),
			'widget'      => array(
				'class'   	=> 'Item_Widget_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'classes/class-item-widget-pb.php',
				'priority'  => 0,
			),
			'image'       => array(
				'class'   	=> 'Item_Image_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-image-pb.php',
				'priority'  => 5,
			),
			'subtitle'    => array(
				'class'   	=> 'Item_Subtitle_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-subtitle-pb.php',
				'priority'  => 3,
			),
			'video'       => array(
				'class'   	=> 'Item_Video_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-video-pb.php',
				'priority'  => 4,
			),
			'cwpiframe'   => array(
				'class'   	=> 'Item_Iframe_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-iframe-pb.php',
				'priority'  => 9,
			),
			'action'   	  => array(
				'class'   	=> 'Item_Action_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-action-pb.php',
				'priority'  => 5,
			),

			'postgallery' => array( 
				'class'   	=> 'Item_Postgallery_PB', 
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-postgallery-pb.php',
				'priority'  => 6,
			),
			'promo'       => array(
				'class'   	=> 'Item_Promo_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-promo-pb.php',
				'priority'  => 7,
			),
			'list'        => array(
				'class'   	=> 'Item_List_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-list-pb.php',
				'priority'  => 8,
			),
			'tabs'        => array(
				'class'   	=> 'Item_Tabs_PB',
				'file_path' => CAHNRS_Pagebuilder_Plugin::dir . 'items/item-tabs-pb.php',
				'priority'  => 9,
			),*/
		);
		
		return $items;
		
	} // end set_items
	
	
	
}