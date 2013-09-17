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
