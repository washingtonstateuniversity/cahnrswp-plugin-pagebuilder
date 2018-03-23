<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><li>
	<?php if ( ! empty( $title ) ) : ?>
			<div class="cpb-title">
				<?php if ( ! empty( $link ) ) : ?><a href="<?php echo esc_url( $link ); ?>" ><?php endif; ?>
				<?php echo esc_html( $title ); ?>
				<?php if ( ! empty( $link ) ) : ?></a><?php endif; ?>
			</div>
	<?php endif; ?>
	<?php if ( ! empty( $excerpt ) ) : ?>
			<div class="cpb-excerpt"><?php echo wp_kses_post( strip_shortcodes( wp_strip_all_tags( $excerpt, true ) ) ); ?></div>
	<?php endif; ?>
</li>
