<?php
class CPB_Form_Fields {
	
	public function get_remove_item_button(){ return '<a href="#" class="cpb-remove-item-action">Remove</a>'; }
	
	public function get_edit_item_button(){ return '<a href="#" class="cpb-edit-item-action">Edit</a>'; }
	
	public function get_item_form( $id , $form , $args = array() ){
		
		if ( ! isset( $args['slug'] ) ) $args['slug'] = '';
		
		if ( ! isset( $args['class'] ) ) $args['class'] = '';
		
		if ( ! isset( $args['size'] ) ) $args['size'] = 'medium';
		
		if ( ! isset( $args['default_label'] ) ) $args['default_label'] = 'Basic';
		
		if ( ! isset( $args['title'] ) ) $args['title'] = '';
		
		if ( ! is_array( $form ) ) $form = array( $args['default_label'] => $form );
		
		$html = '<div class="cpb-item-form-wrap"><fieldset id="' . $id . '" class="cpb-item-form ' . $args['class'] . ' cpb-form-' . $args['size'] . '" data-type="' . $args['slug'] . '">';
		
			$html .= '<header>' . $args['title'] . '<a href="#" class="cpb-close-form-action"></a></header>';
			
			$html .= '<div class="cpb-item-form-contents">';
		
				$html .= '<nav class="cpb-tabs">';
				
					$active = ' active';
				
					foreach( $form as $label => $form_html ){
						
						$html .= '<a href="#" class="cpb-tab-action ' . $active . '">' . $label . '</a>';
						
						$active = '';
						
					} // end foreach
				
				$html .= '</nav>';
				
				$html .= '<div class="cpb-item-sections">';
				
					$active = ' active';
				
					foreach( $form as $label => $form_html ){
						
						$html .= '<div class="cpb-item-section ' . $active . '">' . $form_html . '</div>';
						
						$active = '';
						
					} // end foreach
				
				$html .= '</div>';
			
			$html .= '</div>';
			
			$html .= '<footer><a href="#" class="cpb-close-form-action">Done</a></footer>';
			
			$split_id = explode( '_' , $id );
			
			$html .= '<input class="cpb-form-item-id" type="hidden" name="_cpb[items][' . $id . ']" value="' . $split_id[0] . '" />';
		
		$html .= '</fieldset></div>';
		
		return $html;
		
		
	} // end get_lb_form
	
	
	public function get_button( $title, $class ){
		
		$html .= '<a href="" class="' . $class . '">' . $title . '</a>';
		
		return $html;
		
	} // End get_js_button
	
	public function text_field( $name, $value, $label = false, $class = '' , $helper_text = '' ) {

		$html = '<input type="text" name="' . $name . '" value="' . $value . '" />';

		if ( $label ) $html = '<label>' . $label . '</label>' . $html;
		
		if ( $helper_text ) $html .= '<span class="cpb-helper-text">' . $helper_text . '</span>';

		return $this->wrap_field( $html, $class );

	} // end input_field
	
	public function textarea_field( $name, $value, $label = false, $class = '' ) {

		$html = '<textarea name="' . $name . '">';
		
			$html .= $value;
		
		$html .= '</textarea>';

		if ( $label ) $html = '<label>' . $label . '</label>' . $html;

		return $this->wrap_field( $html, $class );

	} // end input_field
	
