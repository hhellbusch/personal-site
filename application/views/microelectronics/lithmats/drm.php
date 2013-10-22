<?php 
$asssets = array(
	'additionalJSFiles' => array(
		'jquery/flot/jquery.flot',
		'jquery/flot/jquery.flot.axislabel',
		'lithmats/drm'
	),
	'additionalCSSFiles' => array(
		'bootstrap/sticky-footer-navbar',
		'lithmats/drm'
	)
);
$headerData = array(
	'assets' => $asssets,
	'jsOnload' => 'draw();',
	'js' => 'var graphData = ' . json_encode($graphData) . ';'
);
$this->load->view('microelectronics/header', $headerData); 
?>
<h1>DRM</h1>

<div class="graphPlaceholder" id="theGraph" style="width:1018;height:550px;"></div>
<div id="legendContainer"></div>

<?php 
$this->load->view(
	'microelectronics/footer', 
	array('credit'=>"Henry Hellbusch - MCEE505 Homework 5 problem 3")
); ?>