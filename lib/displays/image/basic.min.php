<div class="cpb-image">
	<?php if ( ! empty( $settings['url'] ) ):?><a href="<?php echo $settings['url'];?>"><?php endif;?>
		<img src="<?php echo $settings['img_src'];?>" style="width: 100%;display:block" <?php if( ! empty( $image_array['alt'] ) ):?>alt="<?php echo $image_array['alt'];?>"<?php endif;?> />
	<?php if ( ! empty( $settings['url'] ) ):?></a><?php endif;?>
</div>
