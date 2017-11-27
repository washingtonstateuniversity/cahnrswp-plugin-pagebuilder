<?php

class CPB_Item_Tabs extends CPB_Item {
	
	protected $name = 'Tabs';
	
	protected $slug = 'cpbtabs';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		$tabs = array();
		
		for ( $i = 1; $i < 5; $i++ ){
			
			$prefix = 'tab' . $i;
			
			if ( ! empty( $settings[ $prefix . '_title'] ) ){
				
				$tab = array(
					'title' => $settings[ $prefix . '_title'],
					'url' => ( ! empty( $settings[ $prefix . '_url'] ))? $settings[ $prefix . '_url'] : '',
					'posts' => ( ! empty( $settings[ $prefix . '_posts'] ))? $settings[ $prefix . '_posts'] : '',
				);
				
				$tabs[ $prefix ] = $tab;
				
			} // End if
			
		} // End for
		
		$display = ( ! empty( $settings['display'] ) )? $settings['display'] : 'basic';
		
		switch( $display ){
				
			case 'columns-4':
				$html .= $this->get_display_columns( $tabs, $settings , $content );
				break;
				
			default:
				break;
				
		} // End switch
		
		return $html;
		
	}// end item
	
	
	protected function get_display_columns( $tabs, $settings , $content ) {
		
		ob_start();
		
		include 'displays/columns-4.php';
		
		$html = ob_get_clean();
		
		return $html;
		
	} // End get_display_columns
	
	
	public function form( $settings , $content ){
		
		$posts = cpb_get_public_posts( array(), true );
		
		
		//var_dump( $posts );
		
		$html = '';
		
		$adv = $this->get_form_advanced( $settings , $content );
		
		$html = $this->form_fields->text_field( $this->get_input_name('tab1_title') , $settings['tab1_title'] , 'Tab 1 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab1_url') , $settings['tab1_url'] , 'Tab 1 Link' );
		
		$settings['tab1_posts'] = explode(',', $settings['tab1_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab1_posts') , $settings['tab1_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab2_title') , $settings['tab2_title'] , 'Tab 2 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab2_url') , $settings['tab2_url'] , 'Tab 2 Link' );
		
		$settings['tab2_posts'] = explode(',', $settings['tab2_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab2_posts') , $settings['tab2_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab3_title') , $settings['tab3_title'] , 'Tab 3 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab3_url') , $settings['tab3_url'] , 'Tab 3 Link' );
		
		$settings['tab3_posts'] = explode(',', $settings['tab3_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab3_posts') , $settings['tab3_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab4_title') , $settings['tab4_title'] , 'Tab 4 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab4_url') , $settings['tab4_url'] , 'Tab 4 Link' );
		
		$settings['tab4_posts'] = explode(',', $settings['tab4_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab4_posts') , $settings['tab4_posts'] , $posts , 'Select Posts' );
		
		//$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		/*$styles = array(
			'' 									=> 'None',
			'underline-heading' 				=> 'Underlined Heading',
			'underline-heading small-heading' 	=> 'Underlined Heading (small font)',
		);
		
		
		
		 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('link') , $settings['link'] , 'Link' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('style'), $settings['style'], $styles, 'Style' );*/
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	
	protected function get_form_advanced( $settings , $content ){
		
		$displays = array(
			'basic' => 'Basic',
			'columns-4' => '4 Column',
		);
		
		$adv = '';
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('display'), $settings['display'], $displays, 'Display' );
		
		return $adv;
		
	} // End get_form_advanced
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$fields = array(
			'tab1_link',
			'tab1_title',
			'tab2_link',
			'tab2_title',
			'tab3_link',
			'tab3_title',
			'tab4_link',
			'tab4_title',
			'display'
		);
		
		foreach( $fields as $index => $field ){
			
			$clean[ $field ] = ( ! empty( $settings[ $field ] ) )? sanitize_text_field( $settings[ $field ] ) : '';
			
		} // foreach
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['tab1_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab1_posts'] );
		
		$clean['tab2_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab2_posts'] );
		
		$clean['tab3_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab3_posts'] );
		
		$clean['tab4_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab4_posts'] );
		
		return $clean;
		
	} // End clean
	
	
}