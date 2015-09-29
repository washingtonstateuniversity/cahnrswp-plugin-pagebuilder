<?php
class Item_Feed_PB extends Item_PB {

	public $slug = 'feed';

	public $name = 'Content Feed';

	public $desc = 'Dynamically Feed Content';

	public $form_size = 'medium';
	
	public function item( $settings , $content ){
		
		require_once CWPPBDIR . 'classes/class-query-pb.php';
		
		require_once CWPPBDIR . 'classes/class-display-pb.php';
		
		$query = new Query_PB( $settings );
		
		$display = new Display_PB( $settings );
		
		$items = $query->get_query_items();
		
		$html = $display->get_display( $items );
		
		/*$args = Query_PB::get_local_query_args( $settings );
		
		$feed = Query_PB::get_local_feed_objs( $args );
		
		$html = Display_PB::get_display( $feed , $settings );*/
		
		return $html;

	} // end item

	public function editor( $settings, $editor_content ) {

		$title = ( $settings['title'] ) ? $settings['title'] : 'Add Subtitle';

		$html = '<h2>' . $title . '</h2>';

		return $html;

	} // end editor

	public function form( $settings ) {

		$source_sub_form = array(
			'Basic Feed' => array(
				'form'  => $this->get_source_form( $settings ),
				'value' => 'basic',
			),
		);
		
		$display_form = array(
			'Promo' => array(
				'form' => $this->get_promo_form( $settings ),
				'value' => 'promo'
			)
		);
		
		
		$forms = array(
			'Source' => Forms_PB::get_sub_form( $source_sub_form , $this->get_name_field('feed_type') , $settings['feed_type'] ),
			//'Display Style' => $this->get_display_form( $settings ),
			'Display Style' => Forms_PB::get_sub_form( $display_form , $this->get_name_field('display_type') , $settings['display_type'] )

		);

		return $forms;

	} // end form

	
	private function get_promo_form( $settings ){
		
		$html = Forms_PB::text_field( $this->get_name_field('excerpt_length') , $settings['excerpt_length'] , 'Excerpt Length (# Words)' );
		
		return $html;
		
	}
	
	
	private function get_source_form( $settings ){
		
		$p_types = get_post_types();

		$post_types = array( 'any' => 'Any' );

		foreach( $p_types as $type ) {

			$post_types[ $type ] = ucfirst( $type );

		} // end foreach

		$taxonomies = array(
			'post_tag' => 'Tags',
			'category' => 'Categories',
		);

		$html = Forms_PB::select_field( $this->get_name_field('post_type'), $settings['post_type'], $post_types, 'Content Type' );

		$html .= Forms_PB::select_field( $this->get_name_field('taxonomy'), $settings['taxonomy'], $taxonomies, 'Type' );

		$html .= Forms_PB::text_field( $this->get_name_field('tax_terms'), $settings['tax_terms'], 'Terms' );

		$html .= Forms_PB::text_field( $this->get_name_field('posts_per_page'), $settings['posts_per_page'], 'Count' );

		return $html;

	} // end get_source_form

	private function get_display_form( $settings ) {

		$html = '';

		return $html;

	} // end get_display_style

	public function clean( $s ) {

		$clean = array();

		$clean['feed_type'] = ( ! empty( $s['basic'] ) ) ? sanitize_text_field( $s['feed_type'] ) : 'basic';

		if ( 'basic' == $clean['feed_type'] ) {

			$clean['post_type'] = ( ! empty( $s['post_type'] ) ) ? sanitize_text_field( $s['post_type'] ) : 'post';

			$clean['taxonomy'] = ( ! empty( $s['taxonomy'] ) ) ? sanitize_text_field( $s['taxonomy'] ) : false;

			$clean['tax_terms'] = ( ! empty( $s['tax_terms'] ) ) ? sanitize_text_field( $s['tax_terms'] ) : false;

		} // end if
		
		if ( ! empty( $s['posts_per_page'] ) ) $clean['posts_per_page'] = sanitize_text_field( $s['posts_per_page'] );
		
		$clean['display_type'] = ( ! empty( $s['display_type'] ) )? sanitize_text_field( $s['display_type'] ) : 'promo';
		
		$clean['excerpt_length'] = ( ! empty( $s['excerpt_length'] ) )? sanitize_text_field( $s['excerpt_length'] ) : '';
		

		return $clean;

	} // end clean

}