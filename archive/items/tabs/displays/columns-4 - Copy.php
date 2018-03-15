<div class="cpb-item-tabs-sections"><?php foreach( $tabs as $key => $tab ):?>
		<div class="cpb-item-tabs-section"></div><?php endforeach;?>
	</div>

<?php foreach( $tabs as $key => $tab ):?>
		<div class="cpb-item-tabs-section"></div><?php endforeach;?>

<div class="cpb-item cpb-item-tabs cpb-item-display-columns-4">
	<div class="cpb-item-tabs-nav">
		<?php foreach( $tabs as $key => $tab ):?><a href=""><?php echo $tab['title'];?></a><?php endforeach;?>
	</div>
	<div class="cpb-item-tabs-sections">
		<?php foreach( $tabs as $key => $tab ):?>
		<div class="cpb-item-tabs-section">
			<?php if ( ! empty( $tab['posts'] ) ){
				
				$post_ids = explode(',', $tab['posts']);
	
				foreach( $post_ids as $post_id ){
					
					$post = get_post( $post_id );
					
					echo $post->post_content;
					
				} // end foreach
	
			};?>
		</div>
		<?php endforeach;?>
	</div>
</div>