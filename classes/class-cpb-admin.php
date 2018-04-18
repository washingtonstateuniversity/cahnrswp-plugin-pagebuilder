<?php
class CPB_Admin {
	
	protected $options;
	
	public function __construct( $options ){
		
		$this->options = $options;
		
		if ( ! empty( $_POST['is_update'] ) ) $this->update();
		
		add_submenu_page( 'options-general.php', 'Pagebuilder Settings','Page Layout', 'manage_options', 'pbsettings', array( $this, 'the_page' ) );
		
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
	} // end __construct
	
	
	public function register_settings(){
		
		register_setting( 'cpb_settings', 'cpb_spine_style' );
		
	} // end register_settings
	
	
	public function the_page(){
		
		$html = '';
		
		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			
		  wp_die( __('You do not have sufficient permissions to access this page.') );

		} // end if
		
		$html .= '<div class="wrap">';

			$html .= '<h2>Pabebuilder Settings</h2>';

			$html .= $this->get_the_form();

		$html .= '</div>';

		echo $html;
		
	} // end the page
	
	
	protected function get_the_form(){
		
		$use_spine_style = get_option( 'cpb_spine_style', '' );
		
		$html = '<form method="post" action="">';

			$html .= '<input type="hidden" value="true" name="is_update" />';

			$html .= '<table class="form-table">';

        		$html .= '<tr valign="top">';

        			$html .= '<th scope="row">Apply Builder To:</th>';

        			$html .= '<td>';
					
						$post_types = $this->options->get_option_post_types();

						foreach( $this->get_post_types() as $pt ) {

							$p_id = 'pb-type-' . rand( 1, 10000000 );

							$html .= '<input id="' . $p_id . '" type="checkbox" name="cpb_post_types[]" ';

							if ( ! empty( $post_types ) && in_array( $pt, $post_types ) ) $html .= 'checked="checked" ';

							$html .= 'value="' . $pt . '" />';

							$html .= '<label for="' . $p_id . '"> ' . ucfirst( $pt ) . '</label><br>';

						} // end foreach

					$html .= '</td>';

        		$html .= '</tr>';
				
				$html .= '<tr valign="top">';

        			$html .= '<th scope="row">Style Options:</th>';

        			$html .= '<td>';

						$html .= '<input id="cpb_global_css" type="checkbox" name="cpb_global_css" ';

						if ( $this->options->get_option_global_css() ) $html .= 'checked="checked" ';

						$html .= 'value="1" />';

						$html .= '<label for="cpb_global_css">Use CAHNRS Global CSS</label><br>';

					$html .= '</td>';

        		$html .= '</tr>';
				$html .= '<tr valign="top">';
		  
					$html .= '<th scope="row">Use Spine Style:</th>';
		  
						$html .= '<td>';
		  
							$html .= '<select id="cpb_spine_style" name="_cpb_spine_style" >';
							
							$html .= '<option value="">Default</option>';
							$html .= '<option value="enable" ' . selected( 'enable', $use_spine_style, false ) . '>Enable Spine Layout Style</option>';
							$html .= '<option value="disable" ' . selected( 'disable', $use_spine_style, false ) . '>Disable Spine Layout Style</option>';
							
							$html .= '</select>';
		  
		  
						$html .= '</td>';
		  
					$html .= '</tr>';

				$html .= '</table>';

			ob_start();

				submit_button();

			$html .= ob_get_clean();

		$html .= '</form>';

		return $html;
		
		
	} // end get_the_form
	
	private function get_post_types(){
		
		$post_types = get_post_types(); 

		// Exclude attachment, revision, and nav_menu_item.
		unset( $post_types['attachment'] );
 		unset( $post_types['revision'] );
 		unset( $post_types['nav_menu_item'] );
		
		return $post_types;
		
	}
	
	
	private function update(){
		
		//must check that the user has the required capability
		if ( current_user_can( 'manage_options' ) ) {
			
			if ( isset( $_POST['cpb_post_types'] ) ){ 
			
				$post_types = $_POST['cpb_post_types'];
				
				array_walk_recursive( $post_types, function( &$item, $key ) { sanitize_text_field( $item ); } );
			
				$this->options->set_post_types( $post_types );
				
				update_option( 'cpb_post_types', $this->options->get_option_post_types() );
			
			} // end if
			
			if ( isset( $_POST['cpb_global_css'] ) ){ 
			
				$this->options->set_global_css( sanitize_text_field( $_POST['cpb_global_css'] ) );
				
				update_option( 'cpb_global_css', $this->options->get_option_global_css() );
			
			} // end if
			
			if ( isset( $_POST['cpb_layout_css'] ) ){ 
			
				$this->options->set_layout_css( sanitize_text_field( $_POST['cpb_layout_css'] ) );
				
				update_option( 'cpb_layout_css', $this->options->get_option_layout_css() );
			
			} // end if
			
			if ( isset( $_POST['cpb_spine_style'] ) ){ 
			
				$value = sanitize_text_field( $_POST['cpb_spine_style'] );
				
				add_option( 'cpb_spine_style', $value );
			
			} // end if
		
		} // end if
		
	} // end update
	
}