.cpb-row {
	padding-bottom: 0;
	}

.cpb-row > header {
	background: #555;
	padding: 0;
	height: 35px;
	line-height: 35px;
	overflow: hidden;
	position: relative;
	}
	
.cpb-row > header .cpb-remove-item-action {
	display: inline-block;
	position: absolute;
	top: 0;
	right: 0;
	height: 35px;
	width: 34px;
	/*background-color: #999;*/
	border-left: 1px solid #bcbcbc;
	font-size: 0;
	background-image: url(<?php echo plugins_url( '/images/close-icon.png', dirname(__FILE__) );?>);
	background-position: center center;
	background-repeat: no-repeat;
}

.cpb-row > header .cpb-remove-item-action:hover {
	background-color: #c1c1c1;
}

.cpb-row > header .cpb-item-title{
	display: block;
	background: #555;
	padding: 0 1rem;
	margin: 0 35px;
	height: 35px;
	line-height: 35px;
	overflow: hidden;
	font-size: 0.9rem;
	color: #fff;
	text-decoration: none;
	cursor: move;
	font-weight: bold;
	text-transform: uppercase;
	border-left: 1px solid #bcbcbc;
	border-right: 1px solid #eee;
	}

.cpb-row > header .cpb-edit-item-action {
	display: inline-block;
	position: absolute;
	top: 0;
	left: 0;
	height: 35px;
	width: 34px;
	/*background-color: #999;*/
	border-right: 1px solid #eee;
	font-size: 0;
	background-image: url(<?php echo plugins_url( '/images/edit-icon.png', dirname(__FILE__) );?>);
	background-position: center center;
	background-repeat: no-repeat;
}

.cpb-row > header .cpb-edit-item-action:hover {
	background-color: #c1c1c1;
}

.cpb-row > .cpb-set-wrap {
	margin: 1rem 0;
}