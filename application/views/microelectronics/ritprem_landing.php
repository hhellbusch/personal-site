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
);
$this->load->view('microelectronics/header', $headerData);

if (!isset($dopants)) 
	throw new Exception('expected view variable $dopants to be based to view microelectronics/ritprem_landing.php');
?>

<h1>RITPREM</h1>

<form>
	<h3>Simulation Type</h3>
	<div class="radio">
		<label>
			<input type="radio" name="simulationType" id="implantRadio" value="implant"  checked />
			Implant the simulation space with an element as a specified power and dose.
		</label>
	</div>
	<div class="radio">
		<label>
			<input type="radio" name="simulationType" id="constSourceRadio" value="constantSource"  />
			Places an infinite dopant source at the surface of the simulation space.
		</label>
	</div>
	<h3>Simulation Space</h3>
	<div class="form-group">
		<label for="backgroundBase">Constant Background Doping Concentration</label>
		<div>
			<input type="number" name="backgroundBase" class='form-control' value="1" min="1", step=".1", max="9.9"/>
			<select name="backgroundPower" class="form-control">
				<?php 
				for($i = 9; $i < 20; $i++)
				{
					echo "<option value='$i'>$i</option>";
				}
				?>
			</select>
			cm<sup>-3</sup>
		</div>
	</div>

	<div class="form-group">
		<label for="backgroundDopant">Background Doping Element</label>
		<div>
			<select class="form-control" name="backgroundDopant">
				<?php 
				foreach ($dopants as $dopant)
				{
					echo "<option value='".$dopant->getSymbol()."'>".ucwords($dopant->getFullName())."</option>";
				}
				?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="spacing">Grid Spacing</label>
		<div class=''>
			<input type="number" class="form-control" name="spacing" value="0.01" min="0.01" step="0.01" />
			&mu;m
		</div>
	</div>

	<div class="form-group">
		<label for="depth">Grid Depth</label>
		<div>
			<input type="number" class="form-control" name="depth" value="5" max="10.0" min="0.10" step="0.10" />
			&mu;m
		</div>
	</div>
	<input type="submit" class='btn btn-primary' value="no worky yet" disabled/>
</form>


<?php
$this->load->view(
	'microelectronics/footer',
	array('credit' => "Group project by  Henry Hellbusch, Nate Walsh, Will Abisalih and Nicholas Edwards")
); 
?>