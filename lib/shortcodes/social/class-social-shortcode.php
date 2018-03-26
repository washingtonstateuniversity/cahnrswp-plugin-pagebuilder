<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Social Shortcode
* @since 3.0.0
*/
class Social_Shortcode {

	protected $prefix = '';

	// @var array $default_settings Array of default settings
	protected $default_settings = array(
		'twitter'          => '',
		'twitter_order'    => '',
		'facebook_order'   => '',
		'instagram'        => '',
		'instagram_order'  => '',
		'instagram_target' => '',
		'height'           => '',
		'csshook'          => '',
	);


	public function __construct() {

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	} // End __construct


	/*
	* @desc Register social shortcode
	* @since 3.0.0
	*/
	public function register_shortcode() {

		\add_shortcode( 'social', array( $this, 'get_rendered_shortcode' ) );

		cpb_register_shortcode(
			'social',
			$args = array(
				'form_callback'         => array( $this, 'get_shortcode_form' ),
				'label'                 => 'Social', // Label of the item
				'render_callback'       => array( $this, 'get_rendered_shortcode' ), // Callback to render shortcode
				'default_atts'          => $this->default_settings,
				'in_column'             => true, // Allow in column
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
		$atts = \shortcode_atts( $this->default_settings, $atts, 'social' );

		if ( empty( $atts['height'] ) ) {

			$atts['height'] = 800;

		} // end if

		$feeds = array();

		if ( ! empty( $atts['twitter'] ) ) {

			$order = ( ! empty( $atts['twitter_order'] ) ) ? $atts['twitter_order'] : 2;

			$feeds[ $order ] = array(
				'type' => 'twitter',
				'src' => $atts['twitter'],
				'height' => $atts['height'],
			);

		} // End if

		if ( ! empty( $atts['facebook'] ) ) {

			$order = ( ! empty( $atts['facebook_order'] ) ) ? $atts['facebook_order'] : 3;

			$feeds[ $order ] = array(
				'type' => 'facebook',
				'src' => $atts['facebook'],
				'height' => $atts['height'],
			);

		} // End if

		if ( ! empty( $atts['instagram'] ) ) {

			$order = ( ! empty( $atts['instagram_order'] ) ) ? $atts['instagram_order'] : 4;

			$feeds[ $order ] = array(
				'type' => 'instagram',
				'src' => $atts['instagram'],
				'height' => $atts['height'],
				'target' => $atts['instagram_target'],
				'is_link' => true,
			);

		} // End if

		$html = '<div class="cpb-social-item">';

		ksort( $feeds );

		$icon_html = '<div class="cpb-social-icons-wrapper">';

		$content_html = '<div class="cpb-social-content-wrapper">';

		$i = 0;

		foreach ( $feeds as $index => $feed ) {

			$active = ( 0 === $i ) ? ' active' : '';

			$content_html .= '<div class="cpb-social-content cpb-social-content-' . $feed['type'] . $active . '">';

			switch ( $feed['type'] ) {

				case 'facebook':
					$icon_html .= $this->get_facebook_html( $feed, $i, true );
					$content_html .= $this->get_facebook_html( $feed, $i );
					break;
				case 'twitter':
					$icon_html .= $this->get_twitter_html( $feed, $i, true );
					$content_html .= $this->get_twitter_html( $feed, $i );
					break;
				case 'instagram':
					$icon_html .= $this->get_instagram_html( $feed, $i, true );
					$content_html .= $this->get_instagram_html( $feed, $i );
					break;
			} // End

			$content_html .= '</div>';

			$i++;

		} // end foreach

		$icon_html .= '</div>';

		$content_html .= '</div>';

		$html .= $icon_html . $content_html . '</div>';

		return $html;

	} // End get_rendered_shortcode


	protected function get_facebook_html( $feed, $i, $is_icon = false ) {

		$html = '';

		$height = $feed['height'];

		if ( $is_icon ) {

			$html .= '<div class="cpb-social-icon cpb-social-icon-facebook"></div>';

		} else {

			$facebook_src = $feed['src'];

			\ob_start();

			include __DIR__ . '/facebook.php';

			$html .= \ob_get_clean();

		} // End if

		return $html;

	} // End get_facebook_html


	protected function get_twitter_html( $feed, $i, $is_icon = false ) {

		$html = '';

		$height = $feed['height'];

		if ( $is_icon ) {

			$html .= '<div class="cpb-social-icon cpb-social-icon-twitter"></div>';

		} else {

			$twitter_src = $feed['src'];

			\ob_start();

			include __DIR__ . '/twitter.php';

			$html .= \ob_get_clean();

		} // End if

		return $html;

	} // End get_facebook_html

	protected function get_instagram_html( $feed, $i, $is_icon = false ) {

		$html = '';

		$height = $feed['height'];

		if ( $is_icon ) {

			$html .= '<div class="cpb-social-icon cpb-social-icon-instagram is-link"><a href="' . $feed['src'] . '"';

			if ( $feed['target'] ) {

				$html .= ' target="_blank" ';

			} // End if

			$html .= ' >Visit Instagram</a></div>';

		} else {

			$html .= '';

		} // End if

		return $html;

	} // End get_facebook_html


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

		$html = $cpb_form->text_field( cpb_get_input_name( $id, true, 'twitter' ), $settings['twitter'], 'Twitter URL' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'twitter_order' ), $settings['twitter_order'], 'Twitter Order' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'facebook' ), $settings['facebook'], 'Facebook URL' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'facebook_order' ), $settings['facebook_order'], 'Facebook Order' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'instagram' ), $settings['instagram'], 'Instagram URL' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'instagram_order' ), $settings['instagram_order'], 'Instagram Order' );

		$html .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'instagram_target' ), 1, $settings['instagram_target'], 'Instagram: Open In New Window' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'height' ), $settings['height'], 'height (no px)' );

		$html .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		return $html;

	} // End get_shortcode_form

} // End Social

$cpb_shortcode_social = new Social_Shortcode();
