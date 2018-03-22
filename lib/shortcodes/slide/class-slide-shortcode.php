<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Slide Shortcode
* @since 3.0.0
*/
class Slide_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'slide_type'        =>  '',
        'img_src'           =>  '',
        'img_id'            => '',
        'link'              => '',
        'item_title'        =>  '',
        'subtitle'          => '',
        'excerpt'           => '',
        'columns'           => '4',
        'tag'               => 'h5',
        'img_ratio'         => 'spacer1x1',
        'unset_excerpt'     => '0',
        'unset_title'       => '0',
        'unset_img'         => '0',
        'unset_link'        => '0',
        'stack_vertical'    => '0',
        'as_lightbox'       => '0',
        'csshook'           => '',
    );


	public function __construct() {

        $local_query_defaults = cpb_get_local_query_defaults();

        $remote_query_defaults = cpb_get_remote_query_defaults();

        $select_query_defaults = cpb_get_select_query_defaults();

        $this->default_settings = array_merge(
            $this->default_settings,
            $local_query_defaults,
            $remote_query_defaults,
            $select_query_defaults
        );

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register slide shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'slide', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode(
            'slide',
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Slide', // Label of the item
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'slide' );

        $items = array();

        if ( ! empty( $atts['slide_type'] ) ) {

            switch( $atts['slide_type'] ) {

                case 'custom':
                    $items = $this->get_items_custom( $atts, $content );
                    break;
                case 'feed':
                    $items = $this->get_items_feed( $atts );
                    break;
                case 'select':
                    $items = $this->get_items_select( $atts );
                    break;
                case 'remote_feed':
                    $items = $this->get_items_remote( $atts );
                    break;
            } // end switch

        } // end if

        if ( $items ) {

            global $cpb_slideshow;

            if ( empty( $cpb_slideshow ) ) {

                $cpb_slideshow = array(
                    'i'     => 1,
                    'type'  => 'gallery',
                );

            }

            switch( $cpb_slideshow['type'] ) {

                case 'gallery':
                default:
                    $html .= $this->get_gallery_slide_html( $items, $atts, $cpb_slideshow );
                    break;

            } // end switch

        } // end if

        return $html;

    } // End get_rendered_shortcode


    public function get_gallery_slide_html( $items, $settings ) {

		global $cpb_slideshow;

		$html = '';

		foreach ( $items as $item ) {

			$active = ( $cpb_slideshow['i'] === 1 )? ' active-slide' : '';
			$bg_image = $item['img'];
			$img = '<img class="slide_img_bg" src="' . cpb_get_plugin_url( 'lib/images/spacer1x1.gif' ) . '" style="background-image:url( ' . $bg_image . ' )" />';
			$link = ( $item['link'] ) ? '<a href="' . $item['link'] . '" class="slide-link" /></a>' : '';
			$title = $item['title'];
			$excerpt = wp_trim_words( $item['excerpt'], 35 );

			ob_start();

			include  __DIR__ . '/slide.php';

			$html .= ob_get_clean();

			$cpb_slideshow['i'] = $cpb_slideshow['i'] + 1;

		} // end foreach

		return $html;

	} // end public


    protected function get_items_custom( $settings, $content ) {

        $promo_item = array();

        $promo_item['img'] = ( ! empty( $settings['img_src'] )) ? $settings['img_src'] : '';

        if ( ! empty( $settings['img_id'] )) {

			$image_array = cpb_get_image_properties_array( $settings['img_id'] );

			$promo_item['img_alt'] = $image_array['alt'];

        } else {

            $promo_item['img_alt'] = '';

        }// End if

        $promo_item['title'] = ( ! empty( $settings['promo_title'] ) ) ? $settings['promo_title'] : '';

        $promo_item['subtitle'] = ( ! empty( $settings['subtitle'] ) ) ? $settings['subtitle'] : '';

        $promo_item['excerpt'] = ( ! empty( $settings['excerpt'] ) ) ? $settings['excerpt'] : '';

        $promo_item['link'] = ( ! empty( $settings['link'] ) ) ? $settings['link'] : '';

		return array( $promo_item );

	} // end item_custom

	protected function get_items_feed( $settings ) {

		$query = cpb_get_query_class();

		$items = $query->get_local_items( $settings, '' );

		return $items;

	} // end item_feed

	protected function get_items_select( $settings ) {

		$query = cpb_get_query_class();

		$items = $query->get_remote_items( $settings, '' );

		return $items;

	} // end item_select

	protected function get_items_remote( $settings ) {

		$query = cpb_get_query_class();

		$items = $query->get_remote_items_feed( $settings, '' );

		return $items;

	}


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

        $custom_form = array(
			'name'    => cpb_get_input_name( $id, true, 'slide_type' ),
			'value'   => 'custom',
			'selected' => $settings['slide_type'],
			'title'   => 'Custom',
			'desc'    => 'Add your own image & text',
			'form'    => $this->form_custom( $id, $settings, $cpb_form ),
			);

		$select_form = array(
			'name'    => cpb_get_input_name( $id, true, 'slide_type' ),
			'value'   => 'select',
			'selected' => $settings['slide_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			'form'    => $cpb_form->get_form_select_post( cpb_get_input_name( $id, true ), $settings ),
			);

		$feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'slide_type' ),
			'value'   => 'feed',
			'selected' => $settings['slide_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $cpb_form->get_form_local_query( cpb_get_input_name( $id, true ), $settings ),
			);

		$remote_feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'slide_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['slide_type'],
			'title'   => 'Feed (Another Site)',
			'desc'    => 'Load external content by category or tag',
			'form'    => $cpb_form->get_form_remote_feed( cpb_get_input_name( $id, true ), $settings ),
			);

