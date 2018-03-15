<?php

class CPB_Remote_Request {
	
	public $args = array();
	
	public $context = 'not-set';
	
	public $response = array();
	
	
	public function __construct( $args, $context = 'not-set' ) {
		
		$this->args = $args;
		
		$this->context = $context;
		
		$this->response = $this->get_response();
		
	} // End __construct
	
	
	protected function get_response(){
		
		$request_type = ( ! empty( $this->args['request_type'] ) )? $this->args['request_type'] : 'post';
		
		switch( $request_type ){
			
			case 'post':
			default:
				$response = $this->get_post_response();
				break;
			
		} // End switch
		
		return $response;
		
	} // End get_response
	
	
	protected function get_post_response(){
		
		$post_type = ( ! empty( $this->args['post_type'] ) )? $this->args['post_type'] : 'article';
		
		switch( $request_type ){
			
			case 'article':
			default:
				$response = $this->get_article_response();
				break;
			
		} // End switch
		
		return $response;
		
	} // End get_post_response

	
	protected function get_article_response() {
		
		$default_args = array(
			'url' 					=> 'http://cahnrs.wsu.edu/news-stage',
			'request_base'			=> '/wp-json/wp/v2/',
			'request_type' 			=> 'post',
			'post_type' 			=> 'article',
			'categories' 			=> '',
			'category_relation' 	=> 'OR',
			'tags' 					=> '',
			'tag_relation' 			=> 'OR',
			'per_page' 				=> 10,
			'order_by' 				=> 'date',
			'order' 				=> 'ASC',
			'offset' 				=> 0,
			'exclude' 				=> '',
			'article_placement' 	=> '',
			'article_topic'			=> '',
			'article_subject' 		=> '',
			'subject_relation'		=> 'OR',
			'topic_relation' 		=> 'OR',
			'article_relation' 		=> 'AND',
			'sites' 				=> '',
			'site_relation' 		=> 'OR',
		);
		
		$this->args = $this->get_args( $default_args );
		
		$request_url = $this->get_article_query_url();
		
		$response = $this->get_remote_response( $request_url );
		
		return $response;
		
	}
	
