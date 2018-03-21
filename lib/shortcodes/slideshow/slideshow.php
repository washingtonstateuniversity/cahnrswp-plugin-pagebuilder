<?php namespace CAHNRSWP\Plugin\Pagebuilder; ?>
<div class="cpb-slideshow <?php echo $atts['display_type'] ;?>">
	<div class="slides-wrapper">
        <ul class="slides"><?php echo $slides ;?></ul>
        <nav class="slideshow-primary"><a href="#" class="prev">Previous</a><a href="#" class="next">Next</a></nav>
    </div>
    <nav class="slideshow-secondary"></nav>
</div>