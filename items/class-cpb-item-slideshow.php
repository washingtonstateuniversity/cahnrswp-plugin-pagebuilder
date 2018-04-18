<?php

class CPB_Item_Slideshow extends CPB_Item {
	
	protected $name = 'Slideshow';
	
	protected $slug = 'slideshow';
	
	protected $allowed_children = array('slide');
	
	protected $default_child = 'slide';
	
	protected $form_size = 'small';
	
	
	public function item( $settings , $content ){
		
		global $cpb_slideshow;
		
		$slide_count = substr_count( $content, '[slide' );

		$active = rand(1, $slide_count );

		$cpb_slideshow = array(
			'type' => $settings['dislay_type'],
			'i'    => 1,
			'active' => $active,
			'randomize' => $settings['randomize'],
		);
		
		$slides = do_shortcode( $content );
		
		//ob_start();
		
		//include dirname( dirname(__FILE__) ) . '/js/js-item-slideshow-gallery.js';
		
		//$js = ob_get_clean();
		
		ob_start();
		
		include cpb_plugin_dir('lib/displays/slideshow/basic.min.php');
		
		//include dirname( dirname(__FILE__) ) . '/inc/inc-item-slideshow.php';
		
		$html = ob_get_clean();
		
		return $html;
		
	}// end item
	
	
	
	public function form( $settings , $content ){
		
		$displays = array(
			'default' => 'Default',
			'college' => 'College'
		);
		
		$html = $this->form_fields->text_field( $this->get_input_name('title') , $settings['title'] , 'Title' ); 
		
		$html .= $this->form_fields->select_field( $this->get_input_name('display_type') , $settings['display_type'] , $displays , 'Display Type' );
		
		$html .= $this->form_fields->checkbox_field( $this->get_input_name( 'randomize' ), 1, $settings['randomize'], 'Randomize' );

		return array('Basic' => $html );
		
	} // end form
	
	protected function editor( $settings , $content ){

		$base_settings = array(
			'layout' => '',
		);

		$settings = array_merge( $base_settings, $settings );
		
		$html = '<div class="cpb-item cpb-content-item cpb-sublayout-item cpb-' . $this->get_slug() . ' cpb-layout-item ' . $settings['layout'] . '" data-id="' . $this->get_id() . '">';
		
			$html .= '<header>' . $this->form_fields->get_edit_item_button() . '<div class="cpb-item-title">Slideshow</div>' . $this->form_fields->get_remove_item_button() . '</header>';
		
			$html .= '<div class="cpb-child-set cpb-child-set-items">';
			
				$html .= $content;
			
			$html .= '</div>';
			
			$html .= '<div class="add-part-action" data-slug="slide">+ Add Slide<input type="hidden" name="slug" value="slide" /></div>';
			
			$html .= '<fieldset>';
				
				$html .= '<input class="cpb-children-input" type="hidden" name="' . $this->get_input_name( false , false  ) . '[children]" value="' . $this->get_child_ids() . '" >';
			
			$html .= '</fieldset>';
		
		$html .= '</div>';
		
		return $html;
		
		
	} // end editor
	
	
	protected function clean( $settings ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $settings['title'] ) )? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['dislay_type'] = ( ! empty( $settings['dislay_type'] ) )? sanitize_text_field( $settings['dislay_type'] ) : 'gallery-slideshow';
		
		$clean['randomize'] = ( ! empty( $settings['randomize'] ) )? sanitize_text_field( $settings['randomize'] ) : '';

		return $clean;
	}
	
	//public function css(){
		
		//ob_start();
		
		//include dirname( dirname(__FILE__) ) . '/css/css-item-slideshow.css';
		
		/*$style = '.cpb-slideshow {position: relative}';
		
		$style .= '.cpb-slideshow > .slides > .slide {display:none;}';
		
		$style .= '.cpb-slideshow > .slides > .slide:first-child {display:block;}';
		
		$style .= '.cpb-slideshow.gallery-slideshow > nav{ position:absolute;top:50%;left:0;width:100%;}';
		
		$style .= '.cpb-slideshow.gallery-slideshow > nav a { opacity: 0.5;display: block; width: 50px; height: 50px; position: relative; left: 0; top: -20px;background-color: #ddd;font-size:0;border-radius: 4px;border: 1px solid #ccc;}';
		
		$style .= '.cpb-slideshow.gallery-slideshow > nav a.next { position: absolute; right: 0; left:auto;}';*/
		
		//$style = ob_get_clean();
		
		//return $style;
		
	//} // end admin_css
	
}