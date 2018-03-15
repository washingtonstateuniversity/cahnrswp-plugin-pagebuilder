<?php

class CPB_Item_AZ_Index extends CPB_Item {
	
	protected $name = 'A-Z Index';
	
	protected $slug = 'az_index';
	
	protected $fields = array('title','img','link','excerpt','content');
	
	public function get_fields(){ return $this->fields; }
	
	
	public function item( $settings , $content ){
		
		$settings['count'] = '-1';
		
		$settings['order_by'] = 'title';
		
		$settings['order'] = 'ASC';
		
		$class = ( ! empty( $settings['csshook'] ) ) ? $settings['csshook'] : '';
		
		$html = '<div class="cpb-item cpb-az-index-wrap ' . $class . '">';
	
		$items = $this->get_items( $settings );
		
		$alpha_items = $this->get_alpha_items( $items, $settings );
		
		$html .= $this->get_az_index_nav( $alpha_items, $settings );
		
		$html .= $this->get_az_index_content( $alpha_items, $settings );
		
		$html .= '</div>';
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$feed_form = array(
			'name'    => $this->get_input_name( 'source_type' ),
			'value'   => 'feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $this->form_fields->get_form_local_query( $this->get_input_name() , $settings ),
			);
			
		$remote_feed_form = array(
			'name'    => $this->get_input_name( 'source_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['source_type'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Load external content by category or tag',
			'form'    => $this->form_fields->get_form_remote_feed( $this->get_input_name() , $settings ),
			);
		
		$html = $this->form_fields->multi_form( array( $feed_form , $remote_feed_form ) );
		
		return array( 'Source' => $html );
		
	} // end form
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		if ( ! empty( $settings['source_type'] ) ){
			
			$clean['source_type'] = ( ! empty( $settings['source_type'] ) ) ? sanitize_text_field( $settings['source_type'] ) : '';
			
			switch( $settings['source_type'] ){
				
				case 'feed':
				
					$form_clean = $this->form_fields->get_form_local_query_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
				
				case 'remote_feed':
				
					$form_clean = $this->form_fields->get_form_remote_feed_clean( $settings );
				
					$clean = array_merge( $clean , $form_clean );
					
					break;
					
			} // end switch
		
		} // end if
		
		$clean['tag'] = ( ! empty( $settings['tag'] ) )? sanitize_text_field( $settings['tag'] ) : 'h2';
		
		$clean['anchor'] = ( ! empty( $settings['anchor'] ) )? sanitize_text_field( $settings['anchor'] ) : '';
		
		return $clean;
		
	}
	
	
	protected function get_items( $settings ){
		
		require_once cpb_plugin_dir('classes/class-cpb-query.php');
			
		$query = new CPB_Query();

		switch( $settings['source_type'] ){

			case 'feed':
				$items = $query->get_local_items( $settings , '' , $this->get_fields() );
				break;
			case 'remote_feed':
				$items = $query->get_remote_items_feed( $settings , '' , $this->get_fields() );
				break;

			default:
				$items = array();
				break;

		} // end switch
		
		return $items;
		
	} // End get_items
	
	
	protected function get_columns( $items, $settings ){
		
		$cols = array_chunk( $items, ceil( count( $items )/3 ) );
		
		return $cols;
		
	} // End get_columns
	
	
	protected function get_alpha_items( $items, $settings ){
		
		$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		
		$alpha_array = array();
		
		foreach( $alpha as $index => $al ){
			
			$alpha_array[ $al ] = array(); 
			
		} // End foreach
		
		$alpha_num = array( 1 => 'o', 2 => 't', 3 => 't', 4 => 'f', 5 => 'f', 6 => 's', 7 => 's', 8 => 'e', 9 => 'n');
		
		foreach( $items as $index => $item ){
			
			$let = strtolower( $item['title'][0] );
			
			if ( array_key_exists( $let, $alpha_num ) ) {
				
				$let = $alpha_num[ $let ];
				
			} // End if
			
			$alpha_array[$let][] = $item;
			
		} // End foreach
		
		return $alpha_array;
		
	} // End get_alpha_items
	
	
	protected function get_alpha_set_display( $key, $alpha_set, $settings ){
		
		$html = '';
		
		if ( ! empty( $alpha_set ) ){
		
			$cols = $this->get_columns( $alpha_set, $settings );
		
			foreach( $cols as $column => $col_items ){

				$html .= '<div class="cpb-az-index-column">';

					foreach( $col_items as $index => $item ){
						
						$class = ( ! empty( $item['link'] ) ) ? ' has-link':'';

						$html .= '<div class="cpb-az-index-column-item' . $class . '">';

							$html .= '<h3 class="cpb-az-index-column-item-title">' . $item['title'] . '</h3>';
						
							if ( ! empty( $item['link'] ) ){
								
								$html .= '<div><a class="cpb-az-index-column-item-link" href="' . $item['link'] . '">Visit: ' . $item['title'] . '</a></div>';
								
							} // End if

						$html .= '</div>';

					} // End foreach

				$html .= '</div>';

			} // End foreach

		} // End if
		
		return $html;
		
	} // End get_items_display
	
	protected function get_az_index_nav( $alpha_items, $settings ){
		
		$a = true;
		
		$html = '<nav class="cpb-az-index-nav">';
		
		foreach( $alpha_items as $alpha => $items ){
			
			$active = '';
			
			if ( ! empty( $items ) && $a ){
				
				$active = ' active';
				
				$a = false;
				
			} // End if
			
			$has_items = ( ! empty( $items ) ) ? ' has-items' : '';
			
			$html .= '<div class="cpb-az-index-nav-item ' . $has_items . $active . '">' . $alpha . '</div>';
			
		} // End foreach
		
		$html .= '</nav>';
		
		return $html;
		
	} // End get_az_index_nav
	
	
	protected function get_az_index_content( $alpha_items, $settings ){
		
		$html = '<div class="cpb-az-index-alpha-content">';
		
		$a = true;
		
		foreach( $alpha_items as $key => $alpha_set ){
			
			$active = '';
			
			if ( ! empty( $alpha_set ) && $a ){
				
				$active = ' active';
				
				$a = false;
				
			} // End if
			
			$html .= '<div class="cpb-az-index-alpha-set' . $active . '">';
			
			$html .= $this->get_alpha_set_display( $key, $alpha_set, $settings );
			
			$html .= '</div>';
			
		} // End foreach
		
		$html .= '</div>';
		
		return $html;
		
	} // End get_az_index_content
	
	
}