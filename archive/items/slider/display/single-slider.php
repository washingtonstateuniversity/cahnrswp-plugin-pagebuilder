<div class="cbp-slideshow-feature single-slider inactive">
	<div class="cpb-slide-set">
		<?php foreach( $slides as $index => $slide ):?>
		<div class="cpb-slide-item<?php if ( 0 === $index ) echo ' active';?>">
			<div class="cpb-image-wrapper">
				<div class="cpb-slide-image" style="background-image:url(<?php echo $slide['image'];?>)">
				</div>
			</div>
			<div class="cpb-caption-wrapper">
				<div class="cpb-caption-inner"><h3 class="cpb-caption-title"><?php echo $slide['title'];?></h3>
					<div class="cpb-caption-summary"><?php echo strip_tags ( $slide['excerpt'] );?></div>
				</div>
			</div>
			<div class="cpb-link-wrapper"><?php echo $slide['link_start'];?>Sweet Three-peat: 3rd Big Scoop win for Animal Sciences, WSU Creamery<?php echo $slide['link_end'];?></div>
		</div>
		<?php endforeach;?>
	</div>
	<?php if ( 1 < count( $slides ) ):?> <nav class="cpb-nav-controls">
		<div class="cpb-slide-nav slide-prev"></div>
		<div class="cpb-slide-thumbs-wrapper">
			<?php foreach( $slides as $index => $slide ):?>
			<div class="cpb-slide-thumb<?php if ( 0 === $index ) echo ' active';?>"></div>
			<?php endforeach;?>
		</div>
		<div class="cpb-slide-nav slide-next"></div>
	</nav><?php endif;?>
</div>