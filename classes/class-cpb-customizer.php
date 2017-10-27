<?php

class CPB_Customizer {
	
	
	public function init(){
		
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		
	} // end init
	
	
	public function customize_register( $wp_customize ){
		
		// Add Settings
		
		$wp_customize->add_setting( 'cpb_spine_style' , array(
			'default'   => '',
			'transport' => 'refresh',
		) );
		
		// Add Section
		
		$wp_customize->add_section( 'cpb_builder' , array(
			'title'      => 'CAHNRS Pagebuilder',
			'priority'   => 30,
		) );
		
		// Add Controls
				
		$wp_customize->add_control(
			'cpb_spine_style_control', 
			array(
				'label'    => 'Use Spine Layout CSS',
				'section'  => 'cpb_builder',
				'settings' => 'cpb_spine_style',
				'type'     => 'select',
				'choices'  => array(
					'default'  => 'Default',
					'enable' => 'Enable',
					'disable'  => 'Disable',
				),
			)
		);
		
	}
	
} // end CPB_Customizer