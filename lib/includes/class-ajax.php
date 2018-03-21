<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle ajax calls
* @since 3.0.0 
*/
class AJAX {

	public function __construct() {

        // Handle AJAX calls
        \add_action( 'wp_ajax_cpb_ajax', array( $this, 'admin_ajax' ) );

    }

    /* 
    * @desc Hanlde editor ajax calls
    * @since 3.0.0
    *
    * @ return mixed Ajax request output
    */
    public function admin_ajax() {

        \define( 'CWPPAGEBUILDER_DOING_AJAX', true );

        $this->check_nounce();

        if ( ! empty( $_POST['service'] ) ) {

			switch( $_POST['service'] ) {

				case 'get_part':
					$this->get_part();
					break;

				case 'get_content':
					$this->request_content();
					break;

                case 'get_style':
                    $this->get_style();
					break;

				case 'search_posts':
					break;
				case 'remote_request':
					break;

			} // end switch

        } // end service

        die();

    } // End admin_ajax


    /*
    * @desc Get part from ajax request
    * @since 3.0.0
    *
    * @return json Part 
    */
    protected function get_part() {

        $json = array();

        if ( ! empty( $_POST['slug'] ) ) {

            $slug = sanitize_text_field( $_POST['slug'] ); 

            // TO DO: Need to sanitize this - use shortcode sanitize_callback
            $settings = ( ! empty( $_POST['settings'] ) ) ? $_POST['settings'] : array();

            $content = ( ! empty( $_POST['content'] ) ) ? wp_kses_post( $_POST['content'] ) : '';

            $get_children = ( isset( $_POST['get_children'] ) ) ? $_POST['get_children'] : true;

            $shortcode = cpb_get_shortcode( $slug, $settings, $content, $get_children );

            if ( ! empty( $shortcode ) ) {

                $json['id'] = $shortcode['id'];

				$json['is_content'] = $shortcode['in_column'];

				$json['editor'] = cpb_get_editor_html( $shortcode );

				$json['forms'] = cpb_get_shortcodes_editor_form_html( $shortcode );

            } // End if

        } // End if

        header('Content-Type: application/json; charset=utf-8', true);

        echo \json_encode( $json );

    } // End get_part


    protected function check_nounce() {

        if ( empty( $_POST['ajax-post-id'] ) ) die();

		$post_id = $_POST['ajax-post-id'];

		\check_ajax_referer( 'cahnrs_pb_ajax_' . $post_id, 'ajax-nonce' );

    }


    protected function request_content() {

        \define( 'CWPPAGEBUILDER_IS_EDITOR', true );

        $shortcodes = array();

        if ( ! empty( $_POST['_cpb']['items'] ) ) {

            foreach( $_POST['_cpb']['items'] as $id => $slug ) {

                if ( ! empty( $_POST['_cpb'][$id]['settings'] ) ) {

                    $settings = $_POST['_cpb'][$id]['settings'];

                } else {

                    $settings = array();

                } // End if

                if ( ! empty( $_POST['_cpb_content_' . $id ] ) ) {

                    $content = wp_kses_post( $_POST['_cpb_content_' . $id ] );

                } else {

                    $content = '';

                }// End if

                $rendered_shortcode = cpb_get_rendered_shortcode( $slug, $settings, $content, true, true );

				if ( $rendered_shortcode ) {

					$shortcodes[ $id ] = $rendered_shortcode;

				} // end if

			} // end foreach

        } // End if

        echo json_encode( $shortcodes );

    } // End request_content


    /*
    * @desc Get CSS to use in editor
    * @since 3.0.0
    *
    * @return string CSS to use in editor
    */
    protected function get_style() {

        include cpb_get_plugin_path( '/lib/css/public.css' );

    } // End get_style

} // End AJAX

$cpb_ajax = new AJAX();