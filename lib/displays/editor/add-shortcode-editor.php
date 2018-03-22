<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><ul class="cpb-add-items-set">
	<?php foreach ( $shortcodes as $slug => $shortcode ) : ?><li>
		<input type="text" name="slug" value="<?php echo esc_html( $slug ); ?>" />
		<span><?php echo esc_html( $shortcode['label'] ); ?></span>
	</li><?php endforeach; ?>
</ul>
