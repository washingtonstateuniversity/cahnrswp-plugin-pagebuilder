<?php namespace CAHNRSWP\Plugin\Pagebuilder;

?><div id="cpb-editor-layout" class="cpb-item">
	<header></header>
	<div class="cpb-child-set cpb-layout-set">
		<?php
		// @codingStandardsIgnoreStart Already escaped
		echo $editor_content; 
		// @codingStandardsIgnoreEnd
		?>	
	</div>
	<footer></footer>
	<fieldset>
		<input class="cpb-children-input" type="hidden" name="_cpb[layout]" value="<?php echo esc_html( $child_ids ); ?>" >
	</fieldset>
	<?php
		// @codingStandardsIgnoreStart Already escaped
		echo $add_shortcode_form;
		// @codingStandardsIgnoreEnd
	?>
	<?php
		// @codingStandardsIgnoreStart Already escaped
		echo $add_row_form;
		// @codingStandardsIgnoreEnd
	?>
</div>