	protected function get_article_query_url(){
		
		$request_url = $this->build_request_url();
		
		$add_fields = array( 'article_placement','article_topic','article_subject','subject_relation','topic_relation','article_relation','sites','site_relation' );
		
		$query_params = $this->get_basic_post_query( $add_fields );
		
		if ( ! empty( $query_params ) ){
			
			$request_url .= '?' . http_build_query( $query_params );
			
		} // End if
		
		return $request_url;
		
	} // End get_article_query
	
	
	protected function get_args( $defaut_args ){
		
		$args = apply_filters( 'ignite_remote_request_args', $this->args, $defaut_args , $this->context );
		
		foreach( $defaut_args as $key => $value ){
			
			if ( ! empty( $args[ $key ] ) ){
				
				$defaut_args[ $key ] = $args[ $key ];
				
			} // End if
			
		} // End foreach
		
		return $defaut_args;
		
	} // End get_args
	
	
	protected function build_request_url(){
		
		$remote_url = '';
		
		if ( 'post' === $this->args['request_type'] ) { 
		
			$remote_url = $this->args['url'] . $this->args['request_base'] . $this->args['post_type'];
		
		} // End if
		
		return $remote_url;
		
	} // End build_request_url
	
	
	protected function get_basic_post_query( $add_fields = array() ){
		
		$base_fields = array( 'per_page', 'exclude' );
		
		$fields = array_merge( $add_fields, $base_fields );
		
		$query = array();
		
		foreach( $fields as $field ){
			
			if ( ! empty( $this->args[ $field ] ) ){
			
				$query[ $field ] = $this->args[ $field ];
				
			} // End if
			
		} // End foreach
		
		return $query;
		
	} // End get_basic_post_query
	
	
	private function get_remote_response( $request_url ){
		
		$response = array();
		
		$request_response = wp_remote_get( $request_url );
		
		if( ! is_wp_error( $request_response ) ) {
			
			$json = wp_remote_retrieve_body( $request_response );
			
			$response_json = json_decode( $json, true );
			
			if ( is_array( $response_json ) ){
				
				$response = $response_json;
				
			} // End if
			
		} // End if
		
		return $response;
		
	} // End get_response
	
	
	/*public function get_request_response( $args, $context = 'not-set' ) {
		
		//var_dump( $args );
		
		$response = array();
		
		$default_args = array(
			'url' 				=> 'http://cahnrs.wsu.edu/news',
			'request_base'		=> '/wp-json/wp/v2/',
			'request_type' 		=> 'posts',
			'post_type' 		=> 'article',
			'categories' 		=> '',
			'category_relation' => 'OR',
			'tags' 				=> '',
			'tag_relation' 		=> 'OR',
			'per_page' 			=> 10,
			'order_by' 			=> 'date',
			'order' 			=> 'ASC',
			'offset' 			=> 0,
			'exclude' 			=> '',
		);
		
		$args = $this->get_args( $default_args, $args, $context );
		
		//var_dump( $args );
		
		switch( $args['request_type'] ){
			
			case 'posts':
				$response = $this->get_posts_request_response( $args, $context );
				break;
				
		} // End switch
		
		return $response;
		
	} // End get_request*/
	
	
	/*private function get_article_response( $args, $context ){
		
		$request_url = $this->build_request_url( $args, $context );
		
	} // End get_article_response
	
	
	private function get_article_query( $args, $context ){
		
		$query_params = $this->get_basic_post_query( $args, $context );
		
	} // End get_article_query
	
	
	private function get_basic_post_query( $args, $context ){
		
		$query = array();
		
		if ( ! empty( $args['per_page'] ) ){
			
			$query['per_page'] = $args['per_page'];
			
		} // End if
		
		if ( ! empty( $args['exclude'] ) ){
			
			$query['exclude'] = $args['exclude'];
			
		} // End if
		
		return $query;
		
	} // End get_basic_post_query
	
	
	private function build_request_url( $args, $context ){
		
		$remote_url = '';
		
		if ( 'post' === $args['request_type'] ) { 
		
			$remote_url = $args['url'] . $args['request_base'] . $args['post_type'];
		
		} // End if
		
		return $remote_url;
		
	} // End build_request_url
	
	
	private function get_response( $request_url ){
		
		$response = array();
		
		if( ! is_wp_error( $request_response ) ) {
			
			$json = wp_remote_retrieve_body( $request_response );
			
			$response_json = json_decode( $json, true );
			
			if ( is_array( $response_json ) ){
				
				$response = $response_json;
				
			} // End if
			
		} // End if
		
		return $response;
		
	} // End get_response
	
	
	private function get_posts_request_response( $args, $context ){
		
		$response = array();
		
		$request_url = $this->get_posts_request_response_url( $args, $context );
		
		//var_dump( $request_url );
		
		$request_response = wp_remote_get( $request_url );
		
		if( ! is_wp_error( $request_response ) ) {
			
			$json = wp_remote_retrieve_body( $request_response );
			
			$response_json = json_decode( $json, true );
			
			if ( is_array( $response_json ) ){
				
				$response = $response_json;
				
			} // End if
			
		} // End if
		
		return $response;
		
	} // End get_posts_request_response
	
	
	private function get_posts_request_response_url( $args, $context ){
		
		//var_dump( $args );
		
		$remote_url = $args['url'] . $args['request_base'] . $args['post_type'];
		
		$query_params = array();
		
		if ( ! empty( $args['per_page'] ) ){
			
			$query_params['per_page'] = $args['per_page'];
			
		} // End if
		
		if ( ! empty( $args['exclude'] ) ){
			
			$query_params['exclude'] = $args['exclude'];
			
		} // End if
		
		if ( ! empty( $query_params ) ){
			
			$remote_url .= '?' . http_build_query( $query_params );
			
		} // End if
		
		return $remote_url;
		
	} // End get_posts_request_response_url
	
	
	private function get_args( $defaut_args, $args, $context ){
		
		$args = apply_filters( 'ignite_remote_request_args', $args, $defaut_args , $context );
		
		foreach( $defaut_args as $key => $value ){
			
			if ( ! empty( $args[ $key ] ) ){
				
				$defaut_args[ $key ] = $args[ $key ];
				
			} // End if
			
		} // End foreach
		
		return $defaut_args;
		
	} // End get_args*/
	
} // End Remote_Request_CAHNRS_Ignite