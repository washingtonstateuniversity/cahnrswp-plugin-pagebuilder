<?php
class Options_PB {

	public $settings;

	public function set_settings() {

		$this->settings = $this->get_settings();

	} // end set_options

	public function add_page() {

		add_submenu_page( 'options-general.php', 'Pagebuilder Settings','Page Layout', 'manage_options', 'pbsettings', array( $this, 'the_page' ) );

	} // end add_page

	public function the_page() {

		 //must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
		  wp_die( __('You do not have sufficient permissions to access this page.') );

		} // end if

		$is_update = ( ! empty( $_POST['is_update'] ) );

		$html .= '<div class="wrap">';

			$html .= '<h2>Pabebuilder Settings</h2>';

			$html .= $this->the_form( $this->get_settings( $is_update ) );

		$html .= '</div>';

		echo $html;

	} // end the_page

	public function the_form( $settings ) {

		$post_types = get_post_types();

		$html = '<form method="post" action="">';

			$html .= '<input type="hidden" value="true" name="is_update" />';

			$html .= '<table class="form-table">';

        		$html .= '<tr valign="top">';

        			$html .= '<th scope="row">Apply Builder To:</th>';

        			$html .= '<td>';

						foreach( $post_types as $pt ) {

							$p_id = 'pb-type-' . rand( 1, 10000000 );

							$html .= '<input id="' . $p_id . '" type="checkbox" name="cpb_post_types[]" ';

							if ( ! empty( $settings['cpb_post_types'] ) && in_array( $pt, $settings['cpb_post_types'] ) ) $html .= 'checked="checked" ';

							$html .= 'value="' . $pt . '" />';

							$html .= '<label for="' . $p_id . '"> ' . ucfirst( $pt ) . '</label><br>';

						} // end foreach

					$html .= '</td>';

        		$html .= '</tr>';

			$html .= '</table>';

			ob_start();

				submit_button();

			$html .= ob_get_clean();

		$html .= '</form>';

		return $html;


	} // end the_form

	public function get_settings( $is_update = false ) {

		$settings = array();

		if ( $is_update ) {

			// Start post type

			$p_types = ( ! empty( $_POST['cpb_post_types'] ) ) ? $_POST['cpb_post_types'] : array();

			array_walk_recursive( $p_types, function( &$item, $key ) { sanitize_text_field( $item ); } );

			$settings['cpb_post_types'] = $p_types;

			$settings['cpb_layout_css'] = ( ! empty( $_POST['cpb_layout_css'] ) ) ? sanitize_text_field( $_POST['cpb_layout_css'] ) : false;

			foreach( $settings as $key => $value ) {

				update_option( $key, $value );

			} // end foreach

		} else {

			$settings['cpb_post_types'] = get_option('cpb_post_types', array('page') );

			$settings['cpb_layout_css'] = get_option('cpb_layout_css', true );

		}// end if

		return $settings;

	} // end get_settings

	public function remove_editor() {

		// Remove editor
		if ( is_array( $this->settings['cpb_post_types'] ) ) {

			foreach( $this->settings['cpb_post_types'] as $type ) {

				remove_post_type_support( $type, 'editor' );

				remove_post_type_support( $type, 'excerpt' );

			} // end foreach

		} // end if

	} // end remove_editor

}