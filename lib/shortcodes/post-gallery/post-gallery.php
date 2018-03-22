<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div class="<?php echo esc_html( $classes ); ?>">
	<div class="cpb-gallery-inner">
	    <?php if ( ! empty( $img_src ) ) :?>
			<div class="cpb-image" style="background-image:url(<?php echo esc_url( $img_src ); ?> );background-position:center center;background-size:cover;">
			</div>
        <?php endif; ?>
		<div class="cpb-caption">
			<?php if ( ! empty( $title ) ) : ?>
					<<?php echo esc_html( $tag ); ?> class="cpb-title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>
            <?php endif; ?>
            <?php if ( ! empty( $excerpt ) ) : ?>
						<div class="cpb-excerpt"><?php echo strip_shortcodes( wp_strip_all_tags( $excerpt, true ) ); ?></div>
            <?php endif; ?>
		</div>
	</div>
	<div class="item-link">
        <?php if ( ! empty( $link ) ): ?><a href="<?php echo esc_url( $link ); ?>" ><?php endif; ?>
				 <?php echo esc_html( $title ); ?>
        <?php if ( ! empty( $link ) ): ?></a><?php endif; ?>
    </div>
</div>