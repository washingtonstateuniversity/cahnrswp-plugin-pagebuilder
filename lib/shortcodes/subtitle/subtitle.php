<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<?php if ( ! empty( $anchor ) ) : ?><a name="<?php echo esc_url( $anchor ); ?>"></a><?php endif; ?>
<<?php echo esc_html( $tag ); ?> class="<?php echo esc_html( $classes ); ?>">
    <?php if ( ! empty( $link ) ) :?><a href="<?php echo esc_url( $link ); ?>" ><?php endif;?>
        <?php echo esc_html( $title );?>
    <?php if ( ! empty( $link ) ) :?></a><?php endif;?>
</<?php echo esc_html( $tag ); ?>>