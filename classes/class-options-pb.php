<?php
class Options_PB {

	public $settings;

	public function set_settings() {

		$this->settings = $this->get_settings();

	} // end set_options

	public function add_page() {

		add_submenu_page( 'options-general.php', 'Pagebuilder Settings','Page Layout', 'manage_options', 'pbsettings', array( $this, 'the_page' ) );

	} // end add_page

	public function the_page() {

		 //must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
		  wp_die( __('You do not have sufficient permissions to access this page.') );

		} // end if

		$is_update = ( ! empty( $_POST['is_update'] ) );

		$html .= '<div class="wrap">';

			$html .= '<h2>Pabebuilder Settings</h2>';

			$html .= $this->the_form( $this->get_settings( $is_update ) );

		$html .= '</div>';

		echo $html;

	} // end the_page

	public function the_form( $settings ) {

		$post_types = get_post_types(); 

		// Exclude attachment, revision, and nav_menu_item.
		unset( $post_types['attachment'] );
 		unset( $post_types['revision'] );
 		unset( $post_types['nav_menu_item'] );

		$html = '<form method="post" action="">';

			$html .= '<input type="hidden" value="true" name="is_update" />';

			$html .= '<table class="form-table">';

        		$html .= '<tr valign="top">';

        			$html .= '<th scope="row">Apply Builder To:</th>';

        			$html .= '<td>';

						foreach( $post_types as $pt ) {

							$p_id = 'pb-type-' . rand( 1, 10000000 );

							$html .= '<input id="' . $p_id . '" type="checkbox" name="cpb_post_types[]" ';

							if ( ! empty( $settings['cpb_post_types'] ) && in_array( $pt, $settings['cpb_post_types'] ) ) $html .= 'checked="checked" ';

							$html .= 'value="' . $pt . '" />';

							$html .= '<label for="' . $p_id . '"> ' . ucfirst( $pt ) . '</label><br>';

						} // end foreach

					$html .= '</td>';

        		$html .= '</tr>';
				
				$html .= '<tr valign="top">';

        			$html .= '<th scope="row">Style Options:</th>';

        			$html .= '<td>';

						$html .= '<input id="cpb_global_css" type="checkbox" name="cpb_global_css" ';

						if ( ! empty( $settings['cpb_global_css'] ) ) $html .= 'checked="checked" ';

						$html .= 'value="1" />';

						$html .= '<label for="cpb_global_css">Use CAHNRS Global CSS</label><br>';

					$html .= '</td>';

        $html .= '</tr>';

				// Legacy content update.

				// Posts.

				$posts_array = get_posts( array(
					'posts_per_page' => -1,
					'post_type'      => 'post'
				) );

				$legacy_layout_posts = array();

				foreach ( $posts_array as $post ) {
					$more_tags = '';
					$layout_meta = '';
					$pieces = '';
					$pb_content = '';
					setup_postdata( $post );
					$more_tags = (int) substr_count( $post->post_content, '<!--more-->' );
					$layout_meta = get_post_meta( $post->ID, '_layout', true );
					if ( $more_tags > 1 && $layout_meta ) { // Check for two More tags, as the first is not used for layout.
						$legacy_layout_posts[] = $post->ID;
						if ( isset( $_POST['cpb_legacy_layout_update'] ) ) {

							$pieces = explode( '<!--more-->', $post->post_content );

							switch ( $layout_meta['layout'] ) {
								case '0':
									$layout = 'single';
									break;
								case '1':
									$layout = 'side-right';
									break;
								case '2':
									$layout = 'halves';
									break;
								case '4':
									$layout = 'thirds';
									break;
								case '5':
									$layout = 'quarters';
									break;
								default:
									$layout = 'single';
							}

							// Section and row.
							$pb_content = '[section title="Page Section" fullbleed="0"][row layout="' . $layout . '" padding="pad-ends" gutter="gutter"]';
							// Column One.
							//$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[0] . "\n<!--more-->\n" . $pieces[1] . "\n\n" . '[/textblock][/column]';
							$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[0] . "\n\n" . $pieces[1] . "\n\n" . '[/textblock][/column]';
							// Column Two (we have at least two if we're even in here).
							$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[2] . "\n\n" . '[/textblock][/column]';
							// Column Three.
							if ( $pieces[3] && ( 'thirds' == $layout || 'quarters' == $layout ) ) {
								$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[3] . "\n\n" . '[/textblock][/column]';
							}
							// Column Four.
							if ( $pieces[4] && 'quarters' === $layout ) {
								$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[4] . "\n\n" . '[/textblock][/column]';
							}
							// Close section and row.
							$pb_content .= '[/row][/section]';

							// Update post with short coded content.
							wp_update_post( array(
								'ID'           => $post->ID,
								'post_content' => $pb_content,
							) );

							update_post_meta( $post->ID, '_cpb_excerpt', $pieces[0] );
							// Set pagebuilder as on (not sure if this is right or if we want to do it unless PB is enabled for posts). 
							//add_post_meta( $post->ID, '_cpb_pagebuilder', array( 1 ) );

							// Delete the meta data.
							delete_post_meta( $post->ID, '_layout' );
						}
					}
					
				}
				wp_reset_postdata();

				// Pages.

				$pages_array = get_pages();

				$legacy_layout_pages = array();

				foreach ( $pages_array as $page ) {
					$more_tags = '';
					$layout_meta = '';
					$dynamic_meta = '';
					$pieces = '';
					$pb_content = '';
					$more_tags = (int) substr_count( $page->post_content, '<!--more-->' );
					$layout_meta = get_post_meta( $page->ID, '_layout', true );
					$dynamic_meta = get_post_meta( $page->ID, '_dynamic', true );
					if ( $more_tags > 0 || ( $layout_meta || $dynamic_meta ) ) { // Presumably, any More tag in a page is for layout.
						$legacy_layout_pages[] = $page->ID;
						if ( isset( $_POST['cpb_legacy_layout_update'] ) ) {

							$pieces = explode( '<!--more-->', $page->post_content );

							switch ( $layout_meta['layout'] ) {
								case '0':
									$layout = 'single';
									break;
								case '1':
									$layout = 'side-right';
									break;
								case '2':
									$layout = 'halves';
									break;
								case '4':
									$layout = 'thirds';
									break;
								case '5':
									$layout = 'quarters';
									break;
								default:
									$layout = 'single';
							}

							// Section.
							$pb_content = '[section title="Page Section" fullbleed="0"]';

							// Dynamic pages can be pretty hairy.
							if ( 'dynamic' == $layout_meta['page_type'] && $dynamic_meta ) {

								// Slideshow.
								if ( 'show' == $layout_meta['slideshow'] && $dynamic_meta['wipHomeArray'] ) {
									$pb_content .= '[row layout="single" padding="" gutter=""]';
									$slideshow_content_types = explode( ',', $dynamicMeta['wipHomeArray'] );
									// Maybe just do it for posts (links are really the only other viable content type, though).
									foreach( $slideshow_content_types as $content_type ) {
										if ( 'cTypePosts' == substr( $content_type, 0, 10 ) ) {
											$count = $dynamicMeta[$content_type.'_number'] ? $dynamicMeta[$content_type.'_number'] : 5;
											$category = $dynamicMeta[$content_type.'_category'] ? get_term_by( 'id', $dynamicMeta[$content_type.'_category'], 'category') : '';
											$pb_content .= '[feed feed_type="basic" post_type="post" taxonomy="category" tax_terms="' . $category->name . '" display="list" posts_per_page="' . $count . '"][/feed]';
										}
									}
									$pb_content .= '[/row]';
								}

								// Column one.
								if ( $dynamic_meta['wipMainArray'] ) {
									$pb_content .= '[column verticalbleed="0"][textblock]'. "\n\n";
									$column_one_content_types = explode( ',', $dynamicMeta['wipMainArray'] );
									// Maybe just do page content.
									foreach( $column_one_content_types as $content_type ) {
										if ( 'cTypePage' == substr( $content_type, 0, 10 ) ) {
											//$pb_content .= $pieces[0] . "\n\n";
											$pb_content .= $pieces[substr( $content_type, 11 )] . "\n\n";
										}
									}
									$pb_content .= '[/textblock][/column]';
								}
								
								// Column two.
								if ( $dynamic_meta['wipSecondaryArray'] && ( 'side-right' == $layout || 'halves' == $layout || 'thirds' == $layout || 'quarters' == $layout ) ) {
									$pb_content .= '[column verticalbleed="0"][textblock]'. "\n\n";
									$column_two_content_types = explode( ',', $dynamicMeta['wipSecondaryArray'] );
									foreach( $column_two_content_types as $content_type ) {
										if ( 'cTypePage' == substr( $content_type, 0, 10 ) ) {
											//$pb_content .= $pieces[0] . "\n\n";
											$pb_content .= $pieces[substr( $content_type, 11 )] . "\n\n";
										}
									}
									$pb_content .= '[/textblock][/column]';
								}

								// Column three.
								if ( $dynamic_meta['wipAdditionalArray'] && ( 'thirds' == $layout || 'quarters' == $layout ) ) {
									$pb_content .= '[column verticalbleed="0"][textblock]'. "\n\n";
									$column_three_content_types = explode( ',', $dynamicMeta['wipAdditionalArray'] );
									foreach( $column_three_content_types as $content_type ) {
										if ( 'cTypePage' == substr( $content_type, 0, 10 ) ) {
											//$pb_content .= $pieces[0] . "\n\n";
											$pb_content .= $pieces[substr( $content_type, 11 )] . "\n\n";
										}
									}
									$pb_content .= '[/textblock][/column]';
								}

								// Column four.
								if ( $dynamic_meta['wipFourthArray'] && 'quarters' == $layout ) {
									$pb_content .= '[column verticalbleed="0"][textblock]'. "\n\n";
									$column_four_content_types = explode( ',', $dynamicMeta['wipFourthArray'] );
									foreach( $column_four_content_types as $content_type ) {
										if ( 'cTypePage' == substr( $content_type, 0, 10 ) ) {
											//$pb_content .= $pieces[0] . "\n\n";
											$pb_content .= $pieces[substr( $content_type, 11 )] . "\n\n";
										}
									}
									$pb_content .= '[/textblock][/column]';
								}

							} else { // Non-dynamic pages are easy by comparison.

								// Row.
								$pb_content .= '[row layout="' . $layout . '" padding="pad-ends" gutter="gutter"]';

								// Column one.
								$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[0] . "\n\n" . '[/textblock][/column]';

								// Column two (we have at least two if we're even in here).
								$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[1] . "\n\n" . '[/textblock][/column]';

								// Column three.
								if ( $pieces[2] && ( 'thirds' == $layout || 'quarters' == $layout ) ) {
									$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[2] . "\n\n" . '[/textblock][/column]';
								}

								// Column four.
								if ( $pieces[3] && 'quarters' == $layout ) {
									$pb_content .= '[column verticalbleed="0"][textblock]' . "\n\n" . $pieces[3] . "\n\n" . '[/textblock][/column]';
								}

								// Close row.
								$pb_content .= '[/row]';

							}
							// Clost section.
							$pb_content .= '[/section]';

							// Update post with short coded content.
							//wp_update_post( array(
							//	'ID'           => $page->ID,
							//	'post_content' => $pb_content,
							//) );

							update_post_meta( $page->ID, '_cpb_excerpt', $pieces[0] );
							// Set pagebuilder as on.
							//add_post_meta( $post->ID, '_cpb_pagebuilder', array( 1 ) );

							// Delete the meta.
							//delete_post_meta( $page->ID, '_layout' );
							//delete_post_meta( $page->ID, '_dynamic' );*/
						}
					}
				}

				if ( ( ! empty( $legacy_layout_posts ) && ! empty( $legacy_layout_pages ) ) || isset( $_POST['cpb_legacy_layout_update'] ) ) :

				$html .= '<tr valign="top">';

        	$html .= '<th scope="row">Content Update</th>';

        	$html .= '<td>';

					if ( ! isset( $_POST['cpb_legacy_layout_update'] ) ) {

					$html .= '<input id="cpb_legacy_layout_update" type="checkbox" name="cpb_legacy_layout_update" /> <label for="cpb_legacy_layout_update">Update legacy WSU theme content layouts</label>';

					} else {

						if ( ! empty( $legacy_layout_posts ) ) {
							foreach ( $legacy_layout_posts as $legacy_layout_post ) {
								$html .= 'Post "<strong>' . get_the_title( $legacy_layout_post ) . '</strong>" updated<br />';
							}
						}

						if ( ! empty( $legacy_layout_pages ) ) {
							foreach ( $legacy_layout_pages as $legacy_layout_page ) {
								$html .= 'Page "<strong>' . get_the_title( $legacy_layout_page ) . '</strong>" updated<br />';
							}
						}

					}

					$html .= '</td>';

        $html .= '</tr>';

				endif;

			$html .= '</table>';

			ob_start();

				submit_button();

			$html .= ob_get_clean();

		$html .= '</form>';

		return $html;


	} // end the_form

	public function get_settings( $is_update = false ) {

		$settings = array();

		if ( $is_update ) {

			// Start post type

			$p_types = ( ! empty( $_POST['cpb_post_types'] ) ) ? $_POST['cpb_post_types'] : array();

			array_walk_recursive( $p_types, function( &$item, $key ) { sanitize_text_field( $item ); } );

			$settings['cpb_post_types'] = $p_types;

			$settings['cpb_layout_css'] = ( ! empty( $_POST['cpb_layout_css'] ) ) ? sanitize_text_field( $_POST['cpb_layout_css'] ) : false;
			
			$settings['cpb_global_css'] = ( isset( $_POST['cpb_global_css'] ) ) ? sanitize_text_field( $_POST['cpb_global_css'] ) : 0;

			foreach( $settings as $key => $value ) {

				update_option( $key, $value );

			} // end foreach

		} else {

			$settings['cpb_post_types'] = get_option('cpb_post_types', array('page') );

			$settings['cpb_layout_css'] = get_option('cpb_layout_css', true );
			
			$settings['cpb_global_css'] = get_option('cpb_global_css', 0 );

		}// end if

		return $settings;

	} // end get_settings

	public function remove_editor() {

		// Remove editor
		if ( is_array( $this->settings['cpb_post_types'] ) ) {

			foreach( $this->settings['cpb_post_types'] as $type ) {

				remove_post_type_support( $type, 'editor' );

				remove_post_type_support( $type, 'excerpt' );

			} // end foreach

		} // end if

	} // end remove_editor

}