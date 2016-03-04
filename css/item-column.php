.cpb-column {
	background: #fff;
	border: 1px solid #ccc;
	position: relative;
	padding-bottom: 25px;
	}

.cpb-column > footer {
	background: #ddd;
	height: 25px;
	line-height: 25px;
	position: absolute;
	overflow: hidden;
	margin-top: 0.5rem;
	width: 100%;
	bottom: 0;
	border-top: 1px solid #ddd;
}

.cpb-column > .cpb-child-set-items{
	min-height: 50px;
	padding: 1rem;
	}

.cpb-column .add-item-action {
	display: inline-block;
	background: #2ea2cc;
    border-color: #0074a2;
	color: #fff;
	margin: 1rem;
	text-decoration: none;
	padding: 0.25rem 0.5rem;
	border-radius: 4px;
}

.cpb-column > footer .cpb-edit-item-action {
	display: inline-block;
	position: absolute;
	top: 0;
	left: 0;
	height: 25px;
	width: 24px;
	border-right: 1px solid #eee;
	font-size: 0;
	background-image: url(<?php echo plugins_url( 'images/edit-icon.png', dirname(__FILE__) ); ?>);
	background-position: center center;
	background-repeat: no-repeat;
}

.cpb-column > footer .cpb-item-title {
	display: inline-block;
	padding: 0 0.5rem;
	margin: 0 25px;
	height: 25px;
	line-height: 25px;
	overflow: hidden;
	font-size: 0.8rem;
	color: #fff;
	text-decoration: none;
	cursor: move;
	text-transform: uppercase;
	border-left: 1px solid #ccc;
	font-weight: bold;
	}
