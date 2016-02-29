<?php

class CPB_Item_Textblock extends CPB_Item {
	
	protected $name = 'Text/HTML';
	
	protected $slug = 'textblock';
	
	protected $uses_wp_editor = true;
	
	public function item( $settings , $content ){
		
		$content = do_shortcode( $content );
		
		return apply_filters( 'the_content' , $content );
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		ob_start();
		
		wp_editor( $content , '_cpb_content_' . $this->get_id() );
		
		$html .= ob_get_clean();
		
		return $html;
		
	} // end form
	
	public function editor_default_html(){
		
		return 'Add Text Here';
		
	} // end editor_default_html
	
	
}