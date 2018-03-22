<?php namespace CAHNRSWP\Plugin\Pagebuilder;
?><div id="cpb-editor-options">
	<?php foreach ( $values as $key => $value ) : ?>
	<label class="<?php if ( $cpb === $key ) echo 'active'; ?>" for="cpb-editor-option-<?php echo esc_html( $key ); ?>">
        <?php echo esc_html( $value ); ?>
    </label>
	<input type="radio" name="_cpb_pagebuilder" id="cpb-editor-option-<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $key ); ?>" <?php checked( $cpb, $key ); ?> />
    <?php endforeach; ?>
</div>
