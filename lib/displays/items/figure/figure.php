<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<figure class="cpb-figure">
    <img src="<?php echo esc_url( $img_src ); ?>" style="width: 100%;display:block" />
    <figcaption><?php echo wp_kses_post( $caption ); ?></figcaption>
</figure>