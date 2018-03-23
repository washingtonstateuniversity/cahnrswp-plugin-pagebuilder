<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><div id="cpb-excerpt-options">
	<?php foreach ( $values as $key => $value ) : ?>
<label class="<?php if ( $excerpt_type === $key ) : ?> active<?php endif; ?>" for="cpb-excerpt-option-<?php echo esc_html( $key ); ?>">
		<?php echo esc_html( $value ); ?>
	</label>
	<input type="radio" name="_cpb_m_excerpt" id="cpb-excerpt-option-<?php echo esc_html( $key ); ?>" value="<?php echo esc_html( $key ); ?>" <?php checked( $excerpt_type, $key ); ?> />
	<?php endforeach; ?>
	<textarea name="_cpb_excerpt" <?php if ( empty( $excerpt_type ) ) : ?>disabled="disabled"<?php endif; ?> ><?php echo wp_kses_post( $post->post_excerpt ); ?></textarea>
</div>
