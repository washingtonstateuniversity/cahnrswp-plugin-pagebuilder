<?php
class Item_Promo_PB extends Item_PB {
	
	public $slug = 'promo';
	
	public $name = 'Promo';
	
	public $desc = 'Adds Promo Objects';
	
	public $form_size = 'medium';
	
	public function item( $settings , $content ){
		
		$html .= '';
		
		if ( ! empty( $settings['title'] ) ){
			
			$html .= '<' . $settings['tag'] . '>' . $settings['title'] . '</' . $settings['tag'] . '>';
			
		} // end if
		
		$query = new Query_PB( $settings );
		
		$promos = $query->get_query_items();
		
		if ( $promos ){
			
			foreach( $promos as $promo ){
				
				$html .= $this->get_promo( $promo , $settings );
				
			} // end foreach
			
		} // end if
		
		
		
		//$display = new Display_PB( $settings );
			
		//$items = $query->get_query_items();
		
		//$html .= $display->get_display( $items , $settings );
		
		return $html;
		
	} // end item
	
	public function editor( $settings , $editor_content ){
		
		/*$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';
		
		$html = '<h2>' . $title . '</h2>';*/
		
		$html = $this->get_dynamic_editor( $this->the_item() );
		
		return $html;
		
	} // end editor
	
	public function form( $settings ){
		
		$source .= $this->accordion_radio( 
			$this->get_name_field('source') , 
			'feed' , 
			$settings['source'] , 
			'Feed (This Site)' , 
			Forms_PB::local_feed( $this->get_name_field() , $settings ),
			'I want to dynamically include other posts/pages from this site based on categories, tages, or content type.' 
			); 
			
		$source .= $this->accordion_radio( 
			$this->get_name_field('source') , 
			'remote_feed' , 
			$settings['source'] , 
			'Feed (Another Site)' , 
			Forms_PB::remote_feed( $this->get_name_field() , $settings ),
			'I want to dynamically include posts/pages from another site.' 
			);
		
		/*$feed_source = array(
			'form'        => Forms_PB::local_feed( $this->get_name_field() , $settings ),
			'field_name'  => $this->get_name_field('source'),
			'val'         => 'feed',
			'current_val' => $settings['source'],
			'title'       => 'Feed (This Site)',
			'summary'     => 'I want to dynamically include other posts/pages from this site based on categories, tages, or content type.'
		);
		
		$ext_feed_source = array(
			'form'        => Forms_PB::remote_feed( $this->get_name_field() , $settings ),
			'field_name'  => $this->get_name_field('source'),
			'val'         => 'remote_feed',
			'current_val' => $settings['source'],
			'title'       => 'Feed (Another Site)',
			'summary'     => 'I want to dynamically include posts/pages from another site.'
		);*/
		
		//$source .= Forms_PB::get_subform( $feed_source ); 
		
		//$source .= Forms_PB::get_subform( $ext_feed_source ); 
		
		$display = Forms_PB::text_field( $this->get_name_field('title') , $settings['title'] , 'Title' );
		
		$display .= Forms_PB::select_field( $this->get_name_field('tag') , $settings['tag'] , array('h2' => 'H2','h3'=>'H3','h4'=>'H4' ) , 'Tag Type' );
		
		$display .= '<hr />';
		
		$display .= Forms_PB::select_field( $this->get_name_field('headline_tag') , $settings['headline_tag'] , array('h2' => 'H2','h3'=>'H3','h4'=>'H4','h5'=>'H5' ,'strong' => 'Bold' ) , 'Headline Tag' );
		
		$display .= Forms_PB::select_field( $this->get_name_field('promo_class') , $settings['promo_class'] , array('large' => 'Large','small'=>'Small','full'=> 'Full' ) , 'Promo Style' );
		
		$adv = Forms_PB::checkbox_field( $this->get_name_field('hide_excerpt'), 1, $settings['hide_excerpt'], 'Hide Summary' );
		
		$adv .= Forms_PB::checkbox_field( $this->get_name_field('hide_image'), 1, $settings['hide_image'], 'Hide Image' );
		
		$adv .= Forms_PB::checkbox_field( $this->get_name_field('hide_link'), 1, $settings['hide_link'], 'Remove Link' );
		
		$form = array( 
			'Source'   => $source,
			'Display'  => $display,
			'Advanced' => $adv
		); 
		
		return $form; 
		
	} // end form
	
	
	
