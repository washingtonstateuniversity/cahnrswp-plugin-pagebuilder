<?php namespace CAHNRSWP\Plugin\Pagebuilder;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // End if

/*
* @desc Class to handle Customizer
* @since 3.0.0
*/
class Customizer {


	public function __construct() {

		\add_action( 'customize_register', array( $this, 'add_customizer_options' ) );

	} // End


	public function add_customizer_options( $wp_customize ) {

		$post_types = $this->get_wp_post_types();

		foreach ( $post_types as $type => $label ) {

			$default = 0;

			if ( 'page' === $type ) {

				$default = 1;

			} // End if

			$wp_customize->add_setting(
				'cpb_enable_post_type_' . $type,
				array(
					'default'   => $default,
					'transport' => 'refresh',
				)
			);

		} // End forach

			$wp_customize->add_section(
				'cpb_options',
				array(
					'title'      => 'CAHNRS PageBuilder Options',
					'priority'   => 30,
				)
			);

		foreach ( $post_types as $type => $label ) {

			$wp_customize->add_control(
				'cpb_enable_post_type_' . $type . '_control',
				array(
					'label'    => 'Add PageBuilder to ' . $label,
					'section'  => 'cpb_options',
					'settings' => 'cpb_enable_post_type_' . $type,
					'type'     => 'checkbox',
				)
			);

		} // End forach

	} // End add_customizer_options


	protected function get_wp_post_types() {

		$post_type_array = array();

		$args = array(
			'public' => true,
		);

		$post_types = \get_post_types( $args, 'objects' );

		foreach ( $post_types  as $post_type ) {

			$post_type_array[ $post_type->name ] = $post_type->label;

		} // End foreach

		return $post_type_array;

	} // End get_wp_post_types

} // End Scripts

$cpb_customizer = new Customizer();
