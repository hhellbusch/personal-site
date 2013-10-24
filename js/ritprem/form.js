var onLoad = function()
{
	//attach button listeners!
	$('#addDiffusionStep').on('click', function(event) 
	{
		event.preventDefault();
		/* Act on the event */
		$('#diffusionRows').append(diffusionStepHTML);
	});

	//handles toggling of radio/implant form inputs
	$('#implantRadio').on('change', function()
	{
		radioChangeAction();
	});
	$('#constSourceRadio').on('change', function()
	{
		radioChangeAction();
	});
	$('#implantRadio').change();
	
}

var radioChangeAction = function()
{
	if ($('#implantRadio').is(":checked"))
	{
		$('#implantParams').show();
		$('#constSourceParams').hide();
	}
	else
	{
		console.log("here");
		$('#implantParams').hide();
		$('#constSourceParams').show();
	}
}