	public function clean( $s ){
		
		$clean = array();
		
		/*$clean['tag'] = ( ! empty( $s['tag'] ) )? sanitize_text_field( $s['tag'] ) : 'h2';
		
		$clean['title'] = ( ! empty( $s['title'] ) )? sanitize_text_field( $s['title'] ) : '';
		
		$clean['csshook'] = ( ! empty( $s['csshook'] ) )? sanitize_text_field( $s['csshook'] ) : '';*/
		
		$clean['display_type'] = 'promo';
		
		$clean['title'] = ( ! empty( $s['title'] ) ) ? $s['title'] : '';
		
		$clean['tag'] = ( ! empty( $s['tag'] ) ) ? $s['tag'] : 'h2';
		
		$clean['headline_tag'] = ( ! empty( $s['headline_tag'] ) ) ? $s['headline_tag'] : 'strong';
		
		$clean['hide_excerpt'] = ( ! empty( $s['hide_excerpt'] ) ) ? $s['hide_excerpt'] : '';
		
		$clean['hide_image'] = ( ! empty( $s['hide_image'] ) ) ? $s['hide_image'] : '';
		
		$clean['hide_link'] = ( ! empty( $s['hide_link'] ) ) ? $s['hide_link'] : '';
		
		if ( ! empty( $s['promo_class'] ) ) $clean['promo_class'] = sanitize_text_field( $s['promo_class'] );
		
		$clean['posts_per_page'] =  ( ! empty( $s['posts_per_page'] ) ) ? sanitize_text_field( $s['posts_per_page'] ) : 5;
		
		if ( ! empty( $s['source'] ) ) $clean['source'] = sanitize_text_field( $s['source'] );
		
		if ( ! empty( $s['post_type'] ) ) $clean['post_type'] = sanitize_text_field( $s['post_type'] );
		
		if ( ! empty( $s['taxonomy'] ) ) $clean['taxonomy'] = sanitize_text_field( $s['taxonomy'] );
		
		if ( ! empty( $s['terms'] ) ) $clean['terms'] = sanitize_text_field( $s['terms'] );
		
		if ( isset( $s['term_operator'] ) ) $clean['term_operator'] = sanitize_text_field( $s['term_operator'] );
			
		if ( ! empty( $s['remote_url'] ) ) $clean['remote_url'] = sanitize_text_field( $s['remote_url'] );
		
		if ( ! empty( $s['remote_post_type'] ) ) $clean['remote_post_type'] = sanitize_text_field( $s['remote_post_type'] );
		
		if ( ! empty( $s['remote_taxonomy'] ) ) $clean['remote_taxonomy'] = sanitize_text_field( $s['remote_taxonomy'] );

		if ( ! empty( $s['remote_terms'] ) ) $clean['remote_terms'] = sanitize_text_field( $s['remote_terms'] );
		
		$clean['remote_posts_per_page'] = ( ! empty( $s['remote_posts_per_page'] ) ) ? sanitize_text_field( $s['remote_posts_per_page'] ) : 5;
		
		return $clean;
		
	} // end clean
	
	private function get_promo( $promo , $settings ){
		
		$link_start = ( ! empty( $promo['link'] ) ) ? '<a href="' . $promo['link'] . '">' : '';
		
		$link_end = ( ! empty( $promo['link'] ) ) ? '</a>' : '';
		
		$html = '<article class="cpb-promo ' . implode( ' ' , $this->get_promo_class( $promo , $settings ) ). '">';
		
		if ( ! empty( $promo['image'] ) ) {
			
			$html .= $link_start . '<img src="' . CWPPBURL . 'images/3x4spacer.png" style="background-image:url(' . $promo['image'] . ');" />' . $link_end;
			
		} // end if
		
		$html .= '<div>';
		
		if ( ! empty( $promo['title'] ) ) {
			
			$tag = ( ! empty( $this->settings['headline_tag'] ) ) ? $this->settings['headline_tag'] : 'strong';
						
			$html .= '<' . $tag . '>' . $link_start . $promo['title'] . $link_end . '</' . $tag . '>';
			
			if ( 'strong' == $tag ) $html .= '<br />';
			
		} // end if
		
		if ( ! empty( $promo['excerpt'] ) ) {
			
			$html .= $this->get_excerpt( $promo , $settings );
			
		} // end if
		
		$html .= '</div>';
		
		$html .= '</article>';
		
		return $html;
		
	}
	
	private function get_promo_class( $promo , $settings ){
		
		$class = array();
		
		if ( ! empty( $promo['image'] ) ) $class[] = 'cpb-image-promo';
		
		if ( ! empty( $settings['promo_class'] ) ) $class[] = $settings['promo_class'];
		
		return $class;
		
	}
	
	private function get_excerpt( $promo , $settings ){
		
		$text = wp_strip_all_tags( strip_shortcodes( $promo['excerpt'] ) );
		
		$excerpt = wp_trim_words( $text , 25 );
		
		return $excerpt;
		
	}
	
}