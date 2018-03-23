<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><div class="<?php echo esc_html( $class ); ?>" data-requesturl="<?php echo esc_url( $request_url ); ?>">
	<?php if ( ! empty( $img_src ) ) : ?>
	<div class="cpb-image">
		<img style="background-image:url(<?php echo esc_url( $img_src ); ?>);background-position:center center;background-size:cover;" src="<?php echo esc_url( cpb_get_plugin_url( 'lib/images/' . $img_ratio . '.gif' ) ); ?>" alt="<?php echo esc_html( $img_alt ); ?>" />
	</div>
	<?php endif; ?>
	<div class="cpb-promo-caption">
	<?php if ( ! empty( $title ) ) : ?>
		<div class="cpb-title-wrapper"><<?php echo esc_html( $tag ); ?> class="cpb-title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>></div>
	<?php endif; ?>
	<?php if ( ! empty( $subtitle ) ) : ?>
			<div class="cpb-subtitle-wrapper"><div class="cpb-subtitle"><?php echo esc_html( $subtitle ); ?></div></div>
	<?php endif; ?>
	<?php if ( ! empty( $excerpt ) ) : ?>
			<div class="cpb-copy-wrapper"><div class="cpb-copy"><?php echo esc_html( $excerpt ); ?></div></div>
	<?php endif; ?>
	<?php if ( ! empty( $link ) ) : ?>
		<div class="cpb-promo-link"><a href="<?php echo esc_url( $link ); ?>" >Visit <?php echo esc_html( $title ); ?></a></div>
	<?php endif; ?>
	</div>
</div>
