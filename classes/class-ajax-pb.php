<?php
class AJAX_PB {

	public $items_factory;

	public function __construct( $items_factory ) {

		$this->items_factory  = $items_factory ;

	} // end __construct

	public function do_request() {

		$response = false;

		switch( $_POST['service'] ) {

			case 'add_part':
				$response = $this->add_part();
				break;

		} // end switch

		if ( $response ) {

			echo $response;

		} // end if

	} // end do_request

	public function add_part() {

		$response = array();

		if ( ! empty( $_POST['item_slug'] ) ) {

			$part = sanitize_text_field( $_POST['item_slug'] );

			// Settings are cleaned by the item
			$settings = ( ! empty( $_POST['settings'] ) ) ? $_POST['settings'] : array();

			$content = ( ! empty( $_POST['content'] ) ) ? wp_kses_post( $_POST['content'] ) : ' ';

			// Handle if widget
			if ( strpos( $part, 'cpbwidget_' ) !== false ) {

				$settings['widget_type'] = str_replace( 'cpbwidget_', '', $part );

				$part = 'widget';

			} // end if

			$item = $this->items_factory->get_item( $part, $content, $settings, true );

			if ( $item ) {

				$response['id'] = $item->id;

				$response['editor'] = $this->items_factory->get_editor_item( $item );

				$form_items = $this->items_factory->flatten_array( array( $item ) );

				foreach( $form_items as $f_item ) {

					$type = $f_item->slug;

					$form = Forms_PB::wrap_item_form( $f_item->id, $f_item->the_form(), $f_item->form_size );

					$response['forms'][] = array( 'type' => $type, 'form' => $form, 'id' => $f_item->id );

				} // end foreach

			} // end if

			return json_encode( $response );

		} // end if

	} // end add_part

}