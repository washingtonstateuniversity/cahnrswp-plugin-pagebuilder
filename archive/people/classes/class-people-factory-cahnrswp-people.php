<?php

class People_Factory_CAHNRSWP_People {
	
	protected $request;
	
	
	public function __construct( $request ){
		
		require_once 'class-person-cahnrswp-people.php';
		
		$this->request = $request;
		
	} // end __construct
	
	
	public function get_person(){
		
		$person = new Person_CAHNRSWP_People();
		
		return $person;
		
	} // end get_person
	
	
	public function get_people( $args ){
		
		$people = array();
		
		$requested_people = $this->request->get_people( $args ); 
		
		$requested_people = $this->sort_people( $requested_people, $args );
		
		foreach( $requested_people as $index => $profile ){
			
			$person = $this->get_person();
			
			$person->set_the_person( $profile );
			
			$people[] = $person;
			
		} // end foreach
		
		return $people;
		
	} // end get_people_by_atts
	
	
	protected function sort_people( $profiles, $args = array() ){
		
		$args['sort'] = ( empty( $args['sort'] ) ) ? 'last-name' : $args['sort'];
		
		switch( $args['sort'] ){
			
			case 'last-name':
				uasort( $profiles, function( $a, $b ) {
    				return ( $a['last_name'] < $b['last_name'] ) ? -1 : 1;
				});
				break;
			
		} // end switch
		
		return $profiles;
		
	} // end sort_people
	
	
} // end People_Factory_CAHNRSWP_People