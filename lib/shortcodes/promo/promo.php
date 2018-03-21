<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div class="<?php echo $class ; ?>" data-requesturl="<?php echo $request_url ; ?>">
	<?php if ( ! empty( $img_src ) ):?>
	<div class="cpb-image">
		<img style="background-image:url(<?php echo $img_src; ?>);background-position:center center;background-size:cover;" src="<?php echo cpb_get_plugin_url( 'lib/images/' . $img_ratio. '.gif') ; ?>" alt="<?php echo $img_alt ; ?>" />
	</div>
	<?php endif;?>
	<div class="cpb-promo-caption">
	<?php if ( ! empty( $title ) ):?>
		<div class="cpb-title-wrapper"><<?php echo $tag ; ?> class="cpb-title"><?php echo $title; ?></<?php echo $tag; ?>></div>
	<?php endif;?>
	<?php if ( ! empty( $subtitle ) ):?>
			<div class="cpb-subtitle-wrapper"><div class="cpb-subtitle"><?php echo $subtitle; ?></div></div>
	<?php endif;?>				
	<?php if ( ! empty( $excerpt ) ):?>
			<div class="cpb-copy-wrapper"><div class="cpb-copy"><?php echo $excerpt ; ?></div></div>
	<?php  endif;?>
	<?php if ( ! empty( $link ) ):?>	
		<div class="cpb-promo-link"><a href="<?php echo $link ; ?>" >Visit <?php echo $title ; ?></a></div>
	<?php endif;?>
	</div>
</div>