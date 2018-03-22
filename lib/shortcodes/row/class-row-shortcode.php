<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Row Shortcode
* @since 3.0.0
*/
class Row {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'title'             => '',
        'title_tag'         => 'h2',
        'layout'            => 'single',
        'bgcolor'           => '',
        'textcolor'         => '',
        'padding'           => 'pad-bottom',
        'max_width'         => '',
        'gutter'            => 'gutter',
        'csshook'           => '',
        'anchor'            => '',
        'padding_top'       => '',
        'padding_bottom'    => '',
        'padding_left'      => '',
        'padding_right'     => '',
        'full_bleed'        => '',
        'bg_src'            => '',
        'min_height'        => '',
    );


	public function __construct() {

        \add_action( 'init', array( $this, 'register_shortcode' ) );

        \add_filter( 'cpb_get_shortcode', array( $this, 'check_shortcode_columns' ), 10, 2 );

    } // End __construct


    public function check_shortcode_columns( $shortcode, $get_children ) {

        if ( ( 'row' === $shortcode['slug'] ) && $get_children ) {

            $layout = cpb_get_registered_layout( $shortcode['atts']['layout'] );

            $children = $shortcode['children'];

            if ( count( $layout['columns'] ) > count( $children ) ) {

                $dif = count( $layout['columns'] ) - count( $children );

                while( $dif > 0 ) {

                    $shortcode['children'][] = cpb_get_shortcode( 'column' );

                    $dif--;

                } // End while

            } // End if

        } // End if

        return $shortcode;

    } // End check_shortcode_columns


    /*
    * @desc Register row shortcode
    * @since 3.0.0
    */
    public function register_shortcode() {

        \add_shortcode( 'row', array( $this, 'get_rendered_shortcode' ) );

        cpb_register_shortcode(
            'row',
            $args = array(
                'form_callback'         => array( $this, 'get_shortcode_form' ),
                'sanitize_callback'     => array( $this, 'get_sanitize_shortcode_atts' ),
                'editor_callback'       => array( $this, 'get_shortcode_editor' ), // Callback to render form
                'allowed_children'      => array( 'column' ), // Allowed child shortcodes
                'default_shortcode'     => 'column', // Default to this if no children
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

        $prefix = '';

        // Column index global - This is used in the column shortcode to get column number.
        global $cpb_column_i;

        // Row layout global
		global $cpb_row_layout;

        // Resetting column index to 1 since this is a new row
        $cpb_column_i = 1;

        // Check default settings
        $settings = \shortcode_atts( $this->default_settings, $atts, 'row' );

        // Set global layout
        $cpb_row_layout = $settings['layout'];

        // Set row classes
        $classes = $this->get_row_classes( $settings );

        // Get the style array
        $style_array = $this->get_row_style( $settings );

        // Implode the array to string
        $style = implode( ';', $style_array );

        $prefix = $this->prefix;

        \ob_start();

        include  __DIR__ . '/row.php';

        $html = \ob_get_clean();

        return $html;

    } // End get_rendered_shortcode


    /*
    * @desc Get HTML for shortcode form
    * @since 3.0.0
    *
    * @param string $id Shortcode Id
    * @param array $settings Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode form output
    */
    public function get_shortcode_form( $id, $settings, $content ) {

        $cpb_form = cpb_get_form_class();

        $p_values = array( 'default' => 'Not Set' );

		$p = 0;

		while( $p < 5 ) {

			$p_values[ $p . 'rem' ] = $p . 'rem';

			$p = $p + 0.25;

		} // end for

        $basic = '<input type="hidden" name="' . cpb_get_input_name( $id, true, 'layout' ) . '" value="' . $settings['layout'] . '" >';

		$basic .= $cpb_form->hidden_field( cpb_get_input_name( $id, true, 'layout' ), $settings['layout'] );

		$basic .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'title' ), $settings['title'], 'Title' );

		$basic .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'title_tag' ), $settings['title_tag'], $cpb_form->get_header_tags(), 'Title Tag' );

		$basic .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'bgcolor' ), $settings['bgcolor'], $cpb_form->get_wsu_colors(), 'Background Color' );

		$basic .= $cpb_form->checkbox_field( cpb_get_input_name( $id, true, 'full_bleed' ), 1, $settings['full_bleed'], 'Background Full Bleed Color' );

		$basic .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'textcolor' ), $settings['textcolor'], $cpb_form->get_wsu_colors(), 'Text Color' );

		$layout = $cpb_form->select_field( cpb_get_input_name( $id, true, 'padding_top' ), $settings['padding_top'], $p_values, 'Padding Top' );

		$layout .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'padding_bottom' ), $settings['padding_bottom'], $p_values, 'Padding Bottom' );

		$layout .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'padding_left' ), $settings['padding_left'], $p_values, 'Padding Left' );

		$layout .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'padding_right' ), $settings['padding_right'], $p_values, 'Padding Right' );

		$layout .= $cpb_form->select_field( cpb_get_input_name( $id, true, 'padding' ), $settings['padding'], $cpb_form->get_padding(), 'Padding (Old)' );

		$layout .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'max_width' ), $settings['max_width'], 'Max Width' );

		$adv = $cpb_form->select_field( cpb_get_input_name( $id, true, 'gutter' ), $settings['gutter'], $cpb_form->get_gutters(), 'Gutter' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'bg_src' ), $settings['bg_src'], 'Background Image URL' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'min_height' ), $settings['min_height'], 'Minimum Height (px)' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'anchor' ), $settings['anchor'], 'Anchor Name' );

		$adv .= $cpb_form->text_field( cpb_get_input_name( $id, true, 'csshook' ), $settings['csshook'], 'CSS Hook' );

		return array( 'Basic' => $basic, 'Layout' => $layout, 'Advanced' => $adv );;

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

        // Resetting column index to 1 since this is a new row
        $cpb_column_i = 1;

        // Set layout for the row
        $layout = $atts['layout'];

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
        include cpb_get_plugin_path( '/lib/displays/editor/row-editor.php' );

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
    public function get_sanitize_shortcode_atts( $settings ) {

        $clean = array();

        $text_fields = array(
            'title',
            'title_tag',
            'layout',
            'bgcolor',
            'textcolor',
            'padding',
            'max_width',
            'gutter',
            'csshook',
            'anchor',
            'padding_top',
            'padding_bottom',
            'padding_left',
            'padding_right',
            'full_bleed',
            'bg_src',
            'min_height',
        );

        foreach ( $text_fields as $index => $field ) {

            if ( isset( $settings[ $field ] ) ) {

                $clean[ $field ] = sanitize_text_field( $settings[ $field ] );

            } // End if

        } // End foreach

        return $clean;

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
    * @desc Get row classes
    * @since 3.0.0
    *
    * @param array $settings Row attributes
    *
    * @return string Row classes
    */
    private function get_row_classes( $settings ) {

		$class = '';

		if ( ! empty( $settings['bgcolor'] ) ) {

			$class .= ' ' . $settings['bgcolor'] . '-back';

			$class .= ' has-background-color';

		} // end if

		if ( ! empty( $settings['textcolor'] ) ) {

			$class .= ' ' . $settings['textcolor'] . '-text';

		} // end if

		if ( ! empty( $settings['padding'] ) ) {

			$class .= ' ' . $settings['padding'];

		} // end if

		if ( ! empty( $settings['gutter'] ) ) {

			$class .= ' ' . $settings['gutter'];

		} // end if

		if ( ! empty( $settings['csshook'] ) ) {

			$class .= ' ' . $settings['csshook'];

		} // end if

		if ( ! empty( $settings['layout'] ) ) {

			$class .= ' ' . $settings['layout'];

        } // end if

        if ( ! empty( $settings['bg_src'] ) ) {

			$classes .= ' has-bg-image';

        } // end if

		if ( ! empty( $settings['full_bleed'] ) ) {

			if ( ! empty( $settings['bg_src'] ) ) {

				$class .= ' full-bleed-img';

			} else {

				$class .= ' full-bleed';

			} // end if

		} // end if

		return $class;

    } // End get_item_class

    /*
    * @desc Get row style
    * @since 3.0.0
    *
    * @param array $settings Row attributes
    *
    * @return array Row css
    */
    protected function get_row_style( $settings ) {

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

} // End Row

$cpb_shortcode_row = new Row();