	public function hidden_field( $name, $value, $class = '' ) {

		$html = '<input type="hidden" name="' . $name . '" value="' . $value . '" class="' . $class . '" />';

		return $html;

	} // end input_field
	
	
	public function select_field( $name, $value, $options, $label = false, $class = '' ) {

		$html = '<select name="' . $name . '" >';

		foreach( $options as $op_value => $op_label ) {

			if ( is_array( $op_label ) ) {

				$c_label = $op_label[0];

			} else {

				$c_label = $op_label;

			} // end if

			$html .= '<option value="' . $op_value . '" ' . selected( $op_value, $value, false ) . ' >' . $op_label . '</option>';

		} // end foreach

		$html .= '</select>';

		if ( $label ) $html = '<label for="' . $id . '">' . $label . '</label>' . $html;

		return $this->wrap_field( $html, $class );

	} // end select
	
	
	public function multi_select_field( $name, $values, $options, $label = false, $class = '' ) {
		
		if ( ! is_array( $values ) ) $values = array();
		
		$options_fields_selected = array();
	
		$options_fields = array();

		foreach( $options as $op_value => $op_label ) {

			if ( is_array( $op_label ) ) {

				$c_label = $op_label[0];

			} else {

				$c_label = $op_label;

			} // end if
			
			$selected = ( in_array( $op_value, $values ) )? 'selected="selected"' : '';

			$option_html = '<option value="' . $op_value . '" ' . $selected . ' >' . $op_label . '</option>';
			
			if ( in_array( $op_value, $values ) ){
				
				$options_fields_selected[] = $option_html;
				
			} else {
				
				$options_fields[] = $option_html;
				
			} // End if

		} // end foreach
		
		$html = '<select multiple name="' . $name . '" >' . implode('', $options_fields_selected ) . implode('', $options_fields ) . '</select>';

		if ( $label ) $html = '<label for="' . $id . '">' . $label . '</label>' . $html;

		return $this->wrap_field( $html, $class );

	} // end select
	
	
	public function select_posts_field( $name, $values, $options, $label = false, $class = '' ) {
		
		if ( ! is_array( $values ) ) $values = array();
	
		$options_fields = array();

		foreach( $options as $op_value => $op_label ) {

			if ( is_array( $op_label ) ) {

				$c_label = $op_label[0];

			} else {

				$c_label = $op_label;

			} // end if

			$option_html = '<option value="' . $op_value . '" >' . $op_label . '</option>';
	
			$options_fields[] = $option_html;

		} // end foreach
		
		$html .= '<div class="cpb-select-post" data-basename="' . $name . '[]">';
		
		$html .= '<h3>Selected Posts</h3>';
		
		$html .= '<div class="cpb-select-post-selected">';
		
		foreach( $values as $index => $value ){
			
			if ( array_key_exists( $value, $options ) ){
				
				$html .= '<label>' . $options[ $value ] . '</label><input type="text" name="' . $name . '[]" value="' . $value . '" />';
				
			} // End if
			
		} // End foreach
		
		$html .= '</div>';
		
		$field_html = '<select name="" >' . implode('', $options_fields ) . '</select>';

		if ( $label ) $field_html = '<label for="' . $id . '">' . $label . '</label>' . $field_html;
		
		$html .= $field_html;
		
		$html .= $this->get_button('Add Post', 'cpb-select-post-updated-action') . '</div>';

		return $this->wrap_field( $html, $class );

	} // end select
	
	
	public function clean_select_posts_field( $posts ){
		
		$clean_posts = '';
		
		if ( is_array( $posts ) ){
			
			$posts = implode( ',', $posts );
			
		} // End if
		
		$clean_posts = sanitize_text_field( $posts );
		
		return $clean_posts;
		
	} // End clean_select_posts_field
	
	
	public function checkbox_field( $name, $value, $current_value = false, $label = false, $class = '' , $text = '' ) {

		$active = ( $value == $current_value ) ? ' active' : '';

		$id = str_replace( array( '[',']' ), '_', $name ) . '_' . rand( 0, 1000000 );

		$html = '<input type="checkbox" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . checked( $value, $current_value, false )  . ' />';

		if ( $label ) $html .= '<label for="' . $id . '" class="' . $active . '">' . $label . '</label>' ;
		
		if ( $text ) $html .= '<br /><span class=".cpb-helper-text">' . $text . '</span>' ;

		return $this->wrap_field( $html, $class, 'checkbox' );

	} // end input_field
	
	public function search_field( $class = '' , $action = '' ){
		
		$html .= '<label>Search</label>';
		
		$html .= '<div class="cpb-search-field">';
		
			$html .= '<input type="text" name="cpb-search" value="" placeholder="Search" /><a href="#" class="' . $action . '">GO</a>';
			
			$html .= '<ul></ul>';
		
		$html .= '</div>';
		
		return $this->wrap_field( $html, $class , 'cpb-search');
		
	}
	
