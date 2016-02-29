<?php

class CPB_Item_Video extends CPB_Item {
	
	protected $name = 'Embed Video';
	
	protected $slug = 'video';
	
	
	public function item( $settings , $content ){
		
		$html = '';
		
		if ( $settings['vid_id'] ){
		
			$html .= '<div class="cpb-video-wrapper" style="position: relative">';
	
				switch( $settings['vid_type'] ) {
	
					case 'vimeo':
						$html .= $this->get_vimeo_embed( $settings );
						break;
					default:
						$html .= $this->get_youtube_embed( $settings );
						break;
				} // end switch
	
			$html .= '</div>';
		
		} // end if

		return $html;
		
		$html = '';
		
		return $html;
		
	}// end item
	
	public function admin_item( $settings , $content ){
		
		$html = '';
		
		if ( ! empty( $settings['vid_id'] ) ) {
		
			if ( $settings['vid_type'] == 'youtube' ){

				$src = 'http://img.youtube.com/vi/' . $settings['vid_id'] . '/default.jpg';
			
			} else {
				
				$src = '';
				
			} // end if
			
			$vid_style = 'background-image:url(' . $src . ');background-position: center center;background-size:cover;';
			
		} else {
			
			$vid_style = '';
			
		} // end if
		
		$html .= '<img src="' . plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) ) . '" style="' . $vid_style . 'width:100%;display:block;background-color:#000;" />';

		//$html .= '<div style="position:absolute;width:100%;height:100%;background:#000;top:0;left:0;"></div>';

		return $html;
		
	} // end admin_item
	
	public function get_youtube_embed( $settings ) {
		
		$vid_style = 'position:absolute;height:100%;width:100%;top:0;left:0;';

		$html = '';

		if ( ! empty( $settings['vid_id'] ) ) {

			$html .= '<img src="' . plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) ) . '" style="width:100%;display:block" />';

			$html .= '<iframe src="https://www.youtube.com/embed/' . $settings['vid_id'] . '" frameborder="0" allowfullscreen style="' . $vid_style . '"></iframe>';

		} // end if

		return $html;

	} // end get_youtube_embed
	
	
	public function get_vimeo_embed( $settings ) {
		
		$vid_style = 'position:absolute;height:100%;width:100%;top:0;left:0;';

		$html = '';

		if ( ! empty( $settings['vid_id'] ) ) {

			$html .= '<img src="' . plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) ) . '" style="width:100%;display:block" />';

			$html .= '<iframe src="//player.vimeo.com/video/' .  $settings['vid_id'] . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" frameborder="0" allowfullscreen="allowfullscreen" style="' . $vid_style . '"></iframe>';

		} // end if

		return $html;

	} // end get_vimeo_embed
	
	
	public function form( $settings , $content ){
		
		$youtube_form = array(
			'name'    => $this->get_input_name( 'vid_type' ),
			'value'   => 'youtube',
			'selected' => $settings['vid_type'],
			'title'   => 'YouTube Video',
			'desc'    => 'Display YouTube video by ID',
			'form'    => $this->youtube_form( $settings ),
			);
			
		$vimeo_form = array(
			'name'    => $this->get_input_name( 'vid_type' ),
			'value'   => 'vimeo',
			'selected' => $settings['vid_type'],
			'title'   => 'Vimeo Video',
			'desc'    => 'Display Vimeo video by ID',
			'form'    => $this->vimeo_form( $settings ),
			);
			
		$html = $this->form_fields->multi_form( array( $youtube_form , $vimeo_form ) );
		
		return $html;
		
	} // end form
	
	protected function youtube_form( $settings ){
		
		$form = $this->form_fields->text_field( $this->get_input_name( 'vid_id' ), $settings['vid_id'], 'YouTube Video ID' );
		
		return $form;
		
	}
	
	protected function vimeo_form( $settings ){
		
		$form = $this->form_fields->text_field( $this->get_input_name( 'vid_id' ), $settings['vid_id'], 'Vimeo Video ID' );
		
		return $form;
		
	}
	
	
	protected function clean( $settings ){
		
		$clean = array();

		$clean['vid_id'] = ( ! empty( $settings['vid_id'] ) ) ? sanitize_text_field( $settings['vid_id'] ) : '';

		$clean['vid_type'] = ( ! empty( $settings['vid_type'] ) ) ? sanitize_text_field( $settings['vid_type'] ) : '';

		return $clean;
		
	}
	
	
}