<div class="cpb-item cpb-content-item cpb-sublayout-item cpb-<?php echo esc_html( $slug ); ?> cpb-layout-item" data-id="<?php echo esc_html( $id ); ?>">
	<header><?php echo wp_kses_post( $edit_button ); ?><div class="cpb-item-title">Slideshow</div><?php echo wp_kses_post( $remove_button ); ?></header>
	<div class="cpb-child-set cpb-child-set-items">
		<?php
		// @codingStandardsIgnoreStart Already escaped
		echo $editor_content; 
		// @codingStandardsIgnoreEnd
		?>
	</div>
	<div class="add-part-action" data-slug="slide">+ Add Slide<input type="hidden" name="slug" value="slide" /></div>
	<fieldset>
		<input class="cpb-children-input" type="hidden" name="<?php echo esc_html( $input_name ); ?>[children]" value="<?php echo esc_html( $child_keys ); ?>" >
	</fieldset>
</div>
