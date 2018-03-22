<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Promo Shortcode
* @since 3.0.0
*/
class Promo_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'promo_type'        =>  '',
        'img_src'           =>  '',
        'img_id'            => '',
        'link'              => '',
        'promo_title'       =>  '',
        'subtitle'          => '',
        'excerpt'           => '',
        'columns'           => '4',
        'tag'               => 'h2',
        'img_ratio'         => 'spacer1x1',
        'unset_excerpt'     => '0',
        'unset_title'       => '0',
        'unset_img'         => '0',
        'unset_link'        => '0',
        'stack_vertical'    => '0',
        'as_lightbox'       => '0',
        'csshook'           => '',
        'post_id'           => '',
        'offset'            => '',
    );




	public function __construct() {

        $local_query_defaults = cpb_get_local_query_defaults();

        $remote_query_defaults = cpb_get_remote_query_defaults();

        $select_query_defautls = cpb_get_select_query_defaults();

        $this->default_settings = array_merge(
            $this->default_settings,
            $local_query_defaults,
            $remote_query_defaults
        );

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register promo shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'promo', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode(
            'promo',
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'label'                 => 'Promo', // Label of the item
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'promo' );

        $promo_items = array();

        if ( ! empty( $atts['promo_type'] ) ) {

			switch( $atts['promo_type'] ) {

				case 'custom':
					$promo_items = $this->get_custom_items( $atts, $content );
					break;
				case 'feed':
                    $promo_items = $this->get_feed_items( $atts );
					break;
				case 'select':
                    $promo_items= $this->get_select_items( $atts );
					break;
				case 'remote_feed':
                    $promo_items = $this->get_remote_items( $atts );
					break;
			} // end switch

        } // end if

        if ( ! empty( $promo_items ) && is_array( $promo_items ) ) {

            $img_ratio = $atts['img_ratio'];

            $classes_array = array(
                'cpb-item',
                'cpb-promo',
                'cpb-promo-' . $atts['promo_type'],
            );

            if ( ! empty( $atts['as_lightbox'] ) ) {

                $classes_array[] = 'as-lightbox';

            } // end if

            if ( ! empty( $atts['stack_vertical'] ) ) $classes_array[] = 'stack-vertical';

            $tag = ( ! empty( $atts['tag'] ) ) ? $atts['tag'] : '';

            foreach ( $promo_items as $index => $promo_item ) {

                $promo_item = cpb_check_advanced_display( $promo_item, $atts );

                if ( ! empty( $promo_item['img'] ) ) $classes_array[] = 'has-image';

                $excerpt = ( ! empty( $promo_item['excerpt'] ) ) ? $promo_item['excerpt'] : '';

                if ( $atts['promo_type'] != 'custom' ) {

                    $excerpt = wp_trim_words( strip_shortcodes( wp_strip_all_tags( $excerpt, true ) ), 35, '...' );

                } // End if

                $request_url = $promo_item['link'] . '?cpb-get-template=lightbox';

                $class = implode( ' ', $classes_array );

                $img_src = ( ! empty( $promo_item['img'] ) ) ? $promo_item['img'] : '';

                $img_alt = ( ! empty( $promo_item['img_alt'] ) ) ? $promo_item['img_alt'] : '';

                $title = ( ! empty( $promo_item['title'] ) ) ? $promo_item['title'] : '';

                $subtitle = ( ! empty( $promo_item['subtitle'] ) ) ? $promo_item['subtitle'] : '';

                $excerpt = ( ! empty( $promo_item['excerpt'] ) ) ? $promo_item['excerpt'] : '';

                $link = ( ! empty( $promo_item['link'] ) ) ? $promo_item['link'] : '';

                \ob_start();

			    include  __DIR__ . '/promo.php';

                $html .= \ob_get_clean();

            } // End foreach

        } // End if

        if ( $html ) {

			$class = ( ! empty( $atts['csshook'] ) ) ? $atts['csshook'] : '';

			$html = '<div class="cpb-item cpb-promo-wrap ' . $class . '">' . $html . '</div>';

		} // end if

        return $html;

    } // End get_rendered_shortcode


    protected function get_custom_items( $settings, $content ) {

        $promo_item = array();

        $promo_item['img'] = ( ! empty( $settings['img_src'] )) ? $settings['img_src'] : '';

        if ( ! empty( $settings['img_id'] )) {

			$image_array = cpb_get_image_properties_array( $settings['img_id'] );

			$promo_item['img_alt'] = $image_array['alt'];

        } // End if

        $promo_item['title'] = ( ! empty( $settings['promo_title'] ) ) ? $settings['promo_title'] : '';

        $promo_item['subtitle'] = ( ! empty( $settings['subtitle'] ) ) ? $settings['subtitle'] : '';

        $promo_item['excerpt'] = ( ! empty( $settings['excerpt'] ) ) ? $settings['excerpt'] : '';

        $promo_item['link'] = ( ! empty( $settings['link'] ) ) ? $settings['link'] : '';

		return array( $promo_item );

    } // end item_custom


    protected function get_feed_items( $settings ) {

		$query = cpb_get_query_class();

		$promo_items = $query->get_local_items( $settings, '' );

		return $promo_items;

    } // end item_feed


    protected function get_select_items( $settings ) {

		$promo_items = array();

		$ids = explode( ',', $settings['post_ids'] );

		foreach ( $ids as $post_id ) {

			$promo_items[] = cpb_get_post_item( $post_id, 'medium' );

		} // End foreach

		return $promo_items;

    } // end item_select


    protected function get_remote_items( $settings ) {

		$query = cpb_get_query_class();

		$promo_items = $query->get_remote_items_feed( $settings, '' );

        return $promo_items;

    } // End get_remote_items



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

        $form_custom = '<div class="cpb-form-third">';

        $form_custom .= $cpb_form->insert_media( cpb_get_input_name( $id, true ), $settings );

        $form_custom .= '</div>';

        $form_custom .= '<div class="cpb-form-two-thirds">';

        $form_custom .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'promo_title' ), $settings['promo_title'], 'Title', 'cpb-full-width' );

        $form_custom .= $cpb_form->textarea_field( cpb_get_input_name( $id, true, 'excerpt' ), $settings['excerpt'], 'Summary/Text', 'cpb-full-width' );

        $form_custom .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'link' ), $settings['link'], 'Link', 'cpb-full-width' );

        $form_custom.= '</div>';

        $custom_form = array(
			'name'    => cpb_get_input_name( $id, true, 'promo_type' ),
			'value'   => 'custom',
			'selected' => $settings['promo_type'],
			'title'   => 'Custom',
			'desc'    => 'Add your own image & text',
			'form'    => $form_custom,
			);

		$select_form = array(
			'name'    => cpb_get_input_name( $id, true, 'promo_type' ),
			'value'   => 'select',
			'selected' => $settings['promo_type'],
			'title'   => 'Insert',
			'desc'    => 'Insert a specific post/page',
			//'form'    => $cpb_form->get_form_select_post( cpb_get_input_name( $id, true ), $settings ),
			'form'    => $cpb_form->get_insert_posts_field( cpb_get_input_name( $id, true ), $settings, array(), 'Select Content' ),
			);

		$feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'promo_type' ),
			'value'   => 'feed',
			'selected' => $settings['promo_type'],
			'title'   => 'Feed (This Site)',
			'desc'    => 'Load content by category or tag',
			'form'    => $cpb_form->get_form_local_query( cpb_get_input_name( $id, true ), $settings ),
			);

		$remote_feed_form = array(
			'name'    => cpb_get_input_name( $id, true, 'promo_type' ),
			'value'   => 'remote_feed',
			'selected' => $settings['promo_type'],
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

		return array( 'Source' => $html, 'Display' => $display, 'Advanced' => $adv );






        $form = $cpb_form->insert_media( cpb_get_input_name( $id, true ), $settings );

		$form .= '<hr/>';

		$form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'alt' ), $settings['alt'], 'Promo Alt Text' );

		$form .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'url' ), $settings['url'], 'Link Promo To:' );

        return $form;

    } // End get_shortcode_form

} // End Promo

$cpb_shortcode_promo = new Promo_Shortcode();