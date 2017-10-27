<?php

class CPB_Item_Row extends CPB_Item {
	
	protected $name = 'Row';
	
	protected $slug = 'row';
	
	protected $allowed_children = array('column');
	
	protected $default_child = 'column';
	
	protected $layouts;
	
	public function __construct( $settings = array() , $content = '' ){
		
		parent::__construct( $settings , $content);
		
		$this->set_layouts();
		
	} // end __construct
		
	public function get_layouts(){ return $this->layouts; }
	
	
	protected function item( $settings , $content ){
		
		global $cpb_column_i;

		$cpb_column_i = 1;
		
		$html = '<div class="' . $this->prefix . 'row ' . $this->get_item_class( $settings ) . '" style="' . implode( ';' , $this->get_item_style( $settings ) ) . '">';
		
			if ( ! empty( $this->prefix ) ) $html .= '<div class="cpb-row-inner">'; 
		
		
			if ( ! empty( $settings['title'] ) ){
				
				$html .= '<h2 class="row-title">' . $settings['title'] . '</h2>'; 
				
			} // end if
		
			$html .= $this->get_row_background( $settings ); 
		
			$html .= do_shortcode( $content );
			
			if ( ! empty( $this->prefix ) ) $html .= '</div>';
		
		$html .= '</div>';
		
		if ( ! empty( $settings['anchor'] ) ){
			
			$html = '<a name="' . $settings['anchor'] . '"></a>' . $html;
			
		} // end if
		
		return $html;
		
	}
	
	protected function editor( $settings , $content ){
		
		$layouts = $this->get_layouts();
		
		$layout = $layouts[ $settings['layout'] ];
		
		$html = '<div class="cpb-item cpb-row cpb-layout-item ' . $settings['layout'] . '" data-id="' . $this->get_id() . '">';
		
			$html .= '<header class="cpb-row">' . $this->form_fields->get_edit_item_button() . '<a class="cpb-move-item-action cpb-item-title" href="#">Row | ' . $layout['name'] . '</a>' . $this->form_fields->get_remove_item_button() . '</header>';
			
			$html .= '<div class="cpb-set-wrap">';
			
				$html .= '<div class="cpb-child-set cpb-layout-set">';
				
					$html .= $content;
				
				$html .= '</div>';
			
			$html .= '</div>';
			
			$html .= '<footer></footer>';
			
			$html .= '<fieldset>';
				
				$html .= '<input class="cpb-children-input" type="hidden" name="' . $this->get_input_name( false , false  ) . '[children]" value="' . $this->get_child_ids() . '" >';
			
			$html .= '</fieldset>';
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	protected function form( $settings , $content ){
		
		$p_values = array( 'default' => 'Not Set' );
		
		$p = 0;
		
		while( $p < 4 ){
			
			$p_values[ $p . 'rem' ] = $p . 'rem';
			
			$p = $p + 0.5;
			
		} // end for
		
		$basic = '<input type="hidden" name="' . $this->get_input_name('layout') . '" value="' . $settings['layout'] . '" >';
		
		$basic .= $this->form_fields->hidden_field( $this->get_input_name( 'layout' ), $settings['layout'] );
		
		$basic .= $this->form_fields->text_field( $this->get_input_name('title'), $settings['title'], 'Title' );
		
		$basic .= $this->form_fields->select_field( $this->get_input_name('title_tag'), $settings['title_tag'], $this->form_fields->get_header_tags() , 'Title Tag' );
		
		$basic .= $this->form_fields->select_field( $this->get_input_name('bgcolor'), $settings['bgcolor'], $this->form_fields->get_wsu_colors(), 'Background Color' );
		
		$basic .= $this->form_fields->checkbox_field( $this->get_input_name('full_bleed'), 1, $settings['full_bleed'], 'Background Full Bleed Color' );
		
		$basic .= $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$layout = $this->form_fields->select_field( $this->get_input_name('padding_top'), $settings['padding_top'], $p_values, 'Padding Top' );
		
		$layout .= $this->form_fields->select_field( $this->get_input_name('padding_bottom'), $settings['padding_bottom'], $p_values, 'Padding Bottom' );
		
		$layout .= $this->form_fields->select_field( $this->get_input_name('padding_left'), $settings['padding_left'], $p_values, 'Padding Left' );
		
		$layout .= $this->form_fields->select_field( $this->get_input_name('padding_right'), $settings['padding_right'], $p_values, 'Padding Right' );
		
		$layout .= $this->form_fields->select_field( $this->get_input_name('padding'), $settings['padding'], $this->form_fields->get_padding(), 'Padding (Old)' );
		
		$adv = $this->form_fields->select_field( $this->get_input_name('gutter'), $settings['gutter'], $this->form_fields->get_gutters(), 'Gutter' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('bg_src'), $settings['bg_src'], 'Background Image URL' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('min_height'), $settings['min_height'], 'Minimum Height (px)' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('anchor') , $settings['anchor'] , 'Anchor Name' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );

		
		return array('Basic' => $basic , 'Layout' => $layout, 'Advanced' => $adv );
		
	} // end form
	
	protected function admin_css(){
		
		ob_start();
		
		include plugin_dir_path( dirname ( __FILE__ ) ) . 'css/item-row.php';
		
		return ob_get_clean();
		
	} // end admin_css
	
	protected function css() {
		
		$style = '.row-bg-image {position:absolute !important;top:0;left:0;width:100%;height:100%;background-size:cover;background-position:center center;background-repeat:no-repeat;}';
		
		$style .= '.row-title { position: relative;}';
		
		return $style;
		
	}
	
	
	protected function clean( $settings ){
		
		
		$clean = array();
		
		$clean['title'] = ( isset( $settings['title'] ) ) ? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['title_tag'] = ( isset( $settings['title_tag'] ) ) ? sanitize_text_field( $settings['title_tag'] ) : 'h2';

		$clean['layout'] = ( ! empty( $settings['layout'] ) ) ? sanitize_text_field( $settings['layout'] ) : 'single';

		$clean['bgcolor'] = ( ! empty( $settings['bgcolor'] ) ) ? sanitize_text_field( $settings['bgcolor'] ) : '';

		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) ) ? sanitize_text_field( $settings['textcolor'] ) : '';

