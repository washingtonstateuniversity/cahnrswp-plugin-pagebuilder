<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php do_action( 'wp_enqueue_scripts' );?>
<?php 
	global $wp_styles; 
	foreach( $wp_styles->registered as $key => $style ){
		
		if ( strpos ( $style->src , 'wp-admin' ) || strpos ( $style->src , 'wp-includes' ) ) continue;
		
		if ( 10 > strlen( $style->src ) ) continue;
		
		echo '<link rel="stylesheet" id="' . $key . '"  href="' . $style->src . '" type="text/css" media="all" />';
		
	} // end foreach
	
	?>
</head>
<style>
html {
	background: none;
}

body > main > p {
	margin-top: 0;
}
</style>
<body style="margin:0;padding:0; background:none;">
<main style="margin:0;padding:0;">
</main></body>
</html>