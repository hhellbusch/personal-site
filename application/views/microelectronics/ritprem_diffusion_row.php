<div class='form-group'>
	<div class='form-pull-left'>
		<label>Temperature
			<input class='form-control' name="diffuseTemp[]" type="text" value="900"/> 
		</label>
		Celsius 
	</div>
	<div class='form-pull-left'>
		<label>
			Duration
			<input class='form-control' name="diffuseTime[]" type="text" value="60"/> 
		</label>
		Minutes
	</div>
	<div class='form-pull-left'>
		<select class='form-control' name="diffuseModel[]">
			<option value='constant'>Constant Diffusivity</option>
			<option value='fermi'>"Plummer" Fermi</option>
			<option value='nate'>"Nate" Fermi</option>
		</select>
	</div>
	<!--<div class='form-pull-left'>
		<label> Graph? <input type='checkbox' name='diffuseGraph' /> </label>
	</div>-->
	<div class='form-pull-left'>
		<button type="button" class="close remove-diffuse-row" aria-hidden="true">&times;</button>
	</div>
	<div class='clearfix'></div>
</div>