	public function field_remote_feed_url( $name, $value, $label = false, $class = '' ) {

			$html = '<div class="cpb-field-remote-feed-url">';
		
			$html .= '<input type="text" name="' . $name . '" value="' . $value . '" />';
			
			$html .= '<a href="#" class="cpb-action-load-remote-feed-options cpb-basic-button">Update Options</a>';
			
			$html .= '</div>';

			if ( $label ) $html = '<label>' . $label . '</label>' . $html;
		
			$html .= '<span class="cpb-helper-text">URL of external site. NOTE: You must click <strong>update options</strong> before selecting options below.</span>';

		return $this->wrap_field( $html, $class );

	} // end field_remote_feed_url
	
	public function button( $label, $action, $class = 'cpb-standard-button' ) {

		return '<a href="#" class="' . $action . ' ' . $class . '">' . $label . '</a>';

	} // end button
	
	
	
	public function wrap_field( $field, $class = false , $type = '' ) {

		$class = ( $class ) ? $class : '';

		$html = '<div class="cpb-form-field ' . $type . ' ' . $class . '">' . $field . '</div>';

		return $html;

	} // end wrap_field
	
	public function multi_form( $forms = array() ) {
		
		if ( ! is_array( $forms ) ) $forms = array( $forms );
		
		$options = '';
		
		$subforms = '';
		
		$active_class = '';
		
		foreach( $forms as $form ){
			
			if ( $form['value'] == $form['selected'] ){
				
				$disabled = '';
				
				$checked = ' checked="checked"';
				
				$active = 'active';
				
				$active_class = ' active';
				
			} else {
				
				$disabled = ' disabled';
				
				$checked = '';
				
				$active = '';
				
			} // end if
			
			$id = 'cpb_radio_form_option_' . rand( 0 , 10000000 );
			
			$options .= '<label for="' . $id . '" class="' . $active . '"><span class="op-title">' . $form['title'] . '</span><span class="desc">' . $form['desc'] . '</span></label>';
			
			$options .= '<input id="' . $id . '" class="cpb-accordion-form-checkbox" type="checkbox" name="' . $form['name'] . '" value="' . $form['value'] . '"' . $checked . '/>';
			
			$subforms .= '<fieldset class="' . $active . '"' . $disabled . '>';
			
			$subforms .= '<div class="title">Option: ' . $form[title] . ' <a href="#" class="close-multi-form-action">Close</a></div><hr/>';
			
			$subforms .= $form['form'] . '</fieldset>';
			
		} // end foreach
		
		$html = '<div class="cpb-multi-form' . $active_class . '">';
		
			$html .= '<div class="cpb-multi-form-options' . $active_class . '">' . $options . '</div>';
			
			$html .= $subforms;
		
		$html .= '</div>';
		
		return $html;
		
	}
	
	public function insert_media( $base_name, $settings, $class = '' ) {

		$html = '<div class="cwp-add-media-wrap">';

			$img = ( ! empty( $settings['img_src'] ) ) ? '<img src="' . $settings['img_src'] . '" />' : '<div class="cpb-image-item-empty">No Image Set</div>';

			$html .= '<div class="cpb-add-media-img">' . $img . '</div>';

			$html .= $this->button( 'Add Image', 'add-media-action' );

			$html .= $this->hidden_field( $base_name . '[img_src]', $settings['img_src'], 'cpb-add-media-src' );

			$html .= $this->hidden_field( $base_name . '[img_id]', $settings['img_id'], 'cpb-add-media-id' );

		$html .= '</div>';

		return $html;

	} // end insert_media
	
	public function get_form_local_query( $base_name , $settings , $prefix = '' ){
		
		$order = array(
			'' 		=> 'Not Set',
			'date'	=> 'Date',
			'rand' 	=> 'Random',
			'title' => 'Title',
		);

		if ( empty( $settings[ $prefix . 'post_type'] ) ) $settings[ $prefix . 'post_type'] = '';
		if ( empty( $settings[ $prefix . 'taxonomy'] ) ) $settings[ $prefix . 'taxonomy'] = '';
		if ( empty( $settings[ $prefix . 'terms'] ) ) $settings[ $prefix . 'terms'] = '';
		if ( empty( $settings[ $prefix . 'count'] ) ) $settings[ $prefix . 'count'] = '';
		
		$form .= $this->select_field( $base_name. '[' . $prefix . 'post_type]' , $settings[ $prefix . 'post_type'] , $this->get_post_types(), 'Post Type' );
		
		$form .= $this->select_field( $base_name. '[' . $prefix . 'taxonomy]' , $settings[ $prefix . 'taxonomy'] , $this->get_taxonomies() , 'Taxonomy' );
		
		$form .= $this->text_field( $base_name. '[' . $prefix . 'terms]' , $settings[ $prefix . 'terms'] , 'Category/Tag Names' );
		
		$form .= $this->text_field( $base_name. '[' . $prefix . 'count]' , $settings[ $prefix . 'count'] , 'Number of Items' );
		
		$form .= $this->text_field( $base_name. '[' . $prefix . 'offset]' , $settings[ $prefix . 'offset'] , 'Offset' );
		
		$form .= $this->select_field( $base_name. '[' . $prefix . 'order_by]' , $settings[ $prefix . 'order_by'] , $order , 'Order By' );
		
		return $form;
		
	} // end get_form_local_query
	
