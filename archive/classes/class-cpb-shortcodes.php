<?php
class CPB_Shortcodes {
	
	private $items;
	
	public function __construct( $items ){
		
		$this->items = $items;
		
	}
	
	public function add_shortcodes(){
		
		$shortcode_array = $this->items->get_items();
		
		if ( $shortcode_array ) { // Check if items exist

			foreach( $shortcode_array as $name => $info ) {
				
				if ( ! empty( $info['exclude'] ) ) continue;

				add_shortcode( $name, array( $this, 'render_shortcode' ) );

			} // end foreach

		} // end if
		
	} // end add_shortcodes
	
	
	public function render_shortcode( $atts, $content, $name ) {
		
		$html = '';
		
		$item_obj = $this->items->get_item( $name , $atts , $content , false );
		
		if ( $item_obj ){
			
			$html = $item_obj->the_item( $item_obj->get_settings() , $item_obj->get_content() ); 
		
		} // end if
		
		return $html;

	} // end reg_shortcode	
	
}