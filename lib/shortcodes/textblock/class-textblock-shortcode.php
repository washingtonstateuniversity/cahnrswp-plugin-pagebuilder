<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Textblock Shortcode
* @since 3.0.0 
*/
class Textblock {

    protected $prefix = '';

    // @var array $default_settings Array of default settings
    protected $default_settings = array(
        'is_callout'         => '',
        'bgcolor'           => '',
        'textcolor'         => '',
        'csshook'           => '',
        'list_style'        => '',
    );


	public function __construct(){

        \add_action( 'init', array( $this, 'register_shortcode') );

    } // End __construct


    /*
    * @desc Register textblock shortcode
    * @since 3.0.0
    */
    public function register_shortcode(){

        \add_shortcode( 'textblock', array( $this, 'get_rendered_shortcode') );

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

        // Check default settings 
        $settings = \shortcode_atts( $this->default_settings, $atts, 'textblock' );

        $content = do_shortcode( $this->get_more_content( $content , $settings ) );

        //TO DO: Need to work out applying the content filter here

        // Set textblock classes
        $classes = $this->get_textblock_classes( $settings );

        $prefix = $this->prefix;

        \ob_start();

        include  __DIR__ . '/textblock.php';

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
    * @desc Get textblock classes
    * @since 3.0.0
    *
    * @param array $settings Textblock attributes
    *
    * @return string Textblock classes
    */
    private function get_textblock_classes( $settings ){
		
        $class = array();
			
        if ( ! empty( $settings['textcolor'] ) ) $class[] = $settings['textcolor'] . '-text';
        
        if ( ! empty( $settings['is_callout'] ) ) $class[] = 'is-callout';
        
        if ( ! empty( $settings['csshook'] ) ) $class[] = $settings['csshook'];
        
        if ( ! empty( $settings['bgcolor'] ) ) $class[] = $settings['bgcolor'] . '-back';
        
        if ( ! empty( $settings['list_style'] ) ) $class[] = $settings['list_style'];
		
		return implode( ' ', $class );
		
    } // End get_item_class
    
    
    /*
    * @desc Split content by more span
    * @since 3.0.0
    *
    * @param array $settings Textblock attributes
    *
    * @return array Textblock css
    */
    private function get_more_content( $content , $settings ){
		
		if ( strpos( $content , '<span id="more-' ) !== false ){
			
			$content_parts = preg_split( '/<span id="more-.*?"><\/span>/' , $content );
			
			$link = '<div id="' . $this->get_id() . '" class="cpb-more-button"><a href="#"><span>Continue Reading</span></a></div>';
			
			$new_content = '<div class="cpb-more-content">';
			
			$new_content .= '<div class="cpb-more-content-intro">' . $content_parts[0] . '</div>';
			
			$new_content .= '<div class="cpb-more-content-continue">' . $content_parts[1] . '</div>';
			
			$new_content .=  $link . '</div>';
			
			$content = $new_content;
			
		} // end if
		
		return $content;
		
	} // end get_more_content

} // End Textblock

$cpb_shortcode_textblock = new Textblock();