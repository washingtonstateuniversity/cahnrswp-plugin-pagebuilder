<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div class="cpb-item cpb-column cpb-layout-item column-<?php echo esc_html( $index ); ?>" data-id="<?php echo esc_html( $id ); ?>">
	<div class="cpb-column-inner">
		<header><?php echo wp_kses_post( $edit_button ); ?><a class="cpb-move-item-action cpb-item-title" href="#">Column <span class="cpb-column-index"><?php echo esc_html( $index_int ); ?></span></a></header>
		<div class="cpb-child-set cpb-child-set-items">
		    <?php echo $editor_content; ?>
		</div>
		<a href="#" class="add-item-action">+ Add Item</a>
		<footer><?php echo wp_kses_post( $edit_button ); ?><a class="cpb-move-item-action cpb-item-title" href="#">Column <span class="cpb-column-index"><?php echo esc_html( $index_int ); ?></span></a></footer>
	</div>
	<fieldset>
		<input class="cpb-children-input" type="hidden" name="<?php echo esc_html( $input_name ); ?>[children]" value="<?php echo esc_html( $child_keys ); ?>" >
	</fieldset>
</div>