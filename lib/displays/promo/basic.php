<div class="<?php echo $class ; ?>" data-requesturl="<?php echo $request_url ; ?>">
	<?php if ( ! empty( $item['img'] ) ):?>
	<div class="cpb-image">
		<img style="background-image:url(<?php echo $item['img']; ?>);background-position:center center;background-size:cover;" src="<?php echo cpb_plugin_url( 'images/' . $settings['img_ratio']. '.gif') ; ?>" alt="<?php echo $item['img_alt'] ; ?>" />
	</div>
	<?php endif;?>
	<div class="cpb-promo-caption">
	<?php if ( ! empty( $item['title'] ) ):?>
		<div class="cpb-title-wrapper"><<?php echo $settings['tag'] ; ?> class="cpb-title"><?php echo $item['title'] ; ?></<?php echo $settings['tag']; ?>></div>
	<?php endif;?>
	<?php if ( ! empty( $item['subtitle'] ) ):?>
			<div class="cpb-subtitle-wrapper"><div class="cpb-subtitle"><?php echo $item['subtitle'] ; ?></div></div>
	<?php endif;?>				
	<?php if ( ! empty( $item['excerpt'] ) ):?>
			<div class="cpb-copy-wrapper"><div class="cpb-copy"><?php echo $item['excerpt'] ; ?></div></div>
	<?php  endif;?>
	<?php if ( ! empty( $item['link'] ) ):?>	
		<div class="cpb-promo-link"><a href="<?php echo $item['link'] ; ?>" >Visit <?php echo $item['title'] ; ?></a></div>
	<?php endif;?>
	</div>
</div>