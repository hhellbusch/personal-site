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

<table>
	<tr>
		<td class='text-right'>Junction Depth:</td> 
		<td><strong><?php echo round_sig_figs($xj,6); ?> &mu;m</strong></td>
	</tr>
	<tr>
		<td class='text-right'>Dose:</td>
		<td> <strong><?php echo sprintf('%e', round_sig_figs($dose, 6)); ?> cm<sup>-2</sup> </strong></td>
	</tr>
	<tr>
		<td class='text-right'>Sheet Resistance:</td>
		<td> <strong> <?php echo round_sig_figs($sheetResistance, 6); ?> &#8486; / &#x25FB; </strong></td>
	</tr>
</table>

<?php
$this->load->view(
	'microelectronics/footer',
	array('credit' => "Group project by  Henry Hellbusch, Nate Walsh, Will Abisalih and Nicholas Edwards")
); 
?>