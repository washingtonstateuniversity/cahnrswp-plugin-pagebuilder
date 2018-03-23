<ul class="cpb-form-dropdown-multi-select">
	<?php foreach ( $selected_posts as $post_id ) : ?>
	<?php if ( array_key_exists( $post_id, $options ) ) : ?><li data-postid="<?php echo esc_html( $post_id ); ?>" class="selected"><?php echo esc_html( $options[ $post_id ] ); ?></li><?php endif; ?>
	<?php endforeach; ?>
	<?php foreach ( $options as $id => $label ) : ?>
	<?php if ( ! in_array( $id, $selected_posts, true ) ) : ?><li data-postid="<?php echo esc_html( $id ); ?>"><?php echo esc_html( $label ); ?></li><?php endif; ?>
	<?php endforeach; ?>
</ul>
<input type="text" name="<?php echo esc_html( $name ); ?>[<?php echo esc_html( $prefix ); ?>post_ids]" value="<?php echo wp_kses_post( implode( ',', $selected_posts ) ); ?>" />
