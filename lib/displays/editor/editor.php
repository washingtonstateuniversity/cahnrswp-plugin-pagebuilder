<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div id="cpb-editor">
    <?php echo $options_editor; ?>
    <?php echo $layout_editor; ?>
    <?php echo $form_editor; ?>
    <?php echo $excerpt_editor; ?>
    <?php echo wp_nonce_field( 'save_cahnrs_pagebuilder_' . $post->ID, 'cahnrs_pagebuilder_key', true, false ); ?>
    <input type="hidden" name="ajax-nonce" value="<?php echo wp_create_nonce( 'cahnrs_pb_ajax_'. $post->ID ); ?>" />
    <input type="hidden" name="ajax-post-id" value="<?php echo esc_html( $post->ID ); ?>" />
</div>