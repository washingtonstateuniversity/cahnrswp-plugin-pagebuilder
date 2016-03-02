<?php
Class CPB_Stylesheet {
	
	protected $cpb;
	
	
	public function __construct(){
		
		$this->cpb = CAHNRS_Pagebuilder_Plugin::get_instance();
		
	} // end __construct
	
	
	public function do_stylesheet( $type ){
		
		$type = $this->check_type( $type );
		
		if ( $type ){
		
			header("Content-type: text/css; charset: UTF-8");
			
			if ( 'admin' == $type ){
			
				include plugin_dir_path( dirname ( __FILE__ ) ) . 'css/admin.php';
			
			} // end if
			
			$items = $this->cpb->items->get_items_objs();
			
			foreach( $items as $item ){
				
				echo "\r\n" . "\r\n" . '/* --- ' . $item->get_name() . ' CSS ----------------------- */' . "\r\n";
				
				echo $item->the_css( $type );
				
			} // end foreach
			
		} // end if
		
	} // end do_stylesheet
	
	public function get_style( $type ) {
		
		$css = '';
		
		$type = $this->check_type( $type );
		
		if ( $type ){
			
			$items = $this->cpb->items->get_items_objs();
			
			foreach( $items as $item ){
				
				$css .= "\r\n" . "\r\n" . '/* --- ' . $item->get_name() . ' CSS ----------------------- */' . "\r\n";
				
				$css .= $item->the_css( $type );
				
			} // end foreach
			
		} // end if
		
		return $css;
		
	} 
	
	public function check_type( $type ){
		
		switch( $type ){
			
			case 'admin':
				$type = 'admin';
				break;
			case 'public':
				$type = 'public';
				break;
			case 'editor':
				$type = 'editor';
				break;
			default:
				$type = false;
				break;
			
		} // end switch
		
		return $type;
		
	} // end check_type
	
}