<style>
.cpb-slider {
	position: relative;
	padding: 0 40px;
	box-sizing: border-box;
}
.cpb-slider .cpb-slider-nav {
	/*position: absolute;
	top: 50%;
	left: 0;
	right: 0;*/
}
.cpb-slider .cpb-slider-nav .cpb-slider-nav-prev, 
.cpb-slider .cpb-slider-nav .cpb-slider-nav-next {
	/*position: absolute;
	top: -30px;
	display: block;
	background-color: #777;
	width: 40px;
	height: 60px;
	border-radius: 6px;
	border: 2px solid #ccc;*/
	position: absolute;
	top: 0;
	bottom: 0;
	width: 30px;
	background-color: #eee;
	border: 1px solid #ddd;
}
.cpb-slider .cpb-slider-nav .cpb-slider-nav-prev {
	/*left: -35px;*/
	left: 0;
	border-radius: 3px 0 0 3px;
}
.cpb-slider .cpb-slider-nav .cpb-slider-nav-next {
	/*right: -35px;*/
	right: 0;
	border-radius: 0 3px 3px 0;
}
.cpb-slider .cpb-slider-wrapper {
	overflow: hidden;
}
.cpb-slider .cpb-slider-wrapper > p {
	display: none;
}
.cpb-slider .cpb-slider-slides {
	width: 200%;
}
.cpb-slider .cpb-slider-slides:after {
	content: '';
	clear: both;
	display: block;
}
.cpb-slider .cpb-slider-slide {
	padding-bottom: 12%;
	width: 12%;
	float: left;
	margin: 0 0.25%;
	box-sizing: border-box;
	position: relative;
	background-color: #fff;
	border: 1px solid #eee;
	-webkit-box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.1);
	-moz-box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.1);
	box-shadow: 0px 0px 3px 2px rgba(0,0,0,0.1);
}
.cpb-slider .cpb-slider-image {
	position: absolute;
	top: 8px;
	bottom: 8px;
	left: 8px;
	right: 8px;
	background-color: #000;
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
}
.cpb-slider .cpb-slider-caption {
	position: absolute;
	bottom: 8px;
	left: 8px;
	right: 8px;
	background-color: rgba(0,0,0,0.7);
	padding: 8px 8px 12px;
	box-sizing: border-box;
}
.cpb-slider .cpb-slider-caption-title {
	color: #fff;
	font-weight: bold;
	font-size: 16px;
	letter-spacing: 1px;
}
.cpb-slider .cpb-slider-caption-link {
	color: #fff;
	text-transform: uppercase;
	font-size: 12px;
	letter-spacing: 1px;
	padding-top: 8px;
}
</style>
<div class="cpb-slider">
	<nav class="cpb-slider-nav">
    	<div class="cpb-slider-nav-prev"></div>
        <div class="cpb-slider-nav-next"></div>
    </nav>
	<div class="cpb-slider-wrapper">
    	<div class="cpb-slider-slides">
        	<div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">Title Goes Here Could Be Longer</div>
                    <div class="cpb-slider-caption-link">Learn More</div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
            <div class="cpb-slider-slide">
            	<div class="cpb-slider-image" style="background-image: url(http://extension.wsu.edu/wp-content/uploads/2016/07/honeybeetowerofjewels-e1467482623782.jpg)"></div>
                <div class="cpb-slider-caption">
                	<div class="cpb-slider-caption-title">
                    	Title Goes Here Could Be Longer
                    </div>
                    <div class="cpb-slider-caption-link">
                    	Learn More
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>