	public function get_form_local_query_clean( $settings , $prefix = '' ){
		
		$ca = array();

		$ca[ $prefix . 'post_type'] = ( ! empty( $settings[ $prefix . 'post_type'] ) ) ? sanitize_text_field( $settings[ $prefix . 'post_type'] ) : '';
		$ca[ $prefix . 'taxonomy'] = ( ! empty( $settings[ $prefix . 'taxonomy'] ) ) ? sanitize_text_field( $settings[ $prefix . 'taxonomy'] ) : '';
		$ca[ $prefix . 'terms'] = ( ! empty( $settings[ $prefix . 'terms'] ) ) ? sanitize_text_field( $settings[ $prefix . 'terms'] ) : '';
		$ca[ $prefix . 'count'] = ( ! empty( $settings[ $prefix . 'count'] ) ) ? sanitize_text_field( $settings[ $prefix . 'count'] ) : '';
		$ca[ $prefix . 'offset'] = ( ! empty( $settings[ $prefix . 'offset'] ) ) ? sanitize_text_field( $settings[ $prefix . 'offset'] ) : '';
		$ca[ $prefix . 'order_by'] = ( ! empty( $settings[ $prefix . 'order_by'] ) ) ? sanitize_text_field( $settings[ $prefix . 'order_by'] ) : '';
		
		return $ca;
		
	} // end get_form_local_query
	
	public function get_form_select_post( $base_name , $settings , $prefix = '' ){

		if ( empty( $settings[ $prefix . 'id'] ) ) $settings[ $prefix . 'id'] = '';
		if ( empty( $settings[ $prefix . 'site_url'] ) ) $settings[ $prefix . 'site_url'] = get_site_url();
		
		
		
		$url_helper = 'URL of the site to search'; 
		
		$form = '<div class="cpb-form-search-posts" data-basename="' . $base_name. '[' . $prefix . 'remote_items]' . '">';
		
		$form .= $this->text_field( $base_name. '[' . $prefix . 'site_url]' , $settings[ $prefix . 'site_url'] , 'Search Site' , 'cpb-select-site-url cpb-full-width' , $url_helper );
		
		$form .= $this->search_field( 'cpb-select-post cpb-full-width'  );
		
		$form .= '<hr /><h4 style="margin: 0 2%;">Selected</h4>';
		
		$form .= '<ul class="cpb-results-set">';
		
		if ( is_array( $settings[ $prefix . 'remote_items'] ) ){
			
			foreach( $settings[ $prefix . 'remote_items'] as $post_id => $result ){
				
				$form .= '<li class="cpb-form-item">' . $result['title'] . '<a href="#" class="cpb-form-item-remove"></a><input type="text" name="' .$base_name. '[' . $prefix . 'remote_items][' . $post_id . '][id]" value="' . $result['id'] . '" />';
				
				$form .= '<input type="text" name="' .$base_name. '[' . $prefix . 'remote_items][' . $post_id . '][site]" value="' . $result['site'] . '" />';
				
				$form .= '<input type="text" name="' .$base_name. '[' . $prefix . 'remote_items][' . $post_id . '][title]" value="' . $result['title'] . '" />';
				
				$form .= '</li>';
				
			} // end foreach
			
		} // end if
		
		$form .= '</ul>';
		
		$form .= $this->hidden_field( $base_name. '[' . $prefix . 'id]' , $settings[ $prefix . 'id'] , 'cpb-select-post-id' );
		
		$form .= '</div>';
		
		return $form;
		
	} // end get_form_local_query
	
