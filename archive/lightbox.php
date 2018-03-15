<!doctype html>
<html>
<head>
<meta charset="utf-8">
<base target="_blank" />
<title>Lightbox</title>
<?php wp_head();?>
</head>
<body id="cpb-iframe" class="fluid">

<article id="wrapper">
<?php

global $post;

if ( have_posts() ){
	
	while ( have_posts() ){
		
		the_post();
		
		$html = '<h1>' . apply_filters( 'the_title' , get_the_title() ) . '</h1>';
		
		$html .= apply_filters( 'the_content' , get_the_content() );
		
	 } // end while
	
} // end if




echo apply_filters( 'cpb_post_lightbox_html' , $html , $post );

?>
<?php wp_footer();?>
</article>
</body>
</html>