		$clean['padding'] = ( isset( $settings['padding'] ) ) ? sanitize_text_field( $settings['padding'] ) : 'pad-bottom';

		$clean['gutter'] = ( ! empty( $settings['gutter'] ) ) ? sanitize_text_field( $settings['gutter'] ) : 'gutter';

		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';
		
		$clean['anchor'] = ( ! empty( $settings['anchor'] ) )? sanitize_text_field( $settings['anchor'] ) : '';
		
		$padding = array( 'padding_top', 'padding_bottom', 'padding_left', 'padding_right' );
		
		foreach( $padding as $key => $pad ){
			
			$clean[ $pad ] = ( ! empty( $settings[ $pad ] ) )? sanitize_text_field( $settings[ $pad ] ) : '';
			
		} // end foreach
		
		if ( ! empty( $settings['full_bleed'] ) ) $clean['full_bleed'] = sanitize_text_field( $settings['full_bleed'] );
		
		if ( ! empty( $settings['bg_src'] ) ) $clean['bg_src'] = sanitize_text_field( $settings['bg_src'] );
		
		if ( ! empty( $settings['min_height'] ) ) $clean['min_height'] = sanitize_text_field( $settings['min_height'] );

		return $clean;
		
		
	} // end clean
	
	
	public function get_row_background( $settings ){
		
		if ( isset( $settings['bg_src'] ) ){
			
			$html = '<div class="row-bg-image recto verso unbound" style="background-image:url(' . $settings['bg_src'] . ')"></div>';
			
			return $html;
			
		} else {
			
			return '';
			
		} // end if
		
	} // end get_row_background
	
	
	public function get_add_row( $post ){
		
		$icons = $this->get_add_row_icons();
		
		$html = '<fieldset id="cpb-add-row">';
		
			$html .= '<header>+ Add Row</header>';
			
			$html .= '<ul>';
			
			foreach( $icons as $icon ){
				
				$html .= $icon;
				
			} // end foreach;
			
			$html .= '</ul>';
		
		return $html . '</fieldset>';
		
	} // end get_add_row
	
	protected function get_add_row_icons( $additional = false ){
		
		$icons = array();
		
		$items = $this->get_layouts();
		
		$items['pagebreak'] = array( 
				'name' => 'Page Break',
				'img' => plugins_url( 'images/pagebreak-icon.gif', dirname(__FILE__) ),
				);
		
		foreach( $items as $class => $data ){
			
			$slug = 'row';
			
			if ( 'pagebreak' == $class ) { 
				
				$slug = 'pagebreak';
				
			} // end if
				
			$icons[] = $this->add_row_icon( $slug , $data , array( 'layout' => $class ) );
			
		} // end foreach
		
		
		
		return $icons;
		
	} // get_add_row_icons
	
	protected function add_row_icon( $slug , $data , $settings = array() ){
		
		$html = '<li class="add-row-item">';
		
			$html .= '<div class="cpb-image">';
		
				$html .= '<img  style="background-image:url(' . $data['img'] . ');" src="' . plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) ) . '" />';
			
			$html .= '</div>';
				
			$html .= '<input type="text" name="slug" value="' . $slug . '" />';
			
			foreach( $settings as $key => $value ){
				
				$html .= '<input type="text" name="settings[' . $key . ']" value="' . $value . '" />';
				
			} // end foreach
			
			$html .= '<div class="cpb-title">' . $data['name'] . '</div>'; 
		
		$html .= '</li>';
		
		return $html;
		
	}
	
	protected function set_layouts(){
		
	$layouts = array(
		'single'            => array( 
			'name'    => 'Single Column',
			'columns' => array( 1 ),
			'img' => plugins_url( 'images/column-single-icon.gif', dirname(__FILE__) ),
			),
		'halves'            => array( 
			'name'    => 'Two Column',
			'columns' => array( 0.5 , 0.5 ),
			'img' => plugins_url( 'images/column-halves-icon.gif', dirname(__FILE__) ),
			),
		'side-right'        => array( 
			'name'    => 'Two Column: Sidebar Right',
			'columns' => array( 0.7 , 0.3 ),
			'img' => plugins_url( 'images/column-two-sidebar-right-icon.gif', dirname(__FILE__) ),
			),
		'side-left'         => array( 
			'name'    => 'Two Column: Sidbar Left',
			'columns' => array( 0.3 , 0.7 ),
			'img' => plugins_url( 'images/column-two-sidebar-left-icon.gif', dirname(__FILE__) ),
			),
		'thirds'            => array( 
			'name'    => 'Three Column',
			'columns' => array( 0.33 , 0.33 , 0.33 ),
			'img' => plugins_url( 'images/column-thirds-icon.gif', dirname(__FILE__) ),
			),
		'thirds-half-left'  => array( 
			'name'    => 'Three Column: Left 50%',
			'columns' => array( 0.5 , 0.25 , 0.25 ),
			'img' => plugins_url( 'images/column-thirds-left-icon.gif', dirname(__FILE__) ),
			),
		'thirds-half-right' => array( 
			'name'    => 'Three Column: Right 50% ',
			'columns' => array( 0.25 , 0.25 , 0.5 ),
			'img' => plugins_url( 'images/column-thirds-right-icon.gif', dirname(__FILE__) ),
			),
		'triptych'          => array( 
			'name'    => 'Three Column: Middle 50%',
			'columns' => array( 0.25 , 0.5 , 0.25 ),
			'img' => plugins_url( 'images/column-thirds-middle-icon.gif', dirname(__FILE__) ),
			),
		'quarters'          => array( 
			'name'    => 'Four Column',
			'columns' => array( 0.25 , 0.25 , 0.25 , 0.25 ),
			'img' => plugins_url( 'images/column-four-icon.gif', dirname(__FILE__) ),
			),
		);
		
		$this->layouts = $layouts;
		
	} // end set_layouts
	
	private function get_item_class( $settings ){
		
		$class = '';
		
		if ( ! empty( $settings['bgcolor'] ) ) {
			
			$class .= ' ' . $settings['bgcolor'] . '-back';
			
			$class .= ' has-background-color';
			
		} // end if

		if ( ! empty( $settings['textcolor'] ) ) {
			
			$class .= ' ' . $settings['textcolor'] . '-text';
			
		} // end if

		if ( ! empty( $settings['padding'] ) ) {
			
			$class .= ' ' . $settings['padding'];
			
		} // end if

		if ( ! empty( $settings['gutter'] ) ) {
			
			$class .= ' ' . $settings['gutter'];
			
		} // end if

		if ( ! empty( $settings['csshook'] ) ) {
			
			$class .= ' ' . $settings['csshook'];
			
		} // end if
		
		if ( ! empty( $settings['layout'] ) ) {
			
			$class .= ' ' . $settings['layout'];
			
		} // end if
		
		if ( ! empty( $settings['full_bleed'] ) ) {
			
			if ( ! empty( $settings['bg_src'] ) ){
				
				$class .= ' full-bleed-img';
				
			} else {
				
				$class .= ' full-bleed';
				
			} // end if
			
		} // end if
		
		return $class;
		
	} 
	
	
	
	
}