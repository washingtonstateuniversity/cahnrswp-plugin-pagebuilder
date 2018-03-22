<?php namespace CAHNRSWP\Plugin\Pagebuilder;
?><div class="cpb-item cpb-row cpb-layout-item <?php echo esc_html( $layout ); ?>" data-id="<?php echo esc_html( $id ); ?>">
    <header class="cpb-row"><?php echo wp_kses_post( $edit_button ); ?><a class="cpb-move-item-action cpb-item-title" href="#">Row | <?php echo esc_html( $layout ); ?></a><?php echo wp_kses_post( $remove_button ); ?></header>
    <div class="cpb-set-wrap">
        <div class="cpb-child-set cpb-layout-set">
			<?php echo $editor_content; ?>
        </div>
    </div>
	<footer></footer>
	<fieldset>
        <input class="cpb-children-input" type="hidden" name="<?php echo esc_html( $input_name ); ?>[children]" value="<?php echo esc_html( $child_keys ); ?>" >
	</fieldset>
</div>
