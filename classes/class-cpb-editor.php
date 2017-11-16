<?php
class CPB_Editor {
	
	protected $items;
	
	protected $form_fields;
	
	public function __construct( $items ){
		
		$this->items = $items;
		
		require_once CAHNRS_Pagebuilder_Plugin::$dir . 'classes/class-cpb-form-fields.php';
		$this->form_fields = new CPB_Form_Fields();
		
	} // end __construct
	
	public function get_editor( $post , $settings ){
		
		$items = $this->items->get_items_from_content( $post->post_content , array( 'row','pagebreak' ) , 'row' );
		
		$html = '<div id="cpb-editor">';
		
			$html .= $this->get_editor_options( $post , $settings );
			
			if ( $settings['_cpb_pagebuilder'] ) {
		
				$html .= $this->get_layout_editor( $items , $post ); 
				
				$html .= $this->get_form_editor( $items , $post );
				
				$html .= $this->get_excerpt_options( $post , $settings ); 
			
			} // end if 
			
			$html .= wp_nonce_field( 'save_cahnrs_pagebuilder_' . $post->ID , 'cahnrs_pagebuilder_key' , true , false );
			
			$html .= '<input type="hidden" name="ajax-nonce" value="' .  wp_create_nonce( 'cahnrs_pb_ajax_'. $post->ID ) . '" />';
			
			$html .= '<input type="hidden" name="ajax-post-id" value="' .  $post->ID . '" />';
		
		return $html . '</div>';
		
	} // end get_editor
	
	protected function get_editor_options( $post , $settings ){
		
		$values = array( 'Default Editor' , 'Layout Editor' );
		
		$html .= '<div id="cpb-editor-options">';
		
		foreach( $values as $key => $value ){
			
			$id = 'cpb-editor-option-' . $key;
			
			$active = ( $settings['_cpb_pagebuilder'] == $key ) ? ' active':'';
			
			$html .= '<label class="' . $active . '" for="' . $id . '">' . $value . '</label>';
			
			$html .= '<input type="radio" name="_cpb_pagebuilder" id="' . $id . '" value="' . $key . '" ' . checked( $settings['_cpb_pagebuilder'] , $key , false ) . ' />';
			
		} // end foreach	
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_editor_options
	
	protected function get_excerpt_options( $post , $settings ){
		
		$excerpt_op = get_post_meta( $post->ID , '_cpb_m_excerpt' , true );
		
		$values = array( 'Default Excerpt' , 'Custom Excerpt' );
		
		$html .= '<div id="cpb-excerpt-options">';
		
		foreach( $values as $key => $value ){
			
			$id = 'cpb-excerpt-option-' . $key;
			
			$active = ( $settings['_cpb_m_excerpt'] == $key ) ? ' active':'';
			
			$html .= '<label class="' . $active . '" for="' . $id . '">' . $value . '</label>';
			
			$html .= '<input type="radio" name="_cpb_m_excerpt" id="' . $id . '" value="' . $key . '" ' . checked( $settings['_cpb_m_excerpt'] , $key , false ) . ' />';
			
		} // end foreach
		
		$disalbed = ( $settings['_cpb_m_excerpt'] ) ? '':'disabled';
		
		$html .= '<textarea name="_cpb_excerpt" ' . $disalbed  . '>';
		
		$html .= $post->post_excerpt;
		
		$html .= '</textarea>';
		
		$html .= '</div>';
		
		return $html;
		
	} // end get_editor_options
	
	
	public function get_layout_editor( $items , $post ){
		
		$child_ids = array();
		
		$html = '<div id="cpb-editor-layout" class="cpb-item">';
		
			$html .= '<header></header>';
		
			$html .= '<div class="cpb-child-set cpb-layout-set">';
			
				foreach( $items as $item ){
					
					$child_ids[] = $item->get_id();
					
					$html .= $item->the_editor();
					
				} // end foreach
			
			$html .= '</div>'; 
			
			$html .= '<footer></footer>';
			
			$html .= '<fieldset>';
				
				$html .= '<input class="cpb-children-input" type="hidden" name="_cpb[layout]" value="' . implode( ',' , $child_ids ) . '" >';
			
			$html .= '</fieldset>';
			
			
			$html .= $this->get_add_item_form( $post ). '<hr/>';
			
			$html .= $this->get_add_row_form( $post );
			
		
		return $html . '</div>';
		
	} // end get_layout_editor
	
	public function get_form_editor( $items , $post ){
		
		$html = '<div id="cpb-editor-forms">';
			
			foreach( $items as $item ){
				
				$html .=  $item->the_form();
				
			} // end foreach
			
			$html .= '<hr/>';
			
			$text_editors = array( 'textblock' );
			
			foreach( $text_editors as $text_editor ){
				
				for ( $i = 0; $i < 10; $i++ ){
					
					$item = $this->items->get_item( $text_editor );
					
					$html .= $item->the_form( false , false , 'cpb-blank-editor' ); 
				
				} // end for
				
			} // end foreach
		
		return $html . '</div>';
		
	} // end get_form_editor
	
	
	public function get_add_row_form( $post ){
		
		$item = $this->items->get_item( 'row' );
		
		$html = $item->get_add_row( $post );
		
		return $html;
		
	} // end get_add_row_form
	
	public function get_add_item_form( $post ){
		
		$items = $this->items->get_items( true );
		
		
		$html = '<ul class="cpb-add-items-set">';
		
		foreach( $items as $slug => $data ){
			
			if ( 'widget' == $slug ) continue;
			
			$item = $this->items->get_item( $slug , array() , '' , false );
			
			$html .= '<li>';
			
				$html .= '<input type="text" name="slug" value="' . $slug . '" />';
				
				$html .= '<span>' . $item->get_name() . '</span>';;
			
			$html .= '</li>';
			
		} // end foreach
		
		$html .= '</ul>';
		
		$forms = array( 'Select Item' => $html , 'Select Widget' => $this->get_form_widget_items() );
		
		return $this->form_fields->get_item_form( 'cpb-add-item-form' , $forms , array('title' => 'Add Items & Widgets') );
		
	} // end get_add_item_form
	
	protected function get_form_widget_items(){
		
		global $wp_widget_factory;
		
		$widget_array = array();
		
		foreach( $wp_widget_factory->widgets as $class => $widget ){
			
			$obj = new stdClass();
			
			$obj->name = $widget->name;
			
			$obj->desc = $widget->widget_options['description'];
			
			$obj->slug = 'widget';
			
			$obj->widget_id = $widget->id_base;
			
			$obj->widget_class = $class;
			
			$widget_array[ $class ] = $obj;
			
		}// end foreach
		
		$html = '<ul class="cpb-add-items-set">';
		
		foreach( $widget_array as $widget ){
			
			$html .= '<li>';
			
				$html .= '<input type="text" name="slug" value="widget" />';
				
				$html .= '<span>' . $widget->name . '</span>';
				
				$html .= '<input type="text" name="settings[widget_type]" value="' . $widget->widget_class . '" />';
			
			$html .= '</li>';
			
		} // end foreach
		
		$html .= '</ul>';
		
		return $html;
		
	}
	
	
	
}