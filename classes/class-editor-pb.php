<?php

require_once CWPPBDIR. 'forms/form-builder-options-pb.php';

require_once CWPPBDIR. 'forms/form-excerpt-pb.php';

class Editor_PB {

	public $items;

	public function __construct( $items ) {

		$this->items = $items;

	} // end __construct

	public function the_editor( $post ) {

		$settings = $this->get_editor_settings( $post );
		
		$opts_form = new Form_Builder_Options_PB( $settings );
		
		$excerpt_form = new Form_Excerpt_PB( $settings , $post );
		
		if ( '' == $post->post_content ) $post->post_content = '[row layout="side-right"][/row]';

		// Get layout: array of item objects with children set
		$items = $this->items->get_items_from_content( $post->post_content, 'section', 'section', true  );

		$html = '<div id="cwp-pb">';

			$html .= $opts_form->get_form();

			if ( $settings['_cpb_pagebuilder'] ) {

				$html .= $this->get_layout_editor( $items, $post );

				$html .= $this->get_layout_forms( $items, $post );

				$html .= $excerpt_form->get_form();

			} else {

				$html .= $this->get_content_editor( $post, $settings );

			} // end if

		$html .= '</div>';

		echo $html;

	} // end the_editor


	public function get_content_editor( $post, $settings ) {

		$html = '<div id="cwp-pb-content">';

			ob_start();

			wp_editor( $post->post_content, 'content' );

			$html .= ob_get_clean();

			$html .= '</div>';

		return $html;

	} // end get_content_editor

	public function get_editor_settings( $post ) {

		$st = array();

		$meta = get_post_meta( $post->ID );

		$st['_cpb_m_excerpt'] = ( isset( $meta['_cpb_m_excerpt'][0] ) ) ? $meta['_cpb_m_excerpt'][0] : 'auto';

		$st['_cpb_excerpt'] = ( isset( $meta['_cpb_excerpt'][0] ) ) ? $meta['_cpb_excerpt'][0] : '';

		$st['_cpb_pagebuilder'] = ( isset( $meta['_cpb_pagebuilder'][0] ) ) ? $meta['_cpb_pagebuilder'][0] : 0;

		return $st;

	} // end get_editor_settings

	public function get_excerpt_form( $post, $settings ) {

		$excerpt = ( $st['_cpb_m_excerpt'] == 'manual' ) ? $st['_cpb_excerpt'] : $post->post_excerpt;

		$html = '<div class="cpb-editor-excerpt">';

			$html .= '<h3 class="cwp-inline cwp-vabottom">Summary/Excerpt</h3>';

			$html .= Forms_PB::radio_field( '_cpb_m_excerpt',  'auto', $settings['_cpb_m_excerpt'], 'Auto', 'inline valign-bottom first cwp-radio-toggle');

			$html .= Forms_PB::radio_field( '_cpb_m_excerpt',  'manual', $settings['_cpb_m_excerpt'], 'Custom', 'inline valign-bottom cwp-radio-toggle' );

			$html .= '<textarea name="_cpb_excerpt">' . $excerpt . '</textarea>';

		$html .= '</div>';

		return $html;

	} // end get_excerpt_form

	public function get_layout_editor( $items, $post ) {

		$children = array();

		foreach( $items as $child ) {

			$children[] = $child->id;

		} // end foreach

		$html = '<div id="cwp-pb-editor">';

			$html .= $this->items->get_editor_items( $items );

			$html .= '<input type="hidden" name="_cpb[layout]" value="' . implode( ',', $children ) . '" />';

		$html .= '</div>';

		return $html;

	} // end get_editor

	public function get_layout_forms( $items, $post ) {

		$form_items = $this->items->flatten_array( $items );


		$html = '<div id="cwp-pb-forms">';

		foreach( $form_items as $item ) {

			$html .= Forms_PB::wrap_item_form( $item->id, $item->the_form(), $item->form_size  );

		} // end foreach

		$html .= $this->get_add_forms();

		$html .= $this->get_extra_editors();

		$html .= '</div>';

		return $html;

	} // end get_editor

	public function get_add_forms() {
		
		$form_add_items = new Form_Add_Item_PB( $this->items->get_all_items() );

		$item_form = $this->add_item_form( $this->items->get_all_items() );

		$row_form = $this->add_row_form( array('single' => array( 'label' => 'Single Column' ) ) );

		//$html = Forms_PB::wrap_item_form( 'cpb_add_item', $item_form, 'large'  );

		$html .= Forms_PB::wrap_item_form( 'cpb_add_row', $row_form, 'large'  );
		
		$html .= $form_add_items->get_form();
		
		//$html .= Forms_PB::wrap_item_form( 'cpb_add_item', $form_items->get_form(), 'large'  );

		return $html;

	} // end get_add_item_form

	public function get_extra_editors() {

		$html = '';

		$textblock = $this->items->get_item( 'textblock' );

		for ( $i = 0; $i < 12; $i++ ) {

			$textblock->id = 'cpb_extra_editor_' . $i;

			$html .= Forms_PB::wrap_item_form( $textblock->id, $textblock->the_form(), $textblock->form_size, 'cpb-extra-editor' );

		} // end for

		return $html;

	}

	public function add_item_form( $items ) {

		$structure_array = array('section','row','column','pagebreak');

		$form = array();

		$c_items = '';

		foreach( $items as $item ) {

			if ( ! in_array( $item->slug, $structure_array ) ) {

				$desc = ( isset( $item->desc ) ) ? '<span>' . $item->desc . '</span>' : '';

				$c_items .= Forms_PB::radio_field( 'item_slug',  $item->slug, '', $item->name . $desc, 'cpb-item-icon cpb-' . $item->slug );

			} // end if

		} // end foreach

		$form['Content Items'] = $c_items;

		if ( ! empty( $GLOBALS['wp_widget_factory']->widgets ) ) {

			$w_items = '';

			$widgets = array_keys( $GLOBALS['wp_widget_factory']->widgets );

			foreach( $widgets as $widget ) {

				if ( class_exists( $widget ) ) {

					$wid = new $widget();

					$w_items .= Forms_PB::radio_field( 'item_slug',  'cpbwidget_' . $widget, '', $wid->name, 'cpb-item-icon cpb-' . $widget );

				} // end if

			} // end foreach

			$form['Widgets'] = $w_items;

		} // end if

		return Forms_PB::get_item_form( $form, 'ajax-part-action', 'Insert Item' );

	} // end add_item_form

	public function add_row_form() {

		$layouts = array(
			'single'            => 'Single Column',
			'halves'            => 'Two Column',
			'side-right'        => 'Two Column: Sidebar Right',
			'side-left'         => 'Two Column: Sidbar Left',
			'thirds'            => 'Three Column',
			'thirds-half-left'  => 'Three Column: Left 50%',
			'thirds-half-right' => 'Three Column: Right 50% ',
			'triptych'          => 'Three Column: Middle 50%',
			'quarters'          => 'Four Column',
		);

		$html = '<input type="hidden" name="item_slug" value="row" />';

		foreach( $layouts as $layout_name => $layout_label ) {

			$html .= Forms_PB::radio_field( 'settings[layout]',  $layout_name, '', $layout_label, 'cpb-layout cpb-' . $layout_name );

		} // end foreach

		return Forms_PB::get_item_form( array( 'Row Settings' => $html ), 'ajax-part-action', 'Insert Row' );

	} // end add_item_form
}