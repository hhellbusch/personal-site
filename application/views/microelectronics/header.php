<?php echo doctype('html5'); ?>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="EXPIRES" CONTENT="-1">
	<META NAME="AUTHOR" CONTENT="Henry Hellbusch">
	<META HTTP-EQUIV="CONTENT-LANGUAGE" CONTENT="en-US">
	<META NAME="DESCRIPTION" CONTENT="Microelectronics Tools">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<?php 
	$assetsData = array();
	if (isset($assets)) $assetsData = $assets;
	$this->load->view('assets', $assetsData);
	?>

	<?php 
	if(isset($js)){
	?>
		<script>
			<?php echo $js; ?>
		</script>
	<?php
	}
	?>


	<?php
	if (isset($jsOnload))
	{
	?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
		  <?php echo $jsOnload; ?>
		});
		</script>
	<?php
	}
	?>

	<?php if (isset($title)) 
	{
	?>
		<title><?php echo $title; ?></title>
	<?php
	}
	else
	{
		?>
		<title>uE Tools</title>
		<?php
	}
	?>

</head>
<body>
	<div id="wrap">
		<?php /*
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">
						&mu;E Tools
					</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li class="divider"></li>
								<li class="dropdown-header">Nav header</li>
								<li><a href="#">Separated link</a></li>
								<li><a href="#">One more separated link</a></li>
							</ul>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!-- /.container -->
		</div> <!-- /.navbar -->
		*/ ?>
	<div class='container'>

