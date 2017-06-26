<?php

class Request_CAHNRSWP_People {
	
	protected $base_url = 'https://people.wsu.edu/';
	
	protected $people_url = 'wp-json/wp/v2/people'; 
	
	
	public function get_people( $args ){
		
		$request_url = $this->get_the_request_url( $args );
		
		$request = wp_remote_request( $request_url );
		
		$request_json = wp_remote_retrieve_body( $request );
		
		$profiles = json_decode( $request_json, true );
		
		if ( ! is_array( $profiles ) ) $profiles = array(); 
		
		//var_dump( $profiles );  
		
		return $profiles;
		
	} // end get_people
	
	
	protected function get_the_request_url( $args ){
		
		$request_url = $this->base_url . $this->people_url;
		
		if ( ! empty( $args['tags'] ) ) {
			
			$request_url = add_query_arg( array( 'filter[tag]' => sanitize_key( $args['tags'] ) ), $request_url );
			
		} // end if
		
		if ( ! empty( $args['classification'] ) ) {
			
			$request_url = add_query_arg( array(
				
				'filter[taxonomy]' => 'classification',
				'filter[term]' => sanitize_key( $args['classification'] ),
			), $request_url );
				
		} // end if

		if ( $args['count'] ) {
			
			$request_url = add_query_arg( array( 'per_page' => absint( $args['count'] ) ), $request_url );
			
		} // end if 
		
		return $request_url;
		
	} // end get_request_url
	
	
	protected function get_the_request_query( $args ){
	} // end get_the_request_query
	
} // end class Request_CAHNRSWP_People
