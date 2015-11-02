<?php

class Forms_PB{

	public static $wsu_colors = array(
		'gray-lightest' => array( 'Gray: Lightest', '#eff0f1' )
	);

	public static function text_field( $name, $value, $label = false, $class = '' ) {

		$html = '<input type="text" name="' . $name . '" value="' . $value . '" />';

		if ( $label ) $html = '<label>' . $label . '</label>' . $html;

		return Forms_PB::wrap_field( $html, $class );

	} // end input_field

	public static function hidden_field( $name, $value, $class = '' ) {

		$html = '<input type="hidden" name="' . $name . '" value="' . $value . '" class="' . $class . '" />';

		return $html;

	} // end input_field

	public static function radio_field( $name, $value, $current_value = false, $label = false, $class = '' ) {

		$active = ( $value == $current_value ) ? ' active' : '';

		$id = str_replace( array( '[',']' ), '_', $name ) . '_' . rand( 0, 1000000 );

		$html = '<input type="radio" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . checked( $value, $current_value, false )  . ' />';

		if ( $label ) $html = '<label for="' . $id . '" class="' . $active . '">' . $label . '</label>' . $html;

		return Forms_PB::wrap_field( $html, $class );

	} // end input_field

	public static function checkbox_field( $name, $value, $current_value = false, $label = false, $class = '' , $text = '' ) {

		$active = ( $value == $current_value ) ? ' active' : '';

		$id = str_replace( array( '[',']' ), '_', $name ) . '_' . rand( 0, 1000000 );

		$html = '<input type="checkbox" id="' . $id . '" name="' . $name . '" value="' . $value . '" ' . checked( $value, $current_value, false )  . ' />';

		if ( $label ) $html .= '<label for="' . $id . '" class="' . $active . '">' . $label . '</label>' ;
		
		if ( $text ) $html .= '<br /><span class=".cpb-helper-text">' . $text . '</span>' ;

		return Forms_PB::wrap_field( $html, $class, 'checkbox' );

	} // end input_field

	public static function select_field( $name, $value, $options, $label = false, $class = '' ) {

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

		return Forms_PB::wrap_field( $html, $class );

	} // end select
	
	

	public static function insert_media( $base_name, $settings, $class = '' ) {

		$html = '<div class="cwp-add-media-wrap">';

			$img = ( ! empty( $settings['img_src'] ) ) ? '<img src="' . $settings['img_src'] . '" />' : '<div class="cpb-image-item-empty">No Image Set</div>';

			$html .= '<div class="cpb-add-media-img">' . $img . '</div>';

			$html .= Forms_PB::button( 'Add Image', 'add-media-action' );

			$html .= Forms_PB::hidden_field( $base_name . '[img_src]', $settings['img_src'], 'cpb-add-media-src' );

			$html .= Forms_PB::hidden_field( $base_name . '[img_id]', $settings['img_id'], 'cpb-add-media-id' );

		$html .= '</div>';

		return $html;

	} // end insert_media
	
	public static function local_feed( $base_name , $settings ){
		
		$tax = get_taxonomies( array( 'public' => true ) );
		
		$taxonomies = array( '0' => 'NA' );
		
		foreach( $tax as $name => $id ){
			
			if ( $name == 'post_tag' ) $name = 'Tag';
			
			$name = str_replace( '_' , ' ' , $name );
			
			$name = ucwords( $name );
			
			$taxonomies[ $id ] = $name;
			
		} // end foreach
		
		$html = Forms_PB::select_field( $base_name . '[post_type]', $settings['post_type'] , Forms_PB::get_post_types() , 'Content Type:' );
		
		$html .= Forms_PB::select_field( $base_name . '[taxonomy]', $settings['taxonomy'] , $taxonomies , 'Feed By:' );
		
		$html .= Forms_PB::text_field( $base_name . '[terms]', $settings['terms'] , 'Terms (Name)' );
		
		$html .= Forms_PB::text_field( $base_name . '[posts_per_page]', $settings['posts_per_page'] , 'Count:' , 'cpb-small-field' );
		
		$html .= Forms_PB::checkbox_field( $base_name . '[term_operator]', 'AND', $settings['term_operator'], 'Require All Terms' );
		
		return $html;
		
	}
	
	public static function remote_feed( $base_name , $settings ){
		
		$html = Forms_PB::text_field( $base_name . '[remote_url]', $settings['remote_url'] , 'Site URL (Homepage)' , 'cpb-field-one-column' );
		
		$html .= Forms_PB::text_field( $base_name . '[remote_post_type]', $settings['remote_post_type'] , 'Post Type (slug)');
		
		$html .= Forms_PB::text_field( $base_name . '[remote_taxonomy]', $settings['remote_taxonomy'] , 'Feed By (slug)');
		
		$html .= Forms_PB::text_field( $base_name . '[remote_terms]', $settings['remote_terms'] , 'Terms (Name)');
		
		$html .= Forms_PB::text_field( $base_name . '[remote_posts_per_page]', $settings['remote_posts_per_page'] , 'Count' , 'cpb-small-field' );
		
		return $html;
		
	}
	
