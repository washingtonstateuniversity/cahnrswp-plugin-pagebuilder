<?php
class Display_PB {
	
	public static function get_display( $feed , $settings = array() , $class = '' ){
		
		$display = ( ! empty( $settings['display'] ) ) ? $settings['display'] : 'list';
		
		switch( $display ){
			
			default:
				$html = Display_PB::get_list( $feed , $settings , $class );
				break;
			
		} // end switch
		
		return $html;
		
	} // end get_display
	
	public static function get_list( $feed , $settings = array() , $class = '' ){
		
		$html = '<ul class="cpb-list">';
		
		if ( is_array( $feed ) ){
			
			foreach( $feed as $post ){
				
				$link = '<a href="' . $post['link'] .'">';
				
				$html .= '<li class="cpb-list-item">';
				
					$html .= '<h6>' . $link . $post['title'] . '</a></h6>';
					
					$html .= '<span>' . $post['excerpt'] . '</span>';
					
					$html .= '<a href="' . $post['link'] .'">Read More</a>';
				
				$html .= '</li>';
				
			} // end foreach
			
		} // end if
		
		$html .= '</ul>';
		
		return $html;
		
	} // end get_list
	
}