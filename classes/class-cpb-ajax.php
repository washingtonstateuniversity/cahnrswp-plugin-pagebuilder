<?php 
class CPB_Ajax {
	
	protected $items;
	
	public function __construct( $items ){
		
		$this->items = $items;
		
	} // end __construct
	
	
	public function do_request(){
		
		CAHNRS_Pagebuilder_Plugin::$is_ajax = true;
		
		if ( ! empty( $_POST['service'] ) ){
			
			switch( $_POST['service'] ) {
				
				case 'get_part':
					$this->check_nonce();
					$this->request_part();
					break;
				
				case 'get_content':
					$this->check_nonce();
					$this->request_content();
					break;
					
				case 'get_style':
					$this->get_style();
					break;
					
				case 'search_posts':
					$this->search_posts();
					break;
				case 'remote_request':
					$this->remote_request();
					break;
				
			} // end switch
			
		} // end service
		
		CAHNRS_Pagebuilder_Plugin::$is_ajax = false;
		
	} // end do_request
	
	
	public function request_part(){
		
		$json = array();
		
		if ( ! empty( $_POST['slug'] ) ){
		
			$settings = ( ! empty( $_POST['settings'] ) ) ? $_POST['settings'] : array();
			
			$content = ( ! empty( $_POST['content'] ) ) ? $_POST['content'] : '';
			
			$get_children = ( isset( $_POST['get_children'] ) ) ? $_POST['get_children'] : true;
			
			$item = $this->items->get_item( $_POST['slug'], $settings , $content , $get_children );
			
			$items = $this->items->get_items( true );
			
			$is_content_item = ( array_key_exists( $item->get_slug() , $items ) ) ? 1 : 0 ;
			
			if ( $item ){
				
				$json['id'] = $item->get_id();
				
				$json['is_content'] = $is_content_item;
				
				$json['editor'] = $item->the_editor();
				
				$json['forms'] = $item->the_form();
				
			} // end if
		
		} // end if
		
		echo json_encode( $json );
		
	}
	
	public function request_content(){
		
		CAHNRS_Pagebuilder_Plugin::$is_editor = true;
		
		$items = array();
		
		if ( ! empty( $_POST['_cpb']['items'] ) ){
			
			foreach( $_POST['_cpb']['items'] as $id => $item_slug ){
				
				$settings = ( ! empty( $_POST['_cpb'][$id]['settings'] ) ) ? $_POST['_cpb'][$id]['settings'] : array();
				
				$content = ( ! empty( $_POST['_cpb_content_' . $id ] ) ) ? $_POST['_cpb_content_' . $id ] : '';
				
				$item = $this->items->get_item( $item_slug , $settings , $content );
				
				if ( $item ){
					
					$items[ $id ] = $item->the_item( false , false , true );
				
				} // end if
				
			} // end foreach
			
		} // end if
		
		echo json_encode( $items );
		
	} 
	
	public function request_style(){
		
		do_action( 'wp_enqueue_scripts' );
		
		global $wp_styles;
		
		$stylesheets = array();
		
		foreach( $wp_styles->registered as $key => $style ){
			
			if ( strpos ( $style->src , 'wp-admin' ) || strpos ( $style->src , 'wp-includes' ) ) continue;
			
			$stylesheets[ $key ] = '<link rel="stylesheet" id="' . $key . '"  href="' . $style->src . '" type="text/css" media="all" />';
			
		} // end foreach*/
		
		echo json_encode( $stylesheets );
		
	} // end request_style 
	
	public function get_style(){
		
		/*do_action( 'wp_enqueue_scripts' );
		
		global $wp_styles;
		
		$stylesheets = array();
		
		foreach( $wp_styles->registered as $key => $style ){
			
			if ( strpos ( $style->src , 'wp-admin' ) || strpos ( $style->src , 'wp-includes' ) ) continue;
			
			$stylesheets[] = '<link rel="stylesheet" id="' . $key . '"  href="' . $style->src . '" type="text/css" media="all" />';
			
		} // end foreach*/
		
		//return $stylesheets;
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-stylesheet.php';

		$style = new CPB_Stylesheet();

		$css = $style->get_style( 'editor' );
		
		echo $css;
		
	} // end get_stylesheets
	
	public function search_posts(){
		
		if ( ! empty( $_POST['site'] ) ){
			
			$site = sanitize_text_field( $_POST['site'] );
			
			$search = ( ! empty( $_POST['cpb-search'] ) ) ? sanitize_text_field( $_POST['cpb-search'] ):'';
		
			$response = wp_remote_get( $site . '/wp-json/posts?filter[s]=' . $search );
			
			if ( ! is_wp_error( $response ) ) {
			
				$body = wp_remote_retrieve_body($response);
			
				echo $body;
			
			} // end if
		
		} // end if
		
	} // end search_posts
	
	protected function remote_request(){
		
		if ( ! empty( $_POST['type'] ) && ! empty( $_POST['site'] ) ){
			
			$site = sanitize_text_field( $_POST['site'] ) . '/wp-json/';
			
			switch( $_POST['type'] ){
				
				case 'post_types':
					$request = 'posts/types';
					break;
					
				case 'taxonomies':
					$request = 'taxonomies';
					break;
				default: 
					$request = false;
			}
			
			if ( $request ){
				
				$response = wp_remote_get( $site . $request );
			
				if ( ! is_wp_error( $response ) ) {
				
					$body = wp_remote_retrieve_body($response);
					
					switch( $_POST['type'] ){
						case 'post_types':
							$this->remote_request_post_type( $body );
							break;
						case 'taxonomies':
							$this->remote_request_tax( $body );
							break;
					}// end switch
				
				} // end if
				
			} // 
			
		}// end if
		
	} // end remote_request
	
	protected function remote_request_post_type( $body ){
		
		$json = json_decode( $body , true );
					
		$post_types = array();
		
		foreach( $json as $key => $pt ){
			
			if ( $pt['queryable'] ) $post_types[] = $key;
			
		} // end foreach
		
		if ( $post_types ){
			
			echo json_encode( $post_types );
			
		} // end if
		
	} // end remote_request_post_type
	
	protected function remote_request_tax( $body ){
		
		$json = json_decode( $body , true );
					
		$tax = array();
		
		foreach( $json as $key => $t ){
			
			$tax[] = $t['slug'];
			
		} // end foreach
		
		if ( $tax ){
			
			echo json_encode( $tax );
			
		} // end if
		
	} // end remote_request_post_type
	
	protected function check_nonce(){ 
		
		if ( empty( $_POST['ajax-post-id'] ) ) die();
		
		$post_id = $_POST['ajax-post-id'];
		
		check_ajax_referer( 'cahnrs_pb_ajax_' . $post_id , 'ajax-nonce' );
		
	} // end check nonce
	
}