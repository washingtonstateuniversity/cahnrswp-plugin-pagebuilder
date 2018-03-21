<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<fieldset id="cpb-add-row">
    <header>+ Add Row</header>
    <ul>
    <?php foreach ( $layouts as $slug => $label ) : ?><li class="add-row-item">
            <div class="cpb-image">
                <img class="img-<?php echo esc_html( $slug ); ?>-layout" src="<?php echo cpb_get_plugin_url( 'lib/images/spacer16x9.gif' ); ?>" />
            </div>  
            <input type="text" name="slug" value="row" />
                <input type="text" name="settings[layout]" value="<?php echo esc_html( $slug ); ?>" />
            <div class="cpb-title"><?php echo esc_html( $label ); ?></div> 
        </li><?php endforeach; ?>
    </ul>
</fieldset>