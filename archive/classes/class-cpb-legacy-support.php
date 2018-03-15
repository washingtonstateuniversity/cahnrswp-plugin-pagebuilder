<?php

class CPB_Legacy_Support {
	
	public function __construct(){
		
		add_filter( 'cpb_item_settings', array( $this, 'check_legacy_support'), 10, 2 );
		
	} // End __construct
	
	
	public function check_legacy_support( $settings, $item ){
		
		$type = $item->get_slug();
		
		switch( $type ){
				
			case 'promo':
				$settings = $this->get_legacy_promo( $settings );
				break;
				
		} // End switch
		
		return $settings;
		
	} // End check_legacy_support
	
	
	protected function get_legacy_promo( $settings ){
		
		if ( ! empty( $settings['post_id'] ) ){
			
			$settings['promo_type'] = 'select';
				
			$settings['post_ids'] = $settings['post_id'];
			
		} // End if
		
		return $settings;
		
	} // End get_legacy_promo
	
	
} // End CPB_Legacy_Support

$cpb_legacy_support = new CPB_Legacy_Support();