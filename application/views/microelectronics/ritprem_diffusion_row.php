<div class='form-group'>
	<div class='form-pull-left'>
		<label>Temperature
			<input name="diffuseTemp[]" type="number" min="600" max="2000" step="50" value="900"/> 
		</label>
		Celsius 
	</div>
	<div class='form-pull-left'>
		<label>
			Duration
			<input name="diffuseTime[]" type="number" min="0" step="10" value="600"/> 
		</label>
		Seconds
	</div>
	<div class='form-pull-left'>
		<select name="diffuseModel[]">
			<option value='constant'>Constant Diffusivity</option>
			<option value='fermi'>Fermi</option>
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