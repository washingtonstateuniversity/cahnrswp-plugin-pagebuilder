<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Slideshow Shortcode
* @since 3.0.0 
*/
class Slideshow_Shortcode {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'title'         => '',
        'display_type'   => 'gallery-slideshow',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode' ) );

    } // End __construct


    /*
    * @desc Register slideshow shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'slideshow', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode( 
            'slideshow', 
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'editor_callback'       => array( $this, 'get_shortcode_editor' ), // Callback to render form
                'allowed_children'      => array( 'slide' ), // Allowed child shortcodes
                'default_shortcode'     => 'slide', // Default to this if no children
                'label'                 => 'Slideshow', // Label of the item
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
        $atts = \shortcode_atts( $this->default_settings, $atts, 'slideshow' );

        global $cpb_slideshow;

		$cpb_slideshow = array(
			'type' => $atts['display_type'],
			'i'    => 1,
		);

		$slides = do_shortcode( $content );

		\ob_start();

		include  __DIR__ . '/slideshow.php';

	    $html = \ob_get_clean();

		return $html;

    } // End get_rendered_shortcode


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

        $displays = array(
			'default' => 'Default',
			'college' => 'College'
		);

		$html = $cpb_form->text_field( cpb_get_input_name( $id, true, 'title' ), $settings['title'], 'Title' ); 

		$html .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'display_type' ), $settings['display_type'], $displays, 'Display Type' );

		return array( 'Basic' => $html );

    } // End get_shortcode_form


    /*
    * @desc Get HTML for shortcode editor
    * @since 3.0.0
    *
    * @param string $id Shortcode id
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @param string $children Shortcode children
    *
    * @return string HTML shortcode form output
    */
    public function get_shortcode_editor( $id, $atts, $content, $children ) {

        $slug = 'slideshow';

        // Get the editor content
        $editor_content = cpb_get_editor_html( $children );

        // Get the input name
        $input_name = cpb_get_input_name( $id );

        // Get the child keys
        $child_keys = cpb_get_child_shortcode_ids( $children );

        // implode the child keys
        $child_keys = \implode( ',', $child_keys );

        // Get the edit button
        $edit_button = cpb_get_editor_edit_button();

        // Get the remove button
        $remove_button = cpb_get_editor_remove_button();

        // Start output buffer
        \ob_start();

        // Include the html
        include  __DIR__ . '/slideshow-editor.php';

        // Get the html
        $html = \ob_get_clean();

        return $html; 

    } // End get_shortcode_form

} // End Slideshow

$cpb_shortcode_slideshow = new Slideshow_Shortcode();