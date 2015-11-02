<?php
class Item_Video_PB extends Item_PB {

	public $slug = 'video';

	public $name = 'Video';

	public $desc = 'Add Youtube Video';

	public $form_size = 'medium';

	public $vid_style = 'position:absolute;height:100%;width:100%;top:0;left:0;';

	public function item( $settings, $content ) {

		$html = '<div class="cpb-video-wrapper" style="position: relative">';

			switch( $settings['vid_type'] ) {

				case 'vimeo':
					$html .= $this->get_vimeo_embed( $settings );
					break;
				default:
					$html .= $this->get_youtube_embed( $settings );
					break;
			} // end switch

		$html .= '</div>';

		return $html;

	} // end item

	public function get_vimeo_embed( $settings ) {

		$html = '';

		if ( ! empty( $settings['vimeo_id'] ) ) {

			$html .= '<img src="' . CWPPBURL . 'images/video-spacer.gif" style="width:100%;display:block" />';

			$html .= '<iframe src="//player.vimeo.com/video/' .  $settings['vimeo_id'] . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" frameborder="0" allowfullscreen="allowfullscreen" style="' . $this->vid_style . '"></iframe>';

		} // end if

		return $html;

	} // end get_vimeo_embed

	public function get_youtube_embed( $settings ) {

		$html = '';

		if ( ! empty( $settings['vid_id'] ) ) {

			$html .= '<img src="' . CWPPBURL . 'images/video-spacer.gif" style="width:100%;display:block" />';

			$html .= '<iframe src="https://www.youtube.com/embed/' . $settings['vid_id'] . '" frameborder="0" allowfullscreen style="' . $this->vid_style . '"></iframe>';

		} // end if

		return $html;

	} // end get_vimeo_embed


	public function editor( $settings, $editor_content ) {

		$html = '';

		if ( ! empty( $settings['vid_id'] ) ) {

			$html .= '<img src="http://img.youtube.com/vi/' . $settings['vid_id'] . '/default.jpg" style="width: 100%; display: block;" />';

		} else if ( ! empty( $settings['vid_id'] ) ) {

			 $html .= '<div class="cpb-empty-editor">No Video Set</div>';

		} else {

			$html .= '<div class="cpb-empty-editor">No Video Set</div>';

		}// end if

		return $html;

	} // end editor

	public function form( $settings ) {
		
		/*$video_subform = array(
			'form'        => Forms_PB::text_field( $this->get_name_field('vid_id'), $settings['vid_id'], 'YouTube Video ID' ),
			'field_name'  => $this->get_name_field('vid_type'),
			'val'         => 'youtube',
			'current_val' => $settings['vid_type'],
			'title'       => 'YouTube Video',
			'summary'     => 'Display YouTube video by ID'
		);*/
		
		//$html = Forms_PB::get_subform( $video_subform ); 
		
		//$html .= Forms_PB::get_subform( $vimeo_subform );
		
		$html = $this->accordion_radio( 
			$this->get_name_field('vid_type') , 
			'youtube' , 
			$settings['vid_type'] , 
			'YouTube Video' , 
			Forms_PB::text_field( $this->get_name_field('vid_id'), $settings['vid_id'], 'YouTube Video ID' ),
			'Display YouTube video by ID' 
			); 
		
		$html .= $this->accordion_radio( 
			$this->get_name_field('vid_type') , 
			'vimeo' , 
			$settings['vid_type'] , 
			'Vimeo Video' , 
			Forms_PB::text_field( $this->get_name_field('vimeo_id'), $settings['vimeo_id'], 'Vimeo Video ID' ),
			'Display Vimeo video by ID' 
			); 

		/*$sub_form = array(
			'YouTube' => array(
				'form'  => Forms_PB::text_field( $this->get_name_field('vid_id'), $settings['vid_id'], 'YouTube Video ID' ),
				'value' => 'youtube',
			),
			'Vimeo' => array(
				'form'  => Forms_PB::text_field( $this->get_name_field('vimeo_id'), $settings['vimeo_id'], 'Vimeo Video ID' ),
				'value' => 'vimeo',
			),
		);*/

		//$html = Forms_PB::get_sub_form( $sub_form, $this->get_name_field('vid_type'), $settings['vid_type'] );

		return $html;

	} // end form

	public function clean( $s ) {

		$clean = array();

		$clean['vid_id'] = ( ! empty( $s['vid_id'] ) ) ? sanitize_text_field( $s['vid_id'] ) : '';

		$clean['vimeo_id'] = ( ! empty( $s['vimeo_id'] ) ) ? sanitize_text_field( $s['vimeo_id'] ) : '';

		$clean['vid_type'] = ( ! empty( $s['vid_type'] ) ) ? sanitize_text_field( $s['vid_type'] ) : 'youtube';

		return $clean;

	} // end clean

}