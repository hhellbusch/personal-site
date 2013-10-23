var graphMinPower = 9;
var graphMaxPower = 22;

var draw = function()
{
	var options = {
		xaxis : {
			axisLabel : "depth (um)",
			axisLabelUseCanvas : true,
			//ticks: 4
		},
		yaxis : {
			axisLabel : "concentration (cm^-3)",
			axisLabelUseCanvas : true,
			transform:  function(v) 
			{
				/*move away from zero*/
				return Math.log(v+(Math.pow(10, graphMinPower)));
			},
			ticks: generateLogScaleTickValues(graphMinPower, graphMaxPower),
			tickDecimals: 3,
			tickFormatter: function (v, axis) 
			{
				var str = "" + v;
				var oneIndex = str.indexOf('1');
				if (oneIndex == -1 || oneIndex != 0) return "";
				console.log(v);
				return "10" + (Math.round( Math.log(v)/Math.LN10)).toString().sup();
			},
			//minTickSize:1,
			//tickLength:10
		},
		
		legend:{
			container:$('#legendContainer'),
			noColumns: 6
		},
		lines : {
			show: true
		},
		points: {
			show: true
		}
	};

	console.log(graphData);
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

function generateLogScaleTickValues(minPower, maxPower)
{
	var ticks = [];
	for (var power = minPower; power < maxPower; power++)
	{
		var zeros = Math.pow(10, power); //creates the right magnitude
		for (var i = 1; i <= 9; i++)
		{
			//multiply the zeros by integers from 1 to 9
			var num = i * zeros;
			if (num < 1)
			{
				num = num.toFixed(Math.abs(power));
			}
			ticks.push(num);
		}
	}
	ticks.push(Math.pow(10, maxPower));
	return ticks;
}