	public function get_form_remote_feed( $base_name , $settings , $prefix = '' ){
		
		$post_types = ( ! empty( $settings[ $prefix . 'post_type'] ) ) ? array( $settings[ $prefix . 'post_type'] => ucfirst ( $settings[ $prefix . 'post_type'] ) ): array();
		
		$taxonomies = ( ! empty( $settings[ $prefix . 'taxonomy'] ) ) ? array( $settings[ $prefix . 'taxonomy'] => ucfirst ( $settings[ $prefix . 'taxonomy'] ) ): array();
		
		$form = '<div class="cpb-form-remote-feed" data-basename="' . $base_name. '[' . $prefix . 'remote_items]' . '">';
		
			$url_helper = 'URL of external site. NOTE: You must input url before selecting options below.';
			
			$form .= $this->field_remote_feed_url( $base_name. '[' . $prefix . 'site_url]' , $settings[ $prefix . 'site_url'] , 'Source URL' , 'cpb-select-site-url cpb-full-width' );
			
			$form .= '<hr/>';
			
			$form .= $this->select_field( $base_name. '[' . $prefix . 'post_type]' , $settings[ $prefix . 'post_type'] , $post_types , 'Post Type' , 'cpb-remote-select-post-type cpb-field-min-width' );
			
			$form .= $this->select_field( $base_name. '[' . $prefix . 'taxonomy]' , $settings[ $prefix . 'taxonomy'] , $taxonomies , 'Taxonomy' , 'cpb-remote-select-taxonomy-type cpb-field-min-width' );
			
			$form .= $this->text_field( $base_name. '[' . $prefix . 'terms]' , $settings[ $prefix . 'terms'] , 'Category/Tag Names' );
		
			$form .= $this->text_field( $base_name. '[' . $prefix . 'count]' , $settings[ $prefix . 'count'] , 'Number of Items' );
		
		$form .= '</div>';
		
		return $form;
	
	}
	
	public function get_form_remote_feed_clean( $settings, $prefix = '' ){
		
		$ca = array();
		
		$ca[ $prefix . 'site_url'] = ( ! empty( $settings[ $prefix . 'site_url'] ) ) ? sanitize_text_field( $settings[ $prefix . 'site_url'] ) : '';
		
		$ca[ $prefix . 'post_type'] = ( ! empty( $settings[ $prefix . 'post_type'] ) ) ? sanitize_text_field( $settings[ $prefix . 'post_type'] ) : '';
		$ca[ $prefix . 'taxonomy'] = ( ! empty( $settings[ $prefix . 'taxonomy'] ) ) ? sanitize_text_field( $settings[ $prefix . 'taxonomy'] ) : '';
		$ca[ $prefix . 'terms'] = ( ! empty( $settings[ $prefix . 'terms'] ) ) ? sanitize_text_field( $settings[ $prefix . 'terms'] ) : '';
		$ca[ $prefix . 'count'] = ( ! empty( $settings[ $prefix . 'count'] ) ) ? sanitize_text_field( $settings[ $prefix . 'count'] ) : '';
		
		return $ca;
		
	}
	
	
	public function get_form_select_post_clean( $settings, $prefix = '' ){
		
		$ca = array();
		
		$ca[ $prefix . 'site_url'] = ( ! empty( $settings[ $prefix . 'site_url'] ) ) ? sanitize_text_field( $settings[ $prefix . 'site_url'] ) : '';
		
		if ( is_array( $settings[ $prefix . 'remote_items'] ) ){
			
			foreach( $settings[ $prefix . 'remote_items'] as $key => $result ){
				
				foreach( $result as $sub_key => $props ){
					
					$ca[ $prefix . 'remote_items'][ $key ][ $sub_key ] = sanitize_text_field( $props );
					
				} // end foreach
				
			} // end foreach
			
		} else {
			
			$ca[ $prefix . 'remote_items'] = array();
			
		}
		
		return $ca;
		
	} // end get_form_local_query
	
