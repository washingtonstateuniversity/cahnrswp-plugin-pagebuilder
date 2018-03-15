<?php

require_once plugin_dir_path( dirname ( __FILE__ ) ) . 'classes/class-cpb-stylesheet.php';

$style = new CPB_Stylesheet();

$style->do_stylesheet( $_GET['cpb-stylesheet'] );