<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div class="cpb-image"> 
    <?php if ( ! empty( $url ) ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php endif;?>
    <img src="<?php echo esc_url( $img_src ); ?>" style="width:100%;display:block" <?php if( ! empty( $alt ) ):?>alt="<?php echo esc_html( $alt ); ?>"<?php endif;?> /> 
    <?php if ( ! empty( $url ) ) : ?></a><?php endif; ?>
</div>