<?php

class CPB_Item_Textblock extends CPB_Item {
	
	protected $name = 'Text/HTML';
	
	protected $slug = 'textblock';
	
	protected $uses_wp_editor = true;
	
	public function item( $settings , $content ){
		
		$content = do_shortcode( $this->get_more_content( $content , $settings ) );
		
		//if ( ! empty( $settings['textcolor'] ) ) $content = '<span class="' . $settings['textcolor'] . '-text">' . $content . '</span>';
		
		$html = apply_filters( 'the_content' , $content );
		
		if ( ! empty( $settings['is_callout'] ) || ! empty( $settings['bgcolor'] ) || ! empty( $settings['csshook'] ) || ! empty( $settings['textcolor'] ) || ! empty( $settings['list_style'] ) ) {
			
			$class = array();
			
			if ( ! empty( $settings['textcolor'] ) ) $class[] = $settings['textcolor'] . '-text';
			
			if ( ! empty( $settings['is_callout'] ) ) $class[] = 'is-callout';
			
			if ( ! empty( $settings['csshook'] ) ) $class[] = $settings['csshook'];
			
			if ( ! empty( $settings['bgcolor'] ) ) $class[] = $settings['bgcolor'] . '-back';
			
			if ( ! empty( $settings['list_style'] ) ) $class[] = $settings['list_style'];
			 
			$html = '<div class="cpb-textblock cpb-item ' . implode( ' ' , $class ) . '">' . $html . '</div>';  
			
		} // end if
		
		return $html;
		
	}// end item
	
	
	public function form( $settings , $content ){
		
		$style_array = array(
			'' 						=> 'Default',
			'list-style-arrows' 	=> 'Arrows',
			'list-style-drop-down' 	=> 'Accordion',
		);
		
		$html = $this->form_fields->text_field( $this->get_input_name('title'), $settings['title'], 'Title' );
		
		ob_start();
		
		wp_editor( $content , '_cpb_content_' . $this->get_id() );
		
		$html .= ob_get_clean();
		
		$adv = $this->form_fields->select_field( $this->get_input_name('textcolor'), $settings['textcolor'], $this->form_fields->get_wsu_colors(), 'Text Color' );
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('bgcolor'), $settings['bgcolor'], $this->form_fields->get_wsu_colors(), 'Background Color' );
		
		$adv .= $this->form_fields->select_field( $this->get_input_name('list_style'), $settings['list_style'], $style_array, 'List Style' );
		
		$adv .= $this->form_fields->checkbox_field( $this->get_input_name('is_callout'), 1, $settings['is_callout'], 'Is Callout' );
		
		$adv .= $this->form_fields->text_field( $this->get_input_name('csshook'), $settings['csshook'], 'CSS Hook' );
		
		return array('Basic' => $html , 'Advanced' => $adv );
		
	} // end form
	
	public function editor_default_html(){
		
		return 'Add Text Here';
		
	} // end editor_default_html
	
	public function clean( $settings ){
		
		$clean = array();
		
		$clean['title'] = ( ! empty( $settings['title'] ) ) ? sanitize_text_field( $settings['title'] ) : '';
		
		$clean['bgcolor'] = ( ! empty( $settings['bgcolor'] ) ) ? sanitize_text_field( $settings['bgcolor'] ) : '';
		
		$clean['textcolor'] = ( ! empty( $settings['textcolor'] ) ) ? sanitize_text_field( $settings['textcolor'] ) : '';
		
		$clean['list_style'] = ( ! empty( $settings['list_style'] ) ) ? sanitize_text_field( $settings['list_style'] ) : '';
		
		$clean['is_callout'] = ( ! empty( $settings['is_callout'] ) ) ? sanitize_text_field( $settings['is_callout'] ) : '';
		
		$clean['csshook'] = ( ! empty( $settings['csshook'] ) ) ? sanitize_text_field( $settings['csshook'] ) : '';
		
		
		return $clean;
		
	}
	
	private function get_more_content( $content , $settings ){
		
		if ( strpos( $content , '<span id="more-' ) !== false ){
			
			$content_parts = preg_split( '/<span id="more-.*?"><\/span>/' , $content );
			
			$link = '<div id="' . $this->get_id() . '" class="cpb-more-button"><a href="#"><span>Continue Reading</span></a></div>';
			
			$new_content = '<div class="cpb-more-content">';
			
			$new_content .= '<div class="cpb-more-content-intro">' . $content_parts[0] . '</div>';
			
			$new_content .= '<div class="cpb-more-content-continue">' . $content_parts[1] . '</div>';
			
			$new_content .=  $link . '</div>';
			
			$content = $new_content;
			
		} // end if
		
		return $content;
		
	} // end get_more_content
	
	protected function css() {
		
		$style = '.cpb-more-button::before {content:"";display:block;position:absolute;width:100%;height:1px;top:50%;left:0;background-color:#981e32;}';
		
		$style .= '.cpb-more-content {position: relative;}';
		
		$style .= '.cpb-more-button {position:relative;' .
				'background: -moz-linear-gradient(top,  rgba(249,249,249,0) 0%, rgba(249,249,249,1) 100%);' .
				'background: -webkit-linear-gradient(top,  rgba(249,249,249,0) 0%,rgba(249,249,249,1) 100%);' .
				'background: linear-gradient(to bottom,  rgba(249,249,249,0) 0%,rgba(249,249,249,1) 100%);' .
				'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#00ffffff\', endColorstr=\'#F9F9F9\',GradientType=0 );}';
		
		$style .= '.cpb-more-button a {display:block;position:relative;text-align:center;}';
		
		$style .= '.cpb-more-button a span {display:inline-block;position:relative;padding:0.25rem 1rem;background-color: rgb(249,249,249);}';
		
		$style .= '.cpb-more-content-continue {display:none;}';
		
		$style .= '.cpb-textblock {display:block;}';
		
		$style .= '.cpb-textblock.is-callout {box-sizing:border-box;padding: 1.5rem;}';
		
		
		return $style;
		
	}
	
	
}