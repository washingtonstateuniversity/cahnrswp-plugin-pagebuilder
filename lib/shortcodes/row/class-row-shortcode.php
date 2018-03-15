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


	public function __construct(){

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register row shortcode
    * @since 3.0.0
    */
    public function register_shortcode(){

        \add_shortcode( 'row', array( $this, 'get_rendered_shortcode') );

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
    public function get_rendered_shortcode( $atts, $content ){

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
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    *
    * @return string HTML shortcode form output
    */
    public function get_shortcode_form( $atts, $content ){


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
    public function get_sanitize_shortcode_atts( $atts ){

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
    public function get_to_shortcode( $atts, $content ){

    } // End


    /*
    * @desc Get row classes
    * @since 3.0.0
    *
    * @param array $settings Row attributes
    *
    * @return string Row classes
    */
    private function get_row_classes( $settings ){
		
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
			
			if ( ! empty( $settings['bg_src'] ) ){
				
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
    protected function get_row_style( $settings ){
		
		$style = array();
		
		$valid = array(
			'padding_top' => 'padding-top',
			'padding_bottom' => 'padding-bottom',
			'padding_left' => 'padding-left',
			'padding_right' => 'padding-right',
			'max_width'		=> 'max-width',
		);
		
		foreach( $settings as $key => $value ){

			if ( array_key_exists( $key, $valid ) && $value != 'default' && $value !== '' ){
				
				$css = $valid[ $key ];
				
				$style[] = $css . ':' . $value; 
				
			} // end if
			
		} // end foreach
		
		return $style;
		
	} // end get_item_style

} // End Row

$cpb_shortcode_row = new Row();