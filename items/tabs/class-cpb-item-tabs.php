<?php

class CPB_Item_Tabs extends CPB_Item {
	
	protected $name = 'Tabs';
	
	protected $slug = 'cpbtabs';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		$html = '<div class="cpb-item-tabs display-' . $settings['display'] . '">';
		
		$tabs = array();
		
		for ( $i = 1; $i < 6; $i++ ){
			
			$prefix = 'tab' . $i;
			
			if ( ! empty( $settings[ $prefix . '_title'] ) ){
				
				$tab = array(
					'title' => $settings[ $prefix . '_title'],
					'url' => ( ! empty( $settings[ $prefix . '_url'] ))? $settings[ $prefix . '_url'] : '',
					'posts' => ( ! empty( $settings[ $prefix . '_posts'] ))? $settings[ $prefix . '_posts'] : '',
					'bgcolor' => ( ! empty( $settings[ $prefix . '_bgcolor'] ))? $settings[ $prefix . '_bgcolor'] : '',
					'bgimage' => ( ! empty( $settings[ $prefix . '_img_src'] ))? $settings[ $prefix . '_img_src'] : '',
				);
				
				$tabs[ $prefix ] = $tab;
				
			} // End if
			
		} // End for
		
		$display = ( ! empty( $settings['display'] ) )? $settings['display'] : 'basic';
		
		switch( $display ){
				
			case 'columns':
				$html .= $this->get_display_columns( $tabs, $settings , $content );
				break;
				
			default:
				break;
				
		} // End switch
		
		return $html . '</div>';
		
	}// end item
	
	
	protected function get_display_columns( $tabs, $settings , $content ) {
		
		ob_start();
		
		include 'displays/columns.php';
		
		$html = ob_get_clean();
		
		return $html;
		
	} // End get_display_columns
	
	
	public function form( $settings , $content ){
		
		$posts = cpb_get_public_posts( array(), true, true );
		
		
		//var_dump( $posts );
		
		$html = '';
		
		$adv = $this->get_form_advanced( $settings , $content );
		
		
		for ( $i = 1; $i < 6; $i++ ){
			
			$html .= $this->form_fields->text_field( $this->get_input_name('tab' . $i . '_title') , $settings['tab' . $i . '_title'] , 'Tab ' . $i . ' Title' );
			
			$html .= $this->form_fields->text_field( $this->get_input_name('tab' . $i . '_url') , $settings['tab' . $i . '_url'] , 'Tab ' . $i . ' Link' );
			
			$html .= $this->form_fields->select_field( $this->get_input_name('tab' . $i . '_posts'), $settings['tab' . $i . '_posts'], $posts, 'Tab ' . $i . ' Content' );
			
			$html .= $this->form_fields->select_field( $this->get_input_name('tab' . $i . '_bgcolor'), $settings['tab' . $i . '_bgcolor'], $this->form_fields->get_wsu_colors(), 'Tab ' . $i . ' Background Color' );
			
			$html .= $this->form_fields->insert_media( $this->get_input_name(), $settings, '', 'tab' . $i . '_' );
			
		} // End for
		
		
		
		
		
		
		/*$html .= $this->form_fields->select_field( $this->get_input_name('tab1_bgcolor'), $settings['tab1_bgcolor'], $this->form_fields->get_wsu_colors(), 'Tab 1 Background Color' );
		
		$settings['tab1_posts'] = explode(',', $settings['tab1_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab1_posts') , $settings['tab1_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab2_title') , $settings['tab2_title'] , 'Tab 2 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab2_url') , $settings['tab2_url'] , 'Tab 2 Link' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tab2_bgcolor'), $settings['tab2_bgcolor'], $this->form_fields->get_wsu_colors(), 'Tab 2 Background Color' );
		
		$settings['tab2_posts'] = explode(',', $settings['tab2_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab2_posts') , $settings['tab2_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab3_title') , $settings['tab3_title'] , 'Tab 3 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab3_url') , $settings['tab3_url'] , 'Tab 3 Link' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tab3_bgcolor'), $settings['tab3_bgcolor'], $this->form_fields->get_wsu_colors(), 'Tab 3 Background Color' );
		
		$settings['tab3_posts'] = explode(',', $settings['tab3_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab3_posts') , $settings['tab3_posts'] , $posts , 'Select Posts' );
		
		$html .= '<hr />';
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab4_title') , $settings['tab4_title'] , 'Tab 4 Title' );
		
		$html .= $this->form_fields->text_field( $this->get_input_name('tab4_url') , $settings['tab4_url'] , 'Tab 4 Link' );
		
		$html .= $this->form_fields->select_field( $this->get_input_name('tab4_bgcolor'), $settings['tab4_bgcolor'], $this->form_fields->get_wsu_colors(), 'Tab 4 Background Color' );
		
		$settings['tab4_posts'] = explode(',', $settings['tab4_posts'] );
		
		$html .= $this->form_fields->select_posts_field( $this->get_input_name('tab4_posts') , $settings['tab4_posts'] , $posts , 'Select Posts' );
		
		//$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' );
		
		/*$styles = array(
			'' 									=> 'None',
			'underline-heading' 				=> 'Underlined Heading',
			'underline-heading small-heading' 	=> 'Underlined Heading (small font)',
		);*/
		
		
		
		
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	
	protected function get_form_advanced( $settings , $content ){
		
		$displays = array(
			'basic' => 'Basic',
			'columns' => 'Columns',
		);
		
		$adv = '';
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('display'), $settings['display'], $displays, 'Display' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('min_height'), $settings['min_height'], 'Minimum Height' );
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook') , $settings['csshook'] , 'CSS Hook' ); 
		
		return $adv;
		
	} // End get_form_advanced
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$fields = array(
			'display',
			'csshook',
			'textcolor',
			'min_height',
		);
		
		for ( $i = 1; $i < 6; $i++ ){
			
			$fields[] = 'tab' . $i . '_title';
			$fields[] = 'tab' . $i . '_url';
			$fields[] = 'tab' . $i . '_posts';
			$fields[] = 'tab' . $i . '_bgcolor';
			$fields[] = 'tab' . $i . '_img_src';
			$fields[] = 'tab' . $i . '_img_id';
			
		} // End for
		
		foreach( $fields as $index => $field ){
			
			$clean[ $field ] = ( ! empty( $settings[ $field ] ) )? sanitize_text_field( $settings[ $field ] ) : '';
			
		} // foreach
		
		//$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		//$clean['tab1_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab1_posts'] );
		
		//$clean['tab2_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab2_posts'] );
		
		//$clean['tab3_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab3_posts'] );
		
		//$clean['tab4_posts'] = $this->form_fields->clean_select_posts_field( $settings['tab4_posts'] );
		
		return $clean;
		
	} // End clean
	
	
}