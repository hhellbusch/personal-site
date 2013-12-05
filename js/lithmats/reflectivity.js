var onLoad = function()
{
	if (typeof graphData !== 'undefined')
	{
		draw();
	}
}

var draw = function()
{
	var options = {
		xaxis : {
			axisLabel : "BARC Thickness",
			axisLabelUseCanvas : true
		},
		yaxis : {
			axisLabel : "Substrate Reflectivity",
			axisLabelUseCanvas : true
		},
		
		legend:{
			container:$('#legendContainer'),
			noColumns: 6
		},
		lines : {
			show: true
		}
	};


	$.plot($('#theGraph'), graphData, options);
}