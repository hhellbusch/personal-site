var graphMinPower = -2;
var graphMaxPower = 3;

var draw = function()
{
	var options = {
		xaxis : {
			axisLabel : "Ratio of PAC concentration",
			axisLabelUseCanvas : true,
			//ticks: 4
		},
		yaxis : {
			axisLabel : "Development Rate (nm/s)",
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
				if (str.indexOf('1') == -1) return "";
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
		// points: {
		// 	show: true
		// }
	};


	$.plot($('#theGraph'), graphData, options);
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