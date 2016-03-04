<?php
class CPB_Save {
	
	private $items;
	
	private $fields = array( '_cpb_excerpt' , '_cpb_pagebuilder' , '_cpb_m_excerpt');
	
	public function __construct( $items ){
		
		$this->items = $items;
		
	} // end __constuct
	
	
	private function get_fields(){ return $this->fields; }
	
	
	private function get_settings(){

		$clean = array();
		
		$fields = $this->get_fields();

		foreach( $fields as $field ) {

			if ( isset( $_POST[ $field ] ) ) $clean[ $field ] = sanitize_text_field( $_POST[ $field ] );

		} // end foreach

		return $clean;
		
	} // end get_settings
	
	
	
	
	public function save_layout( $post_id ){
		
		if ( $this->check_can_save( $post_id ) ){
			
			$settings = $this->get_settings();
			
			$this->update_meta( $post_id , $settings );
			
			$this->update_post( $post_id , $settings );
			
		} // end if
		
		
		
	} // end save_layout
	
	private function update_meta( $post_id , $settings ){
		
		if ( ! $this->check_can_save ($post_id) ) return;
		
		foreach( $settings as $key => $value ){
			
			update_post_meta( $post_id , $key , $value );
			
		} // end foreach
		
	} // end update_meta
	
	
	private function update_post( $post_id , $settings  ){
		
		if ( ! $this->check_can_save( $post_id ) ) return;
		
		if ( ! empty( $_POST['_cpb']['layout'] ) && ! empty( $settings[ '_cpb_pagebuilder' ] ) ){
		
			$items = $this->get_layout_items( $_POST['_cpb']['layout'] );
			
			$post_content = '';
			
			foreach( $items as $item ){
				
				$post_content .= $item->the_shortcode();
				
			} // end foreach
			
			$excerpt = $this->get_excerpt( $settings, $post_content );
			
			//var_dump( $post_content );
			
			//var_dump( $post_id );
			
			$post = array(
				'ID'           => $post_id,
				'post_content' => $post_content,
				'post_excerpt' => $excerpt,
			);
			
			
			// Update the post into the database
  			wp_update_post( $post );
		
		} // end if
		
	} // end update_post
	
	
	private function check_can_save( $post_id ){
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;

		} // end if
		
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return false;

		} // end if

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {

				return false;

			} // end if

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {

				return false;

			} // end if

		} // end if
		
		if ( ! isset( $_POST['cahnrs_pagebuilder_key'] ) || ! wp_verify_nonce( $_POST['cahnrs_pagebuilder_key'], 'save_cahnrs_pagebuilder_' .  $post_id ) ) {
		  
			 return false;
		  
		  }
		
		return true;
		
	}
	
	
	
	public function get_layout_items( $id_set ){
		
		$items = array();
		
		$item_ids = explode( ',' , $id_set );
			
		foreach( $item_ids as $item_id ){
			
			$item_data = explode( '_' , $item_id );
			
			$settings = ( ! empty( $_POST['_cpb'][ $item_id ]['settings'] ) ) ? $_POST['_cpb'][ $item_id ]['settings'] : array();
			
			$content = ( ! empty( $_POST['_cpb_content_' . $item_id ] ) ) ? $_POST['_cpb_content_' . $item_id ] : '';
			
			$item = $this->items->get_item( $item_data[0] , $settings , $content , false );
			
			if ( ! empty( $_POST['_cpb'][ $item_id ]['children'] ) ){
				
				$item->set_children( $this->get_layout_items( $_POST['_cpb'][ $item_id ]['children'] ) );
				
			} // end if
			
			$items[] = $item;
			
		} // end foreach
		
		return $items;
		
	} // end get_layout_items
	
	private function get_excerpt( $settings, $content ) {

		if ( ! empty( $settings['_cpb_m_excerpt'] ) ) {

			$excerpt = ( ! empty( $settings['_cpb_excerpt'] ) ) ? $settings['_cpb_excerpt'] : '';

		}  else {

			$excerpt = $this->clean_excerpt( $content );

		} // end if

		return $excerpt;

	} // end get_excerpt

	private function clean_excerpt( $content ) {

		$patt = '/\[.*?\]/i';

		$excerpt = preg_replace( $patt, ' ', $content );

		$excerpt = wp_trim_words( wp_strip_all_tags( $excerpt ), 55 );

		return $excerpt;

	} // end get_excerpt
	
}