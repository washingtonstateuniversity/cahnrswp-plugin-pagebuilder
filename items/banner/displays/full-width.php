<div class="cpb-banner display-full-width">
	<div class="cpb-banner-image-wrapper" style="<?php echo $height_style;?>">
		<div class="cpb-banner-image" style="background-image:url(<?php echo $settings['img_src'];?>)">
		</div>
	</div><div class="cpb-banner-content-wrapper">
		<div class="cpb-banner-image-content" style="<?php echo $height_style;?>">
			<?php if ( ! empty( $settings['content'] ) ):?><div class="cpb-banner-content"><?php echo $settings['content'];?></div><?php endif;?>
		</div><?php if ( ! empty( $settings['caption'] ) ):?><div class="cpb-banner-caption-wrapper">
			<div class="cpb-banner-caption"><?php echo $settings['caption'];?></div>
		</div><?php endif;?>
	</div>
</div>