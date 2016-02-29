<?php
abstract class CPB_Item {
	
	protected $form_fields;
	
	protected $id;
	
	protected $version = '0.0.0';
	
	protected $settings;
	
	protected $content;
	
	protected $name;
	
	protected $form_size = 'medium';
	
	protected $allowed_children = array();
	
	protected $default_child = false;
	
	protected $uses_wp_editor = false;
	
	protected $children = array();
	
	
	
	public function __construct( $settings = array() , $content = ''){
		
		require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-form-fields.php';
		$this->form_fields = new CPB_Form_Fields();
		
		$this->id = $this->get_slug() . '_' . rand( 1 , 100000 );
		
		$this->settings = $this->get_clean_settings( $settings );
		
		$this->content = $content;
		
	} // end __construct
	
	
	public function get_id() { return $this->id; }
	
	public function get_version() { return $this->version; }
	
	public function get_settings() { return $this->settings; }
	
	public function get_content() { return $this->content; }
	
	public function get_slug(){ return $this->slug; }
	
	public function get_name(){ return $this->name; }
	
	public function get_form_size() { return $this->form_size; }
	
	public function get_allowed_children(){ return $this->allowed_children; } 
	
	public function get_default_child(){ return $this->default_child; }
	
	public function get_uses_wp_editor() { return $this->uses_wp_editor; }
	
	public function get_children(){ return $this->children; }
	
	public function get_child_content( $content ){ return $content; }
	
	
	public function set_children( $children ){
		
		$this->children = $children;
		
	} // end set_children
	
	
	public function the_shortcode(){
		
		$shortcode = '[' . $this->get_slug() . $this->get_shortcode_settings() . ']';
		
		if ( $this->get_children() ) {
			
			foreach( $this->get_children() as $child ) {
				
				$shortcode .= $child->the_shortcode();
				
			} // end foreach
			
		} else {
			
			$shortcode .= $this->get_content();
			
		}// end if
		
		$shortcode .= '[/' . $this->get_slug() . ']';
		
		return $shortcode;
		
	} // end the_shortcode
	
	public function get_shortcode_settings(){
		
		$split = '"';
		
		$settings = $this->get_settings();
		
		// Check for array's as a value and convert to JSON
		foreach( $settings as $key => $value ){
			
			if ( is_array( $value ) ){
				
				$split = '\'';
				
				$settings[ $key ] = json_encode( $value );
				
			} // end if
			
		} // end foreach
		
		$s = '';
		
		$default_sett = $this->get_clean_settings( array() );
		
		foreach ( $settings as $key => $value ){
			
			if ( ! array_key_exists( $key , $default_sett ) || $default_sett[ $key ] != $value  ) {
			
				$s .= ' ' . $key . '=' . $split . $value . $split;
			
			} // end if
			
		} // end foreach
		
		return $s;
		
		
	} // end get_shortcode_settings
	
	
	public function get_clean_settings( $settings , $content = false ){
		
		//var_dump( $settings );
		
		if ( ! is_array( $settings ) ) $settings = array();
		
		$clean_sett = array();
		
		foreach( $settings as $key => $sett ){
			
			if ( ! is_array( $sett ) ){
			
				$json = json_decode( $sett , true );
			
				if ( $json ) $settings[ $key ] = $json;
			
			} // end if
			
		} // end foreach
		
		if ( method_exists( $this , 'clean' ) ){
			
			$clean_sett = $this->clean( $settings );
			
		} // end if
		
		return $clean_sett;
		
	} // end clean
	
	public function the_item( $settings = false , $content = false ){
		
		$settings = ( $settings ) ? $settings : $this->get_settings();
		
		$content = ( $content ) ? $content : $this->get_content();
		
		$html = '';
		
		if ( CAHNRS_Pagebuilder_Plugin::$is_editor || CAHNRS_Pagebuilder_Plugin::$is_ajax ){
			
			if ( method_exists( $this , 'admin_item' ) ){
			
				$html .= $this->admin_item( $settings , $content );
			
			} else if ( method_exists( $this , 'item' ) ){
			
				$html .= $this->item( $settings , $content );
			
			}// end if
			
			if ( ! $html ){
				
				if ( method_exists( $this , 'editor_default_html' ) ){
				
					$html .= $this->editor_default_html( $settings , $content );
				
				} else {
					
					$html .=  '<div class="cbp-empty-item" style="padding: 0.5rem;font-size: 1rem;text-transform: uppercase;color: #777;">' . $this->get_name(). '</div>';
					
				} // end if
				
				
			} // end if
			
		} else {
			
			if ( method_exists( $this , 'item' ) ){
			
				$html .= $this->item( $settings , $content );
			
			}// end if
			
		} // end if
		
		
		
		if ( ! $html && ( CAHNRS_Pagebuilder_Plugin::$is_editor || CAHNRS_Pagebuilder_Plugin::$is_ajax ) ){
			
			if ( method_exists( $this , 'editor_default_html' ) ){
				
				$html = $this->editor_default_html( $settings , $content );
				
			} else {
				
				$html =  '<div class="cbp-empty-item" style="padding: 0.5rem;font-size: 1rem;text-transform: uppercase;color: #777;">' . $this->get_name(). '</div>';
				
			}
			
		} // end if
		
		return apply_filters( 'cpb_item_public' , $html , $this );
		
	} // end the_item
	
