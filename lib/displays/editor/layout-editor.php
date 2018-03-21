<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div id="cpb-editor-layout" class="cpb-item">
    <header></header>
    <div class="cpb-child-set cpb-layout-set">
		<?php echo $editor_content; ?>	
	</div>
    <footer></footer>
    <fieldset>
        <input class="cpb-children-input" type="hidden" name="_cpb[layout]" value="<?php echo esc_html( $child_ids ); ?>" >
    </fieldset>
    <?php echo $add_shortcode_form; ?>
    <?php echo $add_row_form; ?>
</div>