<?php header("Content-type: text/css") ?>
<?php
//define colors
$background         = "#353432";
$content            = "#94BA65";
$button             = "#2B4E72";
$buttonFadeTo       = "#0f1c28";
$buttonBorder       = "#244360";
$buttonHoverFadeTo  = "#244360";
$buttonHover        = "#396a98";
$buttonActive       = $buttonHoverFadeTo;
$buttonActiveFadeTo = $buttonHover;
$name               = "#2B4E72";
$nameTextColor      = "white";
$container          = "#4E4D4A";
$fontColor          = "black";
$buttonSelectedFadeTo       = "#4074aa";
$buttonSelected = "#4981ba";
?>


body {
    text-align:center;
	font-family: Georgia, Times, Times New Roman, serif;
	font-size:14px;
	/*background-image:url('/images/background.svg');*/
	/*background-size:100%;*/
	background-color:<?php echo $background; ?>;
}

img {
	border:none;
}

.network_link
{
	cursor:pointer;
	height:40px;
}

.column {
	width: 50%;
	display:inline-block;
	float:left;
}

.network_icon {
	float: left;
	width:32px;
	height:32px;
}

.network_link{
	padding-left:20px;
}

.network_username {
	font-size:11px;
}

#container{
	
	margin-top:100px;
	position :relative;
	margin-left:auto;
	margin-right:auto;
	width:540px;
	height:100;
	
	
}

#content_container{
	background-color:<?php echo $container;?>;
	padding:10px;
	/*border: 1px solid black;*/
}

#top_header {
	background-color:<?php echo $content; ?>;
	text-align:left;
	position:relative;
	z-index:5;
	margin-bottom:0px;
	padding-top:20px;
	padding-bottom:20px;
	padding-left:20px;
	padding-right:20px;
	border-bottom: 1px solid black;
	border-top-left-radius: 10px;
	-moz-border-top-left-radius: 10px;
	-webkit-border-top-left-radius: 10px;
	border-top-right-radius: 10px;
	-moz-border-top-right-radius: 10px;
	-webkit-border-top-right-radius: 10px;
}

#contact_buttons {
	padding-top:10px;
}

#contact_buttons a {
	color:black;
	text-decoration:none;
}

#contact_buttons a.hover {
	color:black;
	text-decoration:none;
}

#contact_buttons a.visited {
	color:black;
	text-decoration:none;
}

#content {
	background-color:<?php echo $content; ?>;
	padding:10px;
	margin:0;
	z-index:3;
	text-align:justify;
	border: 1px solid black;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
}

#bottom_footer {
	background-color:<?php echo $content; ?>;
	text-align:right;
	/*width:540px;*/
	padding-top:5px;
	padding-bottom:5px;
	padding-right:5px;
	z-index:10;
	border-top: 1px solid black;
	border-bottom-left-radius: 10px;
	-moz-border-bottom-left-radius: 10px;
	-webkit-border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
	-moz-border-bottom-right-radius: 10px;
	-webkit-border-bottom-right-radius: 10px;
}

#header_left {
	background-color:<?php echo $name; ?>;
	color:<?php echo $nameTextColor;?>;
	border: 1px solid black;
	box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
	-webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
	-moz-box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	position: relative;
	display:inline-block;
	padding-right:10px;
	padding-left:10px;
}


.text_row{
	text-align:right;
	font-size:12px;
}

.text_row a{
	color:<?php echo $fontColor; ?>;
}

#header_right {
	display:inline-block;
	
	float:right
	
}

#contact_title {
	text-align:center;
}

.contact_button {
	display:inline-block;
}

.button {
	display: inline-block;
	outline: none;
	cursor: pointer;
	text-align: center;
	text-decoration: none;
	font-weight: bold;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 10px;
	padding-right: 10px;
	text-shadow: 0 1px 1px rgba(0,0,0,.3);
	-webkit-border-radius: .5em; 
	-moz-border-radius: .5em;
	border-radius: .5em;
	-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
	-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
	box-shadow: 0 1px 2px rgba(0,0,0,.2);
	color: #FFF;
	border: solid 1px <?php echo $buttonBorder; ?>;
	background: <?php echo $button; ?>;
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $button; ?>), to(<?php echo $buttonFadeTo;?>));
	background: -moz-linear-gradient(top,  <?php echo $button; ?>,  <?php echo $buttonFadeTo;?>);
	
}

.button:hover {
	text-decoration: none;
	background: <?php echo $buttonHover; ?>;
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $buttonHover; ?>), to(<?php echo $buttonHoverFadeTo; ?>));
	background: -moz-linear-gradient(top,  <?php echo $buttonHover;?>,  <?php echo $buttonHoverFadeTo; ?>);
	
}

.button:active {
	position: relative;
	top: 1px;
	color: #FFF;
	background: <?php echo $buttonActive; ?>;
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $buttonActive; ?>), to(<?php echo $buttonActiveFadeTo; ?>));
	background: -moz-linear-gradient(top,  <?php echo $buttonActive; ?>,  <?php echo $buttonActiveFadeTo; ?>);	
}

.button.selected
{
	background: <?php echo $buttonSelected; ?>;

	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $buttonSelected; ?>), to(<?php echo $buttonSelectedFadeTo; ?>));
	background: -moz-linear-gradient(top,  <?php echo $buttonSelected; ?>,  <?php echo $buttonSelectedFadeTo; ?>);
}

#initials {
	padding:0;
	margin:0;
	font-size:4em;
	
}

#name {
	padding:0;
	margin:0;
	font-size:1.48em;
	
}

.font{
	font-family: Georgia, Times, Times New Roman, serif;
	color: #fff;
}

.clear-both{
	clear:both;
}