	public function get_wsu_colors( $subset = 'none' ) {

		$colors = array(
			'' => 'None',
		);

		$values	= array(
			'crimson'         => 'Crimson',
			'crimson-er'      => 'Crimson: Accent',
			'white'           => 'White',
			'gray'            => 'Gray',
			'gray-er'         => 'Gray: Accent',
			'gray-lightest'   => 'Gray: Lightest',
			'gray-lightly'    => 'Gray: Lightly',
			'gray-lighter'    => 'Gray: Lighter',
			'gray-light'      => 'Gray: Light',
			'gray-dark'       => 'Gray: Dark',
			'gray-darker'     => 'Gray: Darker',
			'gray-darkest'    => 'Gray: Darkest',
			'green'           => 'Green',
			'green-er'        => 'Green: Accent',
			'green-lightest'  => 'Green: Lightest',
			'green-lightly'   => 'Green: Lightly',
			'green-lighter'   => 'Green: Lighter',
			'green-light'     => 'Green: Light',
			'green-dark'      => 'Green: Dark',
			'green-darker'    => 'Green: Darker',
			'green-darkest'   => 'Green: Darkest',
			'orange'          => 'Orange',
			'orange-er'       => 'Orange: Accent',
			'orange-lightest' => 'Orange: Lightest',
			'orange-lightly'  => 'Orange: Lightly',
			'orange-lighter'  => 'Orange: Lighter',
			'orange-light'    => 'Orange: Light',
			'orange-dark'     => 'Orange: Dark',
			'orange-darker'   => 'Orange: Darker',
			'orange-darkest'  => 'Orange: Darkest',
			'blue'            => 'Blue',
			'blue-er'         => 'Blue: Accent',
			'blue-lightest'   => 'Blue: Lightest',
			'blue-lightly'    => 'Blue: Lightly',
			'blue-lighter'    => 'Blue: Lighter',
			'blue-light'      => 'Blue: Light',
			'blue-dark'       => 'Blue: Dark',
			'blue-darker'     => 'Blue: Darker',
			'blue-darkest'    => 'Blue: Darkest',
			'yellow'          => 'Yellow',
			'yellow-er'       => 'Yellow: Accent',
			'yellow-lightest' => 'Yellow: Lightest',
			'yellow-lightly'  => 'Yellow: Lightly',
			'yellow-lighter'  => 'Yellow: Lighter',
			'yellow-light'    => 'Yellow: Light',
			'yellow-dark'     => 'Yellow: Dark',
			'yellow-darker'   => 'Yellow: Darker',
			'yellow-darkest'  => 'Yellow: Darkest',
		);

		switch( $subset ) {

			default:
				$colors = array_merge( $colors, $values );
				break;

		} // end switch

		return apply_filters( 'cahnrswp_pagebuilder_colors', $colors );

	}
	
	public function get_padding() {

		$values	= array(
			'pad-ends'   => 'Pad ends',
			'pad-top'    => 'Pad top',
			'pad-bottom' => 'Pad bottom',
			''           => 'No padding',
		);

		return $values;

	}
	
	public function get_gutters() {

		$values	= array(
			'gutter'     => 'On',
			'gutterless' => 'Off',
		);

		return $values;

	}
	
	public function get_header_tags( $include_empty = false ){
		
		$tags = array(
			'h2'     => 'H2',
			'h3'     => 'H3',
			'h4'     => 'H4',
			'h5'     => 'H5',
			'strong' => 'Bold',
			'span'   => 'None',
		);
		
		if ( $include_empty ){
			
			$tags = array_merge( array( '' => 'None'), $tags );
			
		} // End if
		
		return apply_filters( 'cahnrswp_pagebuilder_header_tags', $tags );
		
	}
	
	public function get_post_types( $add_any = true , $exclude = true ){
		
		$exclude_types = array('revision','nav_menu_item','attachment');
		
		if ( $exclude && ! is_array( $exclude ) ) $exclude = $exclude_types;
		
		$p_types = get_post_types();
		
		if ( $add_any ){
		
			$post_types = array( 'any' => 'Any' );
		
		} // end if
		
		foreach( $p_types as $type ){
			
			if ( is_array( $exclude ) && in_array( $type , $exclude ) ) continue;
			
			$post_types[ $type ] = ucfirst( $type );
			
		} // end foreach
		
		return $post_types;
		
	} // end get_post_types
	
	public function get_taxonomies(){
		
		$tax = array(
			'category' => 'Category',
			'post_tag' => 'Tag'
		);
		
		return $tax;
		
	}
	
}