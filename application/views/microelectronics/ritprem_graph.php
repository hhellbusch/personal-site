<?php 
$asssets = array(
	'additionalJSFiles' => array(
		'jquery/flot/jquery.flot',
		'jquery/flot/jquery.flot.axislabel',
		'ritprem/draw'
	),
	'additionalCSSFiles' => array(
		'bootstrap/sticky-footer-navbar',
		'ritprem'
	)
);
$headerData = array(
	'assets' => $asssets,
	'jsOnload' => 'draw();',
	'js' => 'var graphData = ' . json_encode($graphData) . ';'
);
$this->load->view('microelectronics/header', $headerData); 
?>
<h1>RITPREM</h1>

<div class="graphPlaceholder" id="theGraph" style="width:1018;height:550px;"></div>
<div id="legendContainer"></div>

<?php
$this->load->view(
	'microelectronics/footer',
	array('credit' => "Group project by  Henry Hellbusch, Nate Walsh, Will Abisalih and Nicholas Edwards")
); 
?>