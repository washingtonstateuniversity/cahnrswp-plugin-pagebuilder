<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><a href="<?php echo esc_url( $link ); ?>" class="<?php echo esc_html( $classes ); ?>">
	<span class="link-title"><?php echo esc_html( $label ); ?></span>
	<?php if ( ! empty( $caption ) ) : ?>
		<span class="link-caption"><?php echo wp_kses_post( $caption ); ?></span>	
		<?php endif; ?>
</a>
