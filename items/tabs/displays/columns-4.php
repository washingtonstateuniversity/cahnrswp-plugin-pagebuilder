<div class="cpb-item-tabs-set cpb-item-tabs-columns" style="<?php if ( ! empty( $settings['min_height'] ) ) { echo 'height:' . $settings['min_height']; }?>">
	<?php foreach( $tabs as $key => $tab ):?>
		<div class="cpb-item-tab-title cpb-item-tab-column <?php echo $tab['bgcolor'];?>-back <?php echo $settings['textcolor'];?>-text">
			<?php if ( ! empty( $tab['bgimage'] ) ):?><div class="cpb-item-tab-bgimage" style="background-image: url(<?php echo $tab['bgimage'];?> )"></div><?php endif;?>
			<div class="cpb-item-tab-inner-content"><h2 class="<?php echo $settings['textcolor'];?>-text"><?php echo $tab['title'];?></h2></div>
		</div>
		<div class="cpb-item-tab-content cpb-item-tab-column"><div class="cpb-item-tab-inner-content">
		<?php if ( ! empty( $tab['posts'] ) ){
				
				$post_ids = explode(',', $tab['posts']);
	
				foreach( $post_ids as $post_id ){
					
					$post = get_post( $post_id );
					
					echo $post->post_content;
					
				} // end foreach
	
			};?>
			</div></div>
	<?php endforeach;?>
</div>