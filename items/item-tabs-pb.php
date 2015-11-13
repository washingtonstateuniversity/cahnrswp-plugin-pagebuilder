<?php
class Item_Tabs_PB extends Item_PB {

	public $slug = 'tabs';

	public $name = 'Tabs';

	public $desc = 'Add a tab set';


	public function item( $settings, $content ) {
		
		$tabs_id = 'cpb-tabs-' . rand( 0 , 1000000 );
		
		$tabs = explode( ',' , $settings['tabs'] );
		
		$sections = explode( ',' , $settings['sections'] );
		
		$html = '<div class="cpb-tabs-wrapper" id="' . $tabs_id . '">';
		
			$html .= '<style type="text/css" scoped >.cpb-tabs-nav a {display:inline-block;padding:1rem;text-decoration:none;color:inherit;vertical-align:bottom; border-top: 1px solid #ccc;
						border-left: 1px solid #ccc;border-right: 1px solid #ccc;} .cpb-tab-content{ border: 1px solid #ccc;display:none;} .cpb-tab-content.active { display:block;}</style>';
		
			$html .= '<nav class="cpb-tabs-nav">';
			
			$active = 'active';
		
			foreach( $tabs as $index => $tab ){
				
				$html .= '<a href="#" class="' . $active . '">' . $tab . '</a>';
				
				$active = '';
				
			} // end foreach
			
			$html .= '</nav>';
			
			$html .= '<div class="cpb-tabs-content-wrapper">';
			
			$active = ' active';
			
			foreach( $sections as $section ){
				
					$post = get_post( $section );
					
					$html .= '<div class="cpb-tab-content' . $active . '">';
					
					if ( $post ){
					
						$html .= apply_filters( 'the_content' , $post->post_content );
					
					} // end if
					
					$html .= '</div>';
					
					$active = '';
					
				} // end foreach
			
			$html .= '</div>';
			
			$html .= '<script type="text/javascript">if("undefined"==typeof cpb_tabs)var cpb_tbsi=function(){jQuery("body").on("click",".cpb-tabs-nav a",function(e){e.preventDefault();var t=jQuery(this);t.addClass("active").siblings().removeClass("active");var b=t.closest(".cpb-tabs-wrapper").children(".cpb-tabs-content-wrapper").children(".cpb-tab-content").eq(t.index());b.show().siblings().hide();b.addClass("active").siblings().removeClass("active")})},cpb_tbs=new cpb_tbsi;</script>';
		
		$html .= '</div>';
		
		return $html;
		
		/*$posts = array();
		
		$tabs = $this->get_tabs( $settings );
		
		foreach( $tabs as $index => $tab ){
			
			$tab_array = $this->get_post_array( $tab );
			
			if ( $tab_array ){
			
				$posts[] = $this->get_post_array( $tab );
			
			} // end if
			
		} // end foreach
		
		$tabs_id = 'cpb-tabs-' . rand( 0 , 1000000 );
		
		$html = '<div class="cpb-tabs-wrapper" id="' . $tabs_id . '">';
		
			$html .= '<style type="text/css" scoped >.cpb-tabs-nav a {display:inline-block;padding:1rem;text-decoration:none;color:inherit;vertical-align:bottom; border-top: 1px solid #ccc;
						border-left: 1px solid #ccc;border-right: 1px solid #ccc;} .cpb-tab-content{ border: 1px solid #ccc;}</style>';
		
			$html .= '<nav class="cpb-tabs-nav">';
		
			foreach( $posts as $post ){
				
				$html .= '<a href="' . $post['link'] . '">' . $post['title'] . '</a>';
				
			} // end foreach
			
			$html .= '</nav>';
			
			$html .= '<div class="cpb-tabs-content-wrapper">';
			
				foreach( $posts as $post ){
					
					$html .= '<div class="cpb-tab-content">' . apply_filters( 'the_content' , $post['content'] ) . '</div>';
					
				} // end foreach
			
			$html .= '</div>';
			
			$html .= '<script type="text/javascript">if("undefined"==typeof cpb_tabs)var cpb_tbsi=function(){jQuery("body").on("click",".cpb-tabs-nav a",function(e){e.preventDefault();var t=jQuery(this);t.addClass("active").siblings().removeClass("active");var b=t.closest(".cpb-tabs-wrapper").children(".cpb-tabs-content-wrapper").children(".cpb-tab-content").eq(t.index());b.show().siblings().hide()})},cpb_tbs=new cpb_tbsi;</script>';
		
		$html .= '</div>';
		
		return $html;*/

	} // end item

