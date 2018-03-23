<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><div class="cpb-item-tabs-set cpb-item-tabs-columns tabs-columns-<?php echo esc_html( count( $tabs ) ); ?>" style="<?php if ( ! empty( $settings['min_height'] ) ) { echo esc_html( 'height:' . $settings['min_height'] ); } ?>">
<?php foreach ( $tabs as $key => $tab ) : ?>
	<div class="cpb-item-tab-title cpb-item-tab-column <?php echo esc_html( $tab['bgcolor'] ); ?>-back <?php echo esc_html( $settings['textcolor'] ); ?>-text">
		<?php if ( ! empty( $tab['bgimage'] ) ) : ?><div class="cpb-item-tab-bgimage" style="background-image: url(<?php echo esc_url( $tab['bgimage'] ); ?> )"></div><?php endif; ?>
		<div class="cpb-item-tab-inner-content"><<?php echo esc_html( $tab['tag'] ); ?> class="<?php echo esc_html( $settings['textcolor'] ); ?>-text"><?php echo esc_html( $tab['title'] ); ?></<?php echo esc_html( $tab['tag'] ); ?>></div>
	</div>
	<div class="cpb-item-tab-content cpb-item-tab-column"><div class="cpb-item-tab-inner-content">
	<?php if ( ! empty( $tab['posts'] ) ) {

		$post_ids = explode( ',', $tab['posts'] );

		foreach ( $post_ids as $post_id ) {

			$post = get_post( $post_id );

			// @codingStandardsIgnoreStart
			echo do_shortcode( $post->post_content );
			// @codingStandardsIgnoreEnd

		} // end foreach
	}; ?>
	</div></div>
<?php endforeach; ?>
</div>
