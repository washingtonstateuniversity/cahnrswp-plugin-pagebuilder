<?php
class Save_PB {

	public $item_factory;

	public function __construct( $item_factory ) {

		$this->item_factory = $item_factory;

	} // end __construct

	public function get_save_array_recursive( $save_str ) {

		//var_dump( $save_str );

		$items = array();

		$save_items = explode( ',', $save_str );

		foreach( $save_items as $item_id ) {

			$item_post = ( ! empty( $_POST['_cpb'][$item_id] ) ) ?  $_POST['_cpb'][$item_id] : false;

			//var_dump( $item_post );

			if ( $item_post ) {

				$type = ( ! empty( $item_post['type'] ) ) ?  sanitize_text_field( $item_post['type'] ) : false;

				$settings = ( ! empty( $item_post['settings'] ) ) ? $item_post['settings'] : array();

				$content = ( ! empty( $_POST[ '_content_' . $item_id ] ) ) ? wp_kses_post( $_POST[ '_content_' . $item_id ] ) : '';

				$item = $this->item_factory->get_item( $type, $content, $settings );

				if ( $item ) {

					if ( ! empty( $item_post['children'] ) ) {

						$item->children = $this->get_save_array_recursive( sanitize_text_field( $item_post['children'] ) );

					} // end if

					$items[] = $item;

				} // end if

			} // end if

			/*$type = explode( '_', $item_id );

			$type = $type[0];

			if ( ! empty( $_POST['_cwpb'][$item_id]['settings'] ) ) {

				$settings = $_POST['_cwpb'][$item_id]['settings'];

			} else {

				$settings = array();

			} // end if

			if ( ! empty( $_POST[ '_content_' . $item_id ] ) ) {

				$content = wp_kses_post( $_POST[ '_content_' . $item_id ] );

			} else {

				$content = '';
			} // end if

			$item = $this->item_factory->get_item( $type, $content, $settings );

			if ( ! empty( $_POST['_cwpb'][$item_id]['children'] ) ) {

				$item->children = $this->get_save_array_recursive( $_POST['_cwpb'][$item_id]['children'] );

			} // end if

			$items[] = $item;*/

		} // end foreach

		return $items;

	} // end get_save_array_recursive

	public function to_shortcodes_recursive( $save_items ) {

		$data = '';

		//var_dump( $save_items );

		foreach( $save_items as $item ) {

			if ( isset( $item->children ) && is_array( $item->children ) && $item->children ) {

				$content = $this->to_shortcodes_recursive( $item->children );

			} else {

				$content = $item->content;

			}// end if

			//$data .= '[' . $item->slug . $this->encode_settings( $item ) . ']' . $content . '[/' . $item->slug . ']';

			$data .= $item->to_shortcode( $content );

		} // end foreach

		return $data;

	} // end to_shortcodes_recursive

	public function get_settings() {

		$text_fields = array( '_cpb_m_excerpt', '_cpb_excerpt', '_cpb_pagebuilder' );

		$clean = array();

		foreach( $text_fields as $field ) {

			if ( isset( $_POST[$field] ) ) $clean[$field] = sanitize_text_field( $_POST[$field] );

		} // end foreach

		//var_dump( $clean );

		return $clean;

	}

	public function save( $post_id ) {

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

			return;

		} // end if

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {

			return;

			} // end if

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {

				return;

			} // end if

		} // end if

		$settings = $this->get_settings();

		foreach( $settings as $key => $value ) {

			update_post_meta( $post_id, $key, $value );

		} // end foreach

		if ( ! empty( $_POST['_cpb']['layout'] ) ) {

			$save_items = $this->get_save_array_recursive( $_POST['_cpb']['layout'] );

			$content = $this->to_shortcodes_recursive( $save_items );

			$excerpt = $this->get_excerpt( $settings, $content );

			if ( $content ) {

				$new_post = array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
				);

				 wp_update_post( $new_post );

			} // end if

		} // end if

	} // end save

	public function get_excerpt( $settings, $content ) {

		if ( isset( $settings['_cpb_m_excerpt'] ) && 'manual' ==  $settings['_cpb_m_excerpt'] ) {

			$excerpt = $settings['_cpb_excerpt'];

		}  else {

			$excerpt = $this->clean_excerpt( $content );

		} // end if

		return $excerpt;

	} // end get_excerpt

	public function clean_excerpt( $content ) {

		$patt = '/\[.*?\]/i';

		$excerpt = preg_replace( $patt, ' ', $content );

		//var_dump( $content );

		//var_dump( $excerpt );

		$excerpt = wp_trim_words( wp_strip_all_tags( $excerpt ), 55 );

		return $excerpt;

	} // end get_excerpt

}