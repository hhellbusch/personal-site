<?php
$asssets = array(
	'additionalJSFiles' => array(
		'jquery/flot/jquery.flot',
		'jquery/flot/jquery.flot.axislabel',
		'lithmats/reflectivity'
	),
	'additionalCSSFiles' => array(
		'bootstrap/sticky-footer-navbar',
		'lithmats/reflectivity'
	)
);
$headerData = array(
	'assets' => $asssets,
	'jsOnload' => 'onLoad();',
);
if (isset($graphData))
{
	$headerData['js'] = 'var graphData = ' . json_encode($graphData) . ';';
}

$this->load->view('microelectronics/header', $headerData);
?>


<h1>Film Stack Reflectivity</h1>
<?php echo form_open('lithmats/filmStackReflectivity'); ?>
<div id='form' class='pull-left'>
	Default index of refraction values are for a wavelength of 248nm.  Values are from textbook.

	<h3>System Properties</h3>
		<div class="form-group">
			<label for="wavelength">Wavelength of Exposure</label>
			<div>
				<input type="text" name="wavelength" class='form-control' value="248" /> nm
				
			</div>
		</div>

	<h3>Substrate Properties</h3>
	<div class="form-group">
		<label for="substrateIndexReal">Substrate Index of Refraction</label>
		<div>
			<input type="text" name="substrateIndexReal" class='form-control' value="1.58" /> +
			<input type="text" name="substrateIndexImaginary" class='form-control' value="3.60" /> i
		</div>
	</div>
	
	<h3>Layers</h3> 
	<div class="form-group">
		<label for="polyIndexReal">Polysilcion Index of Refraction</label>
		<div>
			<input type="text" name="polyIndexReal" class='form-control' value="1.69" /> +
			<input type="text" name="polyIndexImaginary" class='form-control' value="2.76" /> i &nbsp; &nbsp;
			with thickness of <input type="text" name="polyThickness" class='form-control' value="15" /> nm
		</div>
	</div>

	<div class="form-group">
		<label for="substrateIndexReal">Photoresist Index of Refraction of</label>
		<div>
			<input type="text" name="resistIndexReal" class='form-control' value="1.76" /> +
			<input type="text" name="resistIndexImaginary" class='form-control' value="0.007" /> i &nbsp; &nbsp;
			with a thickness of <input type="text" name="resistThickness" class='form-control' value="800" /> nm
		</div>
	</div>
	

	<h3>Sweep options</h3>
	<div class="form-group">
		<label for="barcIndexStart">Index of refraction of BARC (n)</label>
		<div>
			Start: <input type="text" name="barcIndexStart" class='form-control' value="1" /> 
			To: <input type="text" name="barcIndexEnd" class='form-control' value="2" />
			Step: <input type="text" name="barcIndexStep" class='form-control' value="0.5" />
		</div>
	</div>

	<div class="form-group">
		<label for="barcIndexStart">Extinction coefficient of BARC (k)</label>
		<div>
			Start: <input type="text" name="barcExtinctionStart" class='form-control' value="0.1" /> 
			To: <input type="text" name="barcExtinctionEnd" class='form-control' value="0.3" />
			Step: <input type="text" name="barcExtinctionStep" class='form-control' value="0.1" />
		</div>
	</div>

	<div class="form-group">
		<label for="barcIndexStart">Thickness of BARC (in units of nm)</label>
		<div>
			Start: <input type="text" name="barcThicknessStart" class='form-control' value="100" /> 
			To: <input type="text" name="barcThicknessEnd" class='form-control' value="300" />
			Step: <input type="text" name="barcThicknessStep" class='form-control' value="5" />
		</div>
	</div>

	<!-- submit button! -->
	<div class='form-group'>
		<input type="submit" class='btn btn-primary' value="Execute!"  />
	</div>

</div> <!-- close form -->
<?php echo form_close(); ?>



<div id='filmStackVisual' class='pull-right'>
	<div id='photoresist' class='filmLayerLabel'>Photoresist</div>
	<div id='barc' class='filmLayerLabel'>BARC</div>
	<div id='poly' class='filmLayerLabel'>Poly</div>
	<div id='substrate' class='filmLayerLabel'>Substrate</div>
</div>

<div class='clearfix'></div>

<div class="graphPlaceholder" id="theGraph" style="width:1018;height:550px;"></div>
<div id="legendContainer"></div>

<?php
$footerData = array(
	'credit' => 'Film Stack Simulation for MCEE505<br />Made by Henry Hellbusch'
);
$this->load->view('microelectronics/footer', $footerData);
?>