	public static function get_post_types( $add_any = true ){
		
		$p_types = get_post_types();
		
		if ( $add_any ){
		
			$post_types = array( 'any' => 'Any' );
		
		} // end if
		
		foreach( $p_types as $type ){
			
			$post_types[ $type ] = ucfirst( $type );
			
		} // end foreach
		
		return $post_types;
		
	} // end get_post_types

	public function button( $title, $action, $class = 'cwp-standard-button' ) {

		return '<a href="#" class="' . $action . ' ' . $class . '">' . $title . '</a>';

	} // end button

	public static function wp_editor_field( $id, $content, $label = false, $class = '' ) {

		ob_start();

		wp_editor( $content, '_content_' . $id );

		$html = ob_get_clean();

		if ( $label ) $html = '<label>' . $label . '</label>' . $html;

		return Forms_PB::wrap_field( $html, $class );

	} // end wp_editor_field

	public static function wrap_field( $field, $class = false , $type = '' ) {

		$class = ( $class ) ? $class : '';

		$html = '<div class="cpb-form-field ' . $type . ' ' . $class . '">' . $field . '</div>';

		return $html;

	} // end wrap_field

	public static function get_item_form( $forms, $action = 'close-form-action', $action_label = 'Done' ) {

		$tabs = '';

		$sections = '';

		$active = 'active';

		foreach( $forms as $label => $form ) {

			$tabs .= '<a href="#" class="cpb-tab ' . $active . '">' . $label . '</a>';

			$sections .= '<div class="cpb-form-content cpb-tab-content ' . $active . '"><div class="cpb-form-content-inner">' . $form . '</div></div>';

			$active = '';

		} // end foreach

		$html = '<nav>' . $tabs . '</nav>';

		$html .= $sections;

		$html .= '<footer>';

			$html .= '<a href="#" class="cpb-button ' . $action . '" >' . $action_label . '</a>';

		$html .= '</footer>';

		return $html;

	} // end get_item_form

	public static function get_sub_form( $form_array, $name = '', $value = 'na' ) {

		$options = '';

		$sections = '';

		foreach( $form_array as $title => $form ) {

			$checked = '';

			$active = '';

			if ( $form['value'] == $value ) {

				$checked = checked( $form['value'], $value, false );

				$active = 'active ';

			} // end if

			$id = $name . '_' . rand( 0, 100000 );

			$options .= '<label for="' . $id . '" class="' . $active . '">' . $title . '</label>';

			$options .= '<input type="radio" name="' . $name . '" value="' . $form['value'] . '" id="' . $id . '" ' . $checked . '/>';

			$sections .= '<div class="' . $active . 'cpb-subform-section"><div class="cpb-subform-inner">' . $form['form'] . '</div></div>';

		} // end foreach

		$html = '<div class="cpb-subform-nav">' . $options . '</div>';

		$html .= '<div class="cpb-subform-sections">' . $sections . '</div>';

		return $html;

	}
	
	public static function get_subform( $subform ){
		
		$id = 'subform_' . rand( 0 ,1000000 );
		
		$active = ( $subform['val'] == $subform['current_val'] ) ? ' selected' : '';
		
		$html = '<div class="cbp-form-subsection' . $active . '">';
		
			$html .= '<header>';
			
            	$html .= '<label for="' . $id . '">' .$subform['title'] . '<br /><span class="cbp-subsection-helper-text">' .$subform['summary'] . '</span></label>';
            	
				$html .= '<input type="radio" name="' . $subform['field_name'] . '" id="' . $id . '" value="' . $subform['val'] . '" ' . checked( $subform['val'] , $subform['current_val'] , false )  . ' />';
				
            $html .= '</header>';
			
			$html .= '<div class="cpb-form-fields">' . $subform['form'] . '</div>';
		
		$html .= '</div>';
		
		return $html;
		
	}

	public static function wrap_item_form( $id, $form, $width = 'small', $class = '' ) {

		$html = '<fieldset class="cpb-form cpb-tab-container cpb-form-' . $width . ' ' . $class . '" id="form_' . $id . '">';

			$html .= '<div class="cpb-form-frame">';

				$html .= '<a href="#" class="close-form-action">Close X</a>';

				$html .= $form;

			$html .= '</div>';

		$html .= '</fieldset>';

		return $html;

	}

	public static function get_wsu_colors( $subset = 'none' ) {

		$colors = array(
			'' => 'None',
		);

		$values	= array(
			'crimson'         => 'Crimson',
			'crimson-er'      => 'Crimson: Accent',
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

		return $colors;

	}

	public static function get_padding() {

		$values	= array(
			'pad-ends'   => 'Pad ends',
			'pad-top'    => 'Pad top',
			'pad-bottom' => 'Pad bottom',
			''           => 'No padding',
		);

		return $values;

	}

	public static function get_gutters() {

		$values	= array(
			'gutter'     => 'On',
			'gutterless' => 'Off',
		);

		return $values;

	}

}