$html = $cpb_form->multi_form( array( $custom_form, $select_form, $feed_form, $remote_feed_form ) );

$display = $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'stack_vertical' ), 1, $settings['stack_vertical'], 'Stack Vertical' );


$tags = $cpb_form->get_header_tags();
		unset( $tags['strong'] );
		$display .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'tag' ), $settings['tag'], $tags, 'Tag Type' );

$img_ratio = array( 'spacer1x1' => 'Square', 'spacer3x4' => '3 x 4 ratio', 'spacer4x3' => '4 x 3 ratio' );
		$display .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'img_ratio' ), $settings['img_ratio'], $img_ratio, 'Image Ratio' );

$display .= '<hr/>';

$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_img' ), 1, $settings['unset_img'], 'Hide Image' );

$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_title' ), 1, $settings['unset_title'], 'Hide Title' );

$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_excerpt' ), 1, $settings['unset_excerpt'], 'Hide Summary' );

$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'unset_link' ), 1, $settings['unset_link'], 'Remove Link' );

$display .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'as_lightbox' ), 1, $settings['as_lightbox'], 'Display Lightbox' );

$adv = $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

return array( 'Source' => $html /*, 'Display' => $display, 'Advanced' => $adv */ );

    } // End get_shortcode_form


    protected function form_custom( $id, $settings, $cpb_form ) {

        $form = '<div class="cpb-form-third">';

	    $form .= $cpb_form->insert_media( cpb_get_input_name( $id, true ), $settings );

        $form .= '</div>';

        $form .= '<div class="cpb-form-two-thirds">';

	    $form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'item_title' ), $settings['item_title'], 'Title', 'cpb-full-width' );

        $form .= $cpb_form->textarea_field( cpb_get_input_name( $id, true, 'excerpt' ), $settings['excerpt'], 'Summary/Text', 'cpb-full-width' );

        $form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'link' ), $settings['link'], 'Link', 'cpb-full-width' );

        $form .= '</div>';

        return $form;

    }


} // End Slide

$cpb_shortcode_slide = new Slide_Shortcode();