<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Column Shortcode
* @since 3.0.0 
*/
class Column {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'index'             => '',
        'bgcolor'           => '',
        'textcolor'         => '',
        'csshook'           => '',
        'padding_top'       => '',
        'padding_bottom'    => '',
        'padding_left'      => '',
        'padding_right'     => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register column shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'column', array( $this, 'get_rendered_shortcode') );

        cpb_register_shortcode( 
            'column', 
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'sanitize_callback'     => array( $this, 'get_sanitize_shortcode_atts' ),
                'editor_callback'       => array( $this, 'get_shortcode_editor' ), // Callback to render form
                'allowed_children'      => 'in_column', // Allowed child shortcodes,
                'default_shortcode'     => 'textblock', // Default to this if no children
                'default_atts'          => $this->default_settings,
                'in_column'             => false, // Allow in column
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

        // Column index global - This is used in the column shortcode to get column number.
        global $cpb_column_i;

        $atts['index'] = $cpb_column_i;

        // Check default settings 
        $settings = \shortcode_atts( $this->default_settings, $atts, 'column' );

        // Set column classes
        $classes = $this->get_column_classes( $settings );

        $cpb_column_i++;

        // Column index global - This is used in the column shortcode to get column number.
        global $cpb_column_i;

        // Column layout global
		global $cpb_Column_layout;  

        // Get the style array
        $style_array = $this->get_column_style( $settings );

        // Implode the array to string
        $style = implode( ';', $style_array );

        $prefix = $this->prefix;

        \ob_start();

        include  __DIR__ . '/column.php';

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
    public function get_shortcode_form( $atts, $content ) {


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

        // Column index global - This is used in the column shortcode to get column number.
        global $cpb_column_i;

        // Set index int value
        $index_int = $cpb_column_i;

        // Set index text value
        $index = cpb_convert_int_to_string( $cpb_column_i );

        // increment column global var
        $cpb_column_i++;

        // Get editor content html
        $editor_content = cpb_get_editor_html( $children );

        // Get input name
        $input_name = cpb_get_input_name( $id );

        // Get child kes as array
        $child_keys = cpb_get_child_shortcode_ids( $children );

        // Implode array
        $child_keys = \implode(',', $child_keys );

        // Get the edit button
        $edit_button = cpb_get_editor_edit_button();

        // Get the remove button
        $remove_button = cpb_get_editor_remove_button();

        // Start the output buffer
        \ob_start();

        // Include the html for the column editor
        include cpb_get_plugin_path('/lib/displays/editor/column-editor.php');

        // Get the html
        $html = \ob_get_clean();

        return $html; 

    } // End get_shortcode_form


    /*
    * @desc Get stanitized output for $atts
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return array Sanitized shortcode $atts
    */
    public function get_sanitize_shortcode_atts( $atts ) {

    } // End sanitize_shortcode


    /*
    * @desc Get shortcode for use in save
    * @since 3.0.0
    *
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string Shortcode for saving in content
    */
    public function get_to_shortcode( $atts, $content ) {

    } // End


    /*
    * @desc Get column classes
    * @since 3.0.0
    *
    * @param array $settings Column attributes
    *
    * @return string Column classes
    */
    private function get_column_classes( $settings ) {

        $class = 'column ';

        $index_list = 'zero,one,two,three,four,five,six,seven,eight,nine,ten';

        $index_list = explode( ',', $index_list );

        $class .= ' ' . $index_list[ $settings['index'] ]; 

		if ( ! empty( $settings['bgcolor'] ) ) {

			$class .= ' ' . $settings['bgcolor'] . '-back bg-color';

		} // end if

		if ( ! empty( $settings['csshook'] ) ) {

			$class .= ' ' . $settings['csshook'];

		} // end if

		if ( ! empty( $settings['textcolor'] ) ) {

			$class .= ' ' . $settings['textcolor'] . '-text';

		} // end if

		return $class;

    } // End get_item_class

    /*
    * @desc Get column style
    * @since 3.0.0
    *
    * @param array $settings Column attributes
    *
    * @return array Column css
    */
    protected function get_column_style( $settings ) {

		$style = array();

		$valid = array(
			'padding_top' => 'padding-top',
			'padding_bottom' => 'padding-bottom',
			'padding_left' => 'padding-left',
			'padding_right' => 'padding-right',
			'max_width'		=> 'max-width',
		);

		foreach ( $settings as $key => $value ) {

			if ( array_key_exists( $key, $valid ) && $value != 'default' && $value !== '' ) {

				$css = $valid[ $key ];

				$style[] = $css . ':' . $value; 

			} // end if

		} // end foreach

		return $style;

	} // end get_item_style

} // End Column

$cpb_shortcode_column = new Column();