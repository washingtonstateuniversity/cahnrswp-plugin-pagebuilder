<?php

class Person_CAHNRSWP_People {
	
	protected $bio_department = '';
	protected $bio_unit = '';
	protected $email = '';
	protected $first_name = '';
	protected $last_name = '';
	protected $office = '';
	protected $phone = '';
	protected $photo = '';
	protected $title = '';
	
	public function get_bio_department() { return $this->bio_department; }
	public function get_bio_unit() { return $this->bio_unit; }
	public function get_email() { return $this->email; }
	public function get_first_name() { return $this->first_name; }
	public function get_last_name() { return $this->last_name; }
	public function get_office() { return $this->office; }
	public function get_phone() { return $this->phone; }
	public function get_photo() { return $this->photo; }
	public function get_title() { return $this->title; }
	
	public function set_bio_department( $value ) { $this->bio_department = $value; }
	public function set_bio_unit( $value ) { $this->bio_unit = $value; }
	public function set_email( $value ) { $this->email = $value; }
	public function set_first_name( $value ) { $this->first_name = $value; }
	public function set_last_name( $value ) { $this->last_name = $value; }
	public function set_office( $value ) { $this->office = $value; }
	public function set_phone( $value ) { $this->phone = $value; }
	public function set_photo( $value ) { $this->photo = $value; }
	public function set_title( $value ) { $this->title = $value; }
	
	
	public function set_the_person( $profile ){
		
		var_dump( $profile );
		
		$bio_unit = ( ! empty( $profile[ 'bio_unit' ] ) ) ? $profile[ 'bio_unit' ] : '';
		$this->set_bio_unit( $bio_unit );
		
		$bio_department = ( ! empty( $profile[ 'bio_department' ] ) ) ? $profile[ 'bio_department' ] : '';
		$this->set_bio_department( $bio_department );
		
		$this->set_email( $profile[ 'email' ] );
		$this->set_first_name( $profile[ 'first_name' ] );
		$this->set_last_name( $profile[ 'last_name' ] );
		$this->set_office( $profile[ 'office' ] );
		$this->set_phone( $profile[ 'phone' ] );
		
		$photo = ( ! empty( $profile[ 'profile_photo' ] ) ) ? $profile[ 'profile_photo' ] : 'https://people.wsu.edu/wp-content/uploads/sites/908/2015/07/HeadShot_Template2.jpg';
		$this->set_photo( $photo );
		
		$this->set_title( $profile[ 'position_title' ] );
		
	} // end set_the_person
	
} // end Person_CAHNRSWP_People