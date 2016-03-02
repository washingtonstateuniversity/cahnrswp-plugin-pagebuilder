<?php 

class AJAX_Update_Editor_PB {
	
	private $pre = '_cpb';
	
	private $item_factory;
	
	
	public function __construct( $item_factory ){
		
		$this->item_factory = $item_factory;
		
	} // end __construct
	
	public function init_actions(){
		
		// Handle AJAX calls
		add_action( 'wp_ajax_cpb_update_editor', array( $this, 'update_editor' ) );
		
	} // end init_actions
	
	public function update_editor(){
		
		$json = array();
		
		$item_id = ( ! empty( $_POST['item_id'] )  ) ? sanitize_text_field( $_POST['item_id'] ) : false;
		
		if ( $item_id ){
		
			$settings = $this->get_settings( $item_id ); 
			
			$content = $this->get_content( $item_id );
			
			$item_slug = $this->get_item_slug( $item_id );
			
			$item = $this->item_factory->get_item_by_slug( $item_slug , $settings , $content );
			
			if ( $item ){
				
				$json['editor'] = $item->editor( $settings , '' );
				
			} // end if
		
		} //end if
		
		echo json_encode( $json );

		die();
		
	}
	
	private function get_settings( $item_id ){
		
		if ( ! empty( $_POST[ $this->pre ][ $item_id ]['settings'] ) ){
			
			return $_POST[ $this->pre ][ $item_id ]['settings'];
			
		} else {
			
			return array();
			
		}// end if
		
	} 
	
	private function get_content( $item_id ){
		
		if ( ! empty( $_POST[ 'current_content' ] ) ) {
			
			return wp_kses_post( $_POST['current_content'] );
			
		} else if ( ! empty( $_POST[ '_content_' . $item_id ] ) ) {
			
			return wp_kses_post( $_POST[ '_content_' . $item_id ] );
			
		} else {
			
			return '';
			
		}// end if
		
	}
	
	private function get_item_slug( $item_id ){
		
		$data = explode( '_' , $item_id );
		
		return $data[0];
		
	}
	
}