	public function the_editor( $settings = false , $content = false ){
		
		$settings = ( $settings ) ? $settings : $this->get_settings();
		
		$content = ( $content ) ? $content : $this->get_content();
		
		$content = $this->get_editor_content( $content );
		
		if ( method_exists( $this , 'editor' ) ){
			
			$html = $this->editor( $settings , $content );
			
		} else {
			
			$class = 'cpb-' . $this->get_slug() . ' cpb-item cpb-content-item';
			
			if ( $this->get_uses_wp_editor() ) $class .= ' cpb-wp-editor'; 
			
			$html = '<div class="' . $class . '"  data-id="' . $this->get_id() . '">';
		
				$html .= '<header><a class="cpb-move-item-action cpb-item-title" href="#">Item: ' . $this->get_name() . '</a>' . $this->form_fields->get_remove_item_button() . '</header>';
			
				$html .= '<div class="cpb-child-set">';
				
					$html .= $content;
					
					$html .=  $this->form_fields->get_edit_item_button();
				
				$html .= '</div>';
				
				$html .= '<footer></footer>';
			
			$html .= '</div>';
			
		}// end if
		
		return $html;
		
	} // end the_editor

	
	public function the_form( $settings = false , $content = false , $form_class = '' ){
		
		if ( CAHNRS_Pagebuilder_Plugin::$is_ajax && $this->get_uses_wp_editor() ) return '';
		
		$settings = ( $settings ) ? $settings : $this->get_settings();
		
		$content = ( $content ) ? $content : $this->get_content();
		
		$children = $this->get_children();
		
		if ( method_exists( $this , 'form' ) ){
			
			$allowed_children = $this->get_allowed_children();
			
			if ( empty( $allowed_children ) ) $form_class .= ' cpb-content-item-form';
			
			if ( $this->get_uses_wp_editor() ) $form_class .= ' cpb-wp-editor-item-form';
			
			$form_args = array( 'slug' => $this->get_slug() , 'class' => $form_class , 'title' => $this->get_name() , 'size' => $this->get_form_size() );
		
			$html .= $this->form_fields->get_item_form( $this->get_id() , $this->form( $settings , $content ) , $form_args );
		
		} // end if
			
		if ( $children ){
			
			foreach( $children as $child ){
					
				$html .= $child->the_form();
				
			} // end foreach
			
		} // end if
		
		return  $html;
		
	} // end the_form
	
	public function the_css( $type = false ){
		
		$css = '';
		
		if ( $type = 'admin' &&  method_exists( $this , 'admin_css' ) ){
			
			$css = $this->admin_css();
			
		} else if ( method_exists( $this , 'css' ) ){
		
			$css = $this->css();
			
		} // end if
		
		return $css;
		
	} // end the_admin_css
	
	public function the_public_scripts(){
	} // end the_public_scripts
	
	public function the_admin_scripts(){
	} // end the_admin_scripts
	
	public function get_child_ids(){
		
		$ids = array();
		
		foreach( $this->get_children() as $child ){
			
			$ids[] = $child->get_id();
			
		} // end foreach
		
		return implode( ',' , $ids );
		
	} // end get_child_ids
	
	public function get_input_name( $name = false , $is_setting = true , $prefix = false ){
		
		$input_name = '_cpb[' . $this->get_id() . ']';
		
		if ( $is_setting ) $input_name .= '[settings]';
		
		if ( $name ){
			
			if ( $prefix ) $name = $prefix . '_' . $name;
			
			$input_name .= '[' . $name . ']';
			
		} // end if
		
		return  $input_name;
		
	} // end get_input_name
	
	public function get_editor_content( $content ){
		
		$editor_content = '';
			
		if ( $this->get_children() ){
			
			foreach( $this->get_children() as $child ){
				
				$editor_content .= $child->the_editor();
				
			} // end foreach
			
		} else {
			
			$editor_content .= '<iframe id="item-content-' . $this->get_id() . '" class="cpb-editor-content" data-id="' . $this->get_id() . '" src="about:blank" scrolling="no"></iframe>';
			
			$editor_content .= '<textarea style="display:none;">' . $this->the_item( $this->get_settings() , $this->get_content() ) . '</textarea>';
			
			//$editor_content .= '<iframe id="item-content-' . $this->get_id() . '" class="cpb-editor-content" data-id="' . $this->get_id() . '" src="' . get_site_url() . '?cpb-get-template=editor-iframe" scrolling="no"></iframe>';
			
			//$editor_content .= '<iframe id="item-content-' . $this->get_id() . '" class="cpb-editor-content" data-id="' . $this->get_id() . '" src="' . plugins_url( 'editor.html', dirname(__FILE__) ) . '" scrolling="no"></iframe>';
			
		}// end if
		
		return $editor_content;
		
	} // end
	
	public function check_advanced_display( $item , $settings , $prefix = '' ){
		
		if ( ! empty( $item['title'] ) && ! empty( $settings[ $prefix . 'unset_title'] ) ){
			
			unset( $item['title'] );
			
		} // end if
		
		if ( ! empty( $item['excerpt'] ) && ! empty( $settings[ $prefix . 'unset_excerpt'] ) ){
			
			unset( $item['excerpt'] );
			
		} // end if
		
		if ( ! empty( $item['img'] ) && ! empty( $settings[ $prefix . 'unset_img'] ) ){
			
			unset( $item['img'] );
			
		} // end if
		
		if ( ! empty( $item['link'] ) && ! empty( $settings[ $prefix . 'unset_link'] ) ){
			
			unset( $item['link'] );
			
		} // end if
		
		if ( ! empty( $item['excerpt'] ) && ! empty( $settings[ $prefix . 'excerpt_length'] ) ){
			
			switch( $settings[ $prefix . 'excerpt_length'] ){
				
				case 'short':
					$words = 15;
					break;
				case 'long':
					$words = 40;
					break;
				case 'full':
					$words = false;
					break;
				default:
					$words = 25;
					break;
				
			} // end switch
			
			if ( $words ){
				
				$item['excerpt'] = wp_trim_words( $item['excerpt'] , $words , '...' );
				
			} // end if
			
		} // end if
		
		return $item;
		
	}
	
}