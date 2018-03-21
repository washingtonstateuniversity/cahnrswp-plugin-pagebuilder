<ul class="cpb-form-dropdown-multi-select">
	<?php foreach ( $selected_posts as $post_id ):?>
	<?php if ( array_key_exists( $post_id, $options ) ):?><li data-postid="<?php echo $post_id;?>" class="selected"><?php echo $options[ $post_id ];?></li><?php endif;?>
	<?php endforeach;?>
	<?php foreach ( $options as $id => $label ):?>
	<?php if ( ! in_array( $id, $selected_posts ) ):?><li data-postid="<?php echo $id;?>"><?php echo $label;?></li><?php endif;?>
	<?php endforeach;?>
</ul>
<input type="text" name="<?php echo $name;?>[<?php echo $prefix;?>post_ids]" value="<?php echo implode(',', $selected_posts );?>" />