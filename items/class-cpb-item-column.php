<?php

class CPB_Item_Column extends CPB_Item {
	
	protected $name = 'Column';
	
	protected $slug = 'column';
	
	protected $allowed_children = array('all');
	
	protected $default_child = 'textblock';
	
	protected $index = 1;
	
	
	public function get_index(){ return $this->index; }
	
	public function set_index( $i ){ $this->index = $i; }
	
	
	protected function item( $settings , $content ){
		
		global $cpb_column_i;
		
		$this->set_index( $cpb_column_i );

		$cpb_column_i++;
		
		$html = '<div class="' . $this->prefix . $this->get_item_class( $settings )  . '">' . do_shortcode( $content ) . '</div>';
		
		return $html;
		
	}
	
	
	protected function editor( $settings , $content ){
		
		$html = '<div class="cpb-item cpb-column cpb-layout-item column-' . $this->get_index_class( $this->get_index() ) . '" data-id="' . $this->get_id() . '">';
		
			$html .= '<div class="cpb-column-inner">';
			
			$html .= '<header>' . $this->form_fields->get_edit_item_button() . '<a class="cpb-move-item-action cpb-item-title" href="#">Column <span class="cpb-column-index">' . $this->get_index() . '</span></a></header>';
		
			$html .= '<div class="cpb-child-set cpb-child-set-items">';
			
				$html .= $content;
			
			$html .= '</div>';
			
			$html .= '<a href="#" class="add-item-action">+ Add Item</a>';
			
			$html .= '<footer>' . $this->form_fields->get_edit_item_button() . '<a class="cpb-move-item-action cpb-item-title" href="#">Column <span class="cpb-column-index">' . $this->get_index() . '</span></a></footer>';
			
			$html .= '</div>';
			
			$html .= '<fieldset>';
				
				$html .= '<input class="cpb-children-input" type="hidden" name="' . $this->get_input_name( false , false  ) . '[children]" value="' . $this->get_child_ids() . '" >';
			
			$html .= '</fieldset>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end editor
	
	protected function form( $settings , $content ){

		$basic = $this->form_fields->select_field( $this->get_input_name('bgcolor'), $settings['bgcolor'], $this->form_fields->get_wsu_colors(), 'Background Color' );

		$basic .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv = $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );
		
		return array( 'Basic' => $basic , 'Advanced' => $adv );
		
	}
	
	protected function clean( $settings ){
		
		$clean = array();

		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';

		$clean['bgcolor'] = ( ! empty( $settings['bgcolor'] ) ) ? sanitize_text_field( $settings['bgcolor'] ) : '';

		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) ) ? sanitize_text_field( $settings['textcolor'] ) : '';

		return $clean;
		
	}
	
	protected function admin_css(){
		
		ob_start();
		
		include plugin_dir_path( dirname ( __FILE__ ) ) . 'css/item-column.php';
		
		return ob_get_clean();
		
	} // end admin_css
	
	protected function get_index_class( $i ){
		
		$list = 'zero,one,two,three,four,five,six,seven,eight,nine,ten';
		
		$list = explode( ',',$list );
		
		return $list[ $i ];
	
	} // end get_index_class 
	
	protected function get_item_class( $settings ){
		
		$class = 'column ' .  $this->get_index_class( $this->get_index() );
		
		if ( ! empty( $settings['bgcolor'] ) ) {

			$class .= ' ' . $settings['bgcolor'] . '-back bg-color';

		} // end if

		if ( ! empty( $settings['csshook'] ) ) {

			$class .= ' ' . $settings['csshook'];

		} // end if
		
		if ( ! empty( $settings['textcolor'] ) ) {
			
			$class .= ' ' . $settings['textcolor'] . '-text';
			
		} // end if
		
		return $class;
		
	}
	
	
}