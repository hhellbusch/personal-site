<?php 
$asssets = array(
	'additionalJSFiles' => array(
		'jquery/flot/jquery.flot',
		'jquery/flot/jquery.flot.axislabel',
		'ritprem/form',
	),
	'additionalCSSFiles' => array(
		'bootstrap/sticky-footer-navbar',
		'ritprem'
	)
);

$diffusionStepHTML = $this->load->view('microelectronics/ritprem_diffusion_row', array(), true);
$headerData = array(
	'assets' => $asssets,
	'jsOnload' => 'onLoad();',
	'js' => 'var diffusionStepHTML = ' . json_encode($diffusionStepHTML) . ';'
);
$this->load->view('microelectronics/header', $headerData);

//should probably be in a view not a func
if (!function_exists('dopantTypesHTML'))
{
	function dopantTypesHTML($dopants, $name)
	{
		echo '<select class="form-control" name="'.$name.'">';
		foreach ($dopants as $dopant)
		{
			echo "<option value='".$dopant->getSymbol()."'>".ucwords($dopant->getFullName())."</option>";
		}
		echo '</select>';
	}
}

if (!isset($dopants)) 
	throw new Exception('expected view variable $dopants to be based to view microelectronics/ritprem_landing.php');
?>

<h1>RITPREM</h1>

<?php echo form_open('ritprem/simulate'); ?>
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
			<?php dopantTypesHTML($dopants, 'backgroundDopant'); ?>
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

	<h3>Dopant Source</h3>
	<div class="radio">
		<label>
			<input type="radio" name="simulationType" id="implantRadio" value="implant"  checked />
			<strong>Implant</strong> the simulation space with an element as a specified power and dose
		</label>
	</div>
	<div id="implantParams" class='col-md-offset-1'>
		<div class="form-group">
			<div class='form-pull-left'>
				<label>Dopant Type</label>
				<div>
					<?php dopantTypesHTML($dopants, 'implantDopant');?>
				</div>
			</div>
			<div class='form-pull-left'>
				<label>Energy</label>
				<div>
					<input class='form-control' type="number" name="implantEnergy" value="50" min="20" max="200" step="10"/> KeV
				</div>
			</div>
			<div class='form-pull-left'>
				<label>Dose</label>
				<div>
					<input type="number" name="implantDose" class='form-control' value="1" min="1", step=".1", max="9.9"/>
					<select name="implantDosePower" class="form-control">
						<?php 
						for($i = 9; $i < 20; $i++)
						{
							echo "<option value='$i'>$i</option>";
						}
						?>
					</select>
					cm<sup>-2</sup>
				</div>
			</div>
			<div class='clearfix'></div>
		</div>
	</div>

	<div class="radio">
		<label>
			<input type="radio" name="simulationType" id="constSourceRadio" value="constantSource"  />
			Places an <strong>infinite dopant source at the surface</strong> of the simulation space
		</label>
	</div>

	<div id="constSourceParams" class='col-md-offset-1'>
		<div class="form-group">
			<div class='form-pull-left'>
				<label>Dopant Type</label>
				<div>
					<?php dopantTypesHTML($dopants, 'constSourceDopant');?>
				</div>
			</div>
			<div class='form-pull-left'>
				<label>Surface Dopant Concentration</label>
				<div>
					<input type="number" name="constSourceConcBase" class='form-control' value="1" min="1", step=".1", max="9.9"/>
					<select name="constSourceConcPower" class="form-control">
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
			<div class='form-pull-left'>
				<label>Temperature</label>
				<div>
					<input name="constSourceTemp" type="number" min="600" max="2000" step="50" value="900"/> Celsius 
				</div>
			</div>
			<div class='form-pull-left'>
				<label>
					Duration
				</label>
				<div>
					<input name="constSourceTime" type="number" min="0" step="10" value="600"/> Seconds
				</div>
				
			</div>
			<div class='form-pull-left'>
				<div><label>Model</label></div>
				<select name="constantSourceModel">
					<option value='constant'>Constant Diffusivity</option>
					<option value='fermi'>Fermi</option>
				</select>
			</div>

			<div class='clearfix'></div>
		</div>
	</div>

	<h3>
		Diffusion
		<button type="button" id="addDiffusionStep" class='btn btn-default'>Add diffusion step</button> 
	</h3>
	<div class='form-group'>
		
		<div id="diffusionRows">
		</div>
	</div>

	<div class='form-group'>
		<input type="submit" class='btn btn-primary' value="might work?"/>
	</div>
</form>


<?php
$this->load->view(
	'microelectronics/footer',
	array('credit' => "Group project by  Henry Hellbusch, Nate Walsh, Will Abisalih and Nicholas Edwards")
); 
?>