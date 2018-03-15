<?php

class CPB_Item_Sidebar extends CPB_Item {
	
	protected $slug = 'sidebar';

	protected $name = 'Sidebar (Widgets)';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html .= '';
		
		if ( $settings['sidebar_id'] ){
			
			ob_start();
		
			dynamic_sidebar( $settings['sidebar_id'] );
			
			$sidebar = ob_get_clean();
			
			$html = do_shortcode( $sidebar );
		
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		global $wp_registered_sidebars;
		
		$sidebars = array( 0 => 'None' );
		
		foreach( $wp_registered_sidebars as $sidebar ){
			
			$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			
		} // end foreach
		
		$form = $this->form_fields->select_field( $this->get_input_name('sidebar_id'), $settings['sidebar_id'], $sidebars, 'Select Sidebar' );
		
		return $form;
		
	} // end form
	
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['sidebar_id'] = ( ! empty( $settings['sidebar_id'] ) ) ? sanitize_text_field( $settings['sidebar_id'] ):'';
		
		return $clean;
		
	} // end clean
	
}