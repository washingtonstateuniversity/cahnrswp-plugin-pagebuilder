<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Video Shortcode
* @since 3.0.0 
*/
class Video_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'vid_id'        => '',
        'vid_type'      => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register video shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'video', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode( 
            'video', 
            $args = array(
                'form_callback'             => array( $this, 'get_shortcode_form' ),
                'label'                     => 'Video', // Label of the item
                'render_callback'           => array( $this, 'get_rendered_shortcode' ), // Callback to render shortcode
                'editor_render_callback'    => array( $this, 'get_editor_content' ), // Callback to render shortcode
                'default_atts'              => $this->default_settings,
                'in_column'                 => true, // Allow in column
            ) 
        );

    } // End register_shortcode


    /*
    * @desc Render the shortcode
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode output
    */
    public function get_rendered_shortcode( $atts, $content ) {

        $html = '';

        // Check default settings 
        $atts = \shortcode_atts( $this->default_settings, $atts, 'video' );


        if ( ! empty( $atts['vid_id'] ) ) {

            $html .= '<div class="cpb-video-wrapper" style="position: relative">';

            switch( $atts['vid_type'] ) {

                case 'vimeo':
                    $video_id = $atts['vid_id'];
                    ob_start();
                    include  __DIR__ . '/vimeo-video.php';
                    $html .= ob_get_clean();
                    break;
                default:
                    $video_id = $this->get_video_id_from_url( $atts['vid_id'] );
                    ob_start();
                    include  __DIR__ . '/youtube-video.php';
                    $html .= ob_get_clean();
                    break;
            } // end switch

            $html .= '</div>';

        } // End if

        return $html;

    } // End get_rendered_shortcode


    /*
    * @desc Override dhe editor content view
    * @since 3.0.0
    *
    * @param array $settings Shortode atts
    * @param string $content Shortcode content
    *
    * @return string HTML for use in Editor
    */
    public function get_editor_content( $settings, $content ) {

		$html = '';

		if ( ! empty( $settings['vid_id'] ) ) {

			if ( $settings['vid_type'] == 'youtube' ) {

                $video_id = $this->get_video_id_from_url( $settings['vid_id'] );

				$src = 'http://img.youtube.com/vi/' . $video_id . '/default.jpg';

			} else {

				$src = '';

			} // end if

			$vid_style = 'background-image:url( ' . $src . ' );background-position: center center;background-size:cover;';

		} else {

			$vid_style = '';

		} // end if

		$html .= '<img src="' . plugins_url( 'images/spacer16x9.gif', dirname(__FILE__) ) . '" style="' . $vid_style . 'width:100%;display:block;background-color:#000;" alt=""/>';

		//$html .= '<div style="position:absolute;width:100%;height:100%;background:#000;top:0;left:0;"></div>';

		return $html;

	} // end admin_item


    /*
	 * @desc - Extracts video id from url
     * @since 3.0.0
     * 
	 * @return string - The video id.
	*/
	protected function get_video_id_from_url( $url ) {

		$video_id = $url;

		if ( strpos( $url,'watch?v=' ) ) {

			$url = explode( 'watch?v=', $url );

			$video_id = $url[1];

		} else if ( strpos( $url,'.be/' ) ) { //https://youtu.be/nkXGohB02V0

			$url = explode( '.be/', $url );

			$video_id = $url[1];

		} // End if

		return $video_id;

	} // end cwp_get_video_id_from_url


    /*
    * @desc Get HTML for shortcode form
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode form output
    */
    public function get_shortcode_form( $id, $settings, $content ) {

        $cpb_form = cpb_get_form_class();

        $youtube_form = array(
			'name'    => cpb_get_input_name( $id, true, 'vid_type' ),
			'value'   => 'youtube',
			'selected' => $settings['vid_type'],
			'title'   => 'YouTube Video',
			'desc'    => 'Display YouTube video by ID',
			'form'    => $cpb_form->text_field( cpb_get_input_name( $id, true, 'vid_id' ), $settings['vid_id'], 'YouTube Video ID' ),
			);

		$vimeo_form = array(
			'name'    => cpb_get_input_name( $id, true, 'vid_type' ),
			'value'   => 'vimeo',
			'selected' => $settings['vid_type'],
			'title'   => 'Vimeo Video',
			'desc'    => 'Display Vimeo video by ID',
			'form'    => $cpb_form->text_field( cpb_get_input_name( $id, true, 'vid_id' ), $settings['vid_id'], 'Vimeo Video ID' ),
			);

		$html = $cpb_form->multi_form( array( $youtube_form, $vimeo_form ) );

		return $html;

    } // End get_shortcode_form

} // End Video

$cpb_shortcode_video = new Video_Shortcode();