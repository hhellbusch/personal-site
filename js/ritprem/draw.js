var draw = function()
{
	var options = {
		xaxis : {
			axisLabel : "depth (um)",
			axisLabelUseCanvas : true,
			ticks: 4
		},
		yaxis : {
			axisLabel : "concentration (cm^-3)",
			axisLabelUseCanvas : true,
			transform:  function(v) 
			{
				return Math.log(v+0.0001); /*move away from zero*/
			},
			ticks: [0.001,0.01,0.1,1,10,100],
			tickDecimals: 3,
			tickFormatter: function (v, axis) 
			{
				return "10" + (Math.round( Math.log(v)/Math.LN10)).toString().sup();
			}
		},
		
		legend:{
			container:$('#legendContainer'),
			noColumns: 6
		},
		lines : {
			show: true
		}
	};



	

	var data1 = sampleFunction( 0, 5, function(x){ return Math.exp(x)*Math.sin(x)*Math.sin(x) } );
	var graphData = [{label: "label!", data: data1}];
	$.plot($('#theGraph'), graphData, options);
	// var previousPoint = null;
	// $('#theGraph').bind('plothover', function (event, pos, item) 
	// {
	// 	if (item) 
	// 	{
	// 		var identity = item.datapoint[0] + ' ' + item.datapoint[1];
	// 		if (previousPoint != identity) 
	// 		{
	// 			previousPoint = identity;
	// 			$('#tooltip').remove();
	// 			showTooltip(item.pageX, item.pageY, item.series.label);
	// 		}
	// 	}
	// 	else 
	// 	{
	// 		$('#tooltip').remove();
	// 		previousPoint = null;            
	// 	}
	// });
}

function sampleFunction(x1, x2, func) {
	var d = [ ];
	var step = (x2-x1)/300;
	for (var i = x1; i < x2; i += step )
		d.push([i, func( i ) ]);

	return d;
}