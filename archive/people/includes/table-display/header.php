<div class="cwp-table-row row-header">
	<div class="cwp-table-cell profile-image">
    </div>
    <div class="cwp-table-cell profile-contact">
    	Contact
    </div>
    <?php if ( ! empty( $atts['bio'] ) ):?>
    <div class="cwp-table-cell profile-bio">
    	<?php if ( ! empty( $atts['bio-label'] ) ): ?><?php echo $atts['bio-label'] ?><?php else: ?>Research Interests<?php endif ?>
    </div>
    <?php endif ?>
</div>