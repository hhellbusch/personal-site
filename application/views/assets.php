<?php 
$cssFiles = array(
	'bootstrap/bootstrap'
);

if (isset($additionalCSSFiles))
{
	foreach($additionalCSSFiles as $cssFile)
	{
		if (!in_array($cssFile, $cssFiles))
		{
			$cssFiles[] = $cssFile;
		}
	}
}
foreach ($cssFiles as $cssFile)
{
	echo link_tag('css/' . $cssFile . '.css');
}

$jsFiles = array(
	'jquery/jquery-1.10.2.min',
	'jquery/jquery-ui-1.10.3.custom.min',
	'bootstrap/bootstrap',
);
if (isset($additionalJSFiles))
{
	foreach($additionalJSFiles as $jsFile)
	{
		if (!in_array($jsFile, $jsFiles))
		{
			$jsFiles[] = $jsFile;
		}
	}
}

//doing this fixes potential conflicts that might occur if additonaly JS files aren't loaded yet.
//i dont understand why it happens...but it does.



//sometimes its useful to have the js to not combine while debugging js.
foreach ($jsFiles as $jsFile)
{
?>
	<script language="javascript" type="text/javascript" src="<?php echo base_url() . '/js/' .  $jsFile . '.js'; ?>"></script>
<?php
}
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo base_url();  ?>/js-src/jquery/flot/excanvas.js"></script><![endif]-->
