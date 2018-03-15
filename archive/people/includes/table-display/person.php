<div class="cwp-table-row">
	<div class="cwp-table-cell profile-image">
    	<img src="<?php echo $person->get_photo() ?>" />
    </div>
    <div class="cwp-table-cell profile-contact">
    	<h3><?php echo $person->get_first_name() ?> <?php echo $person->get_last_name() ?></h3>
        <ul>
        	<li><?php echo $person->get_title() ?></li>
            <li><a href="mailto:<?php echo $person->get_email() ?>"><?php echo $person->get_email() ?></a></li>
            <li><?php echo $person->get_phone() ?></li>
            <li><?php echo $person->get_office() ?></li>
        </ul>
    </div>
    <?php if ( ! empty( $atts['bio'] ) ):?>
    <div class="cwp-table-cell profile-bio">
    	<?php switch( $atts['bio'] ){
			case 'department':
				if ( ! empty( $person->get_bio_unit() ) ){
					echo $person->get_bio_unit();
				} else {
					echo $person->get_bio_department();
				} // End if
				break;
		} ?>
    </div>
    <?php endif ?>
</div>