	public function editor( $settings, $editor_content ) {

		$html = 'tabs';

		return $html;

	} // end editor

	public function form( $settings ) {
		
		//$tabs = $this->get_tabs( $settings );
		
		$tabs = explode( ',' , $settings['tabs'] );
		
		$sections = explode( ',' , $settings['sections'] );
		
		$html = '';
		
		for ( $i = 0; $i < 4 ; $i++ ){
			
			if ( ! empty( $tabs ) ){
				
				$title = ( isset( $tabs[$i] ) ) ? $tabs[$i] : '';
				
				$id = ( isset( $sections[$i] ) ) ? $sections[$i] : '';
				
			} else {
				
				$title = '';
				
				$id = '';
				
			} // end if
			
			$html .= Forms_PB::text_field( $this->get_name_field('[tabs][' . $i . '][title]' , true , true ) , $title , 'Tab Label' );
		
			$html .= Forms_PB::text_field( $this->get_name_field('[tabs][' . $i . '][id]' , true , true ) , $id , 'Post/Page ID' ); 
		
			$html .= '<hr />';
			
		} // end for
		
		
		
		//$html = 'tabs';

		/*$tags = array(
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
		);

		
		$html = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$html .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , $tags , 'Tag Type' );
		
		$html .= Forms_PB::text_field( $this->get_name_field('anchor') , $settings['anchor'] , 'Anchor Link' ); 
		
		$html .= Forms_PB::text_field( $this->get_name_field('csshook') , $settings['csshook'] , 'CSS Hook' ); */
		
		return $html; 
		
	} // end form
	
	public function get_tabs( $settings ){
		
		$tabs = array();
		
		if ( ! empty( $settings['tabs'] ) ){
			
			$tabs_array = explode('|' , $settings['tabs'] );
			
			foreach( $tabs_array as $index => $tab_a ){
				
				$tab = explode( '::' , $tab_a );
				
				if ( count( $tab ) > 1 ){
					
					$tabs[$index]['title'] = $tab[0];
					
					$tabs[$index]['id'] = $tab[1];
					
				} // end if
				
			} // end foreach
			
		} // end if
		
		return $tabs;
		
	} // end get_tabs
	
	public function get_post_array( $tab ){
		
		$post_array = array();
		
		if( ! empty( $tab['id'] ) ){
				
			$post = get_post( $tab['id'] ); 
			
			if ( $post ){
			
				$post_array['title'] = $tab['title'];
				
				$post_array['content'] = $post->post_content;
				
				$post_array['link'] = get_post_permalink( $tab['id'] );
			
			} // end if
			
		} // end if
		
		return $post_array;
		
	} // end get_post_array
	

	public function clean( $s ) { 
		
		if ( isset( $s['tabs'] ) && is_array( $s['tabs'] ) ){
			
			$tabs = array();
			
			$sections = array();
			
			foreach( $s['tabs'] as $tab ){
				
				if ( ! empty( $tab['title'] ) && ! empty( $tab['id'] ) ){
					
					$tabs[] = htmlspecialchars ( $tab['title'] , ENT_QUOTES );
					
					$sections[] = htmlspecialchars ( $tab['id'] , ENT_QUOTES );
					
				}
				
				//$tabs[] = $tab['title'] . '::' . $tab['id'];
				
			} // end foreach
			
			$s['tabs'] = implode( ',' , $tabs );
			
			$s['sections'] = implode( ',' , $sections );
			
		} // end if

		$clean = array();
		
		$clean['tabs'] = ( isset( $s['tabs'] ) )? sanitize_text_field( $s['tabs'] ) : '';
		
		$clean['sections'] = ( isset( $s['sections'] ) )? sanitize_text_field( $s['sections'] ) : '';
		
		return $clean;

	} // end clean

}