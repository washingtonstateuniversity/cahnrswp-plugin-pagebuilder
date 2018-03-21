<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<dl class="cpb-faq">
    <dt><<?php echo esc_html( $tag); ?>><?php echo esc_html( $title );?></<?php echo esc_html( $tag); ?>></dt>
    <dd><?php echo wp_kses_post( $content );?></dd>
</dl>