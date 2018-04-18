.cpb-column {
	
	position: relative;
	padding-bottom: 25px;
	}
.cpb-column .cpb-column-inner {
	margin: 0.5rem;
    box-sizing: border-box;
    background: #fff;
	border: 1px solid #ccc;
}
.cpb-column .cpb-column-inner > header {
	height: 30px;
    background-color: #ccc;
    position: relative;
}

.cpb-column .cpb-column-inner > footer {
	background: #ddd;
	height: 30px;
	line-height: 30px;
	position: absolute;
	overflow: hidden;
	margin-top: 0.5rem;
	width: 100%;
	bottom: 0;
	border-top: 1px solid #ddd;
}

.cpb-column .cpb-column-inner > .cpb-child-set-items{
	min-height: 50px;
	padding: 1rem;
	}

.cpb-column .cpb-column-inner .add-item-action {
	display: inline-block;
	background: #2ea2cc;
    border-color: #0074a2;
	color: #fff;
	margin: 1rem;
	text-decoration: none;
	padding: 0.25rem 0.5rem;
	border-radius: 4px;
}

.cpb-column .cpb-column-inner > header .cpb-edit-item-action {
	display: inline-block;
	position: absolute;
	top: 0;
	left: 0;
	height: 30px;
	width: 30px;
	border-right: 1px solid #eee;
	font-size: 0;
	background-image: url(<?php echo plugins_url( 'images/edit-icon.png', dirname(__FILE__) ); ?>);
	background-position: center center;
	background-repeat: no-repeat;
}

.cpb-column .cpb-column-inner > header .cpb-item-title {
	display: inline-block;
	padding: 0 0.5rem;
	margin: 0 30px;
	height: 30px;
	line-height: 30px;
	overflow: hidden;
	font-size: 0.8rem;
	color: #fff;
	text-decoration: none;
	cursor: move;
	text-transform: uppercase;
	border-left: 1px solid #ccc;
	font-weight: bold;
	}
 
.cpb-column .cpb-column-inner > footer {
	display: none;
}
