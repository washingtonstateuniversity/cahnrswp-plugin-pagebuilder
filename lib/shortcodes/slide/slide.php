<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><li class="slide gallery-slide<?php echo esc_html( $active ); ?>">
	<?php echo wp_kses_post( $img ); ?>
	<?php echo esc_url( $link ); ?>
	<ul class="cpb-slide-caption">
		<li class="cpb-slide-title"><?php echo esc_html( $title ); ?></li>
		<li class="cpb-slide-excerpt"><?php echo wp_kses_post( $excerpt ); ?></li>
		<li class="cpb-slide-link"><?php echo esc_url( $link ); ?></li>
	</ul>
</li>
