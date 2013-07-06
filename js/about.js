var onLoad = function()
{
	//a bunch of stuff to do on load.
	$("#about").hide();
	$("#works").hide();
	$("#contact").show();
	
	//add listeners to buttons.
	$("#about_button").live(
	{
		click: function()
		{
			
			if ($("#works").is(":visible")) 
			{
				updateSelectedButton($(this), $("#works_button"));
				toggleView($("#works"), $("#about"));
			} 
			else if ($("#contact").is(":visible")) 
			{
				updateSelectedButton($(this), $("#contact_button"));
				toggleView($("#contact"), $("#about"));
			}
		}
	});
	$("#contact_button").live(
	{
		click: function()
		{
			
			if ($("#works").is(":visible")) 
			{
				updateSelectedButton($(this), $("#works_button"));
				toggleView($("#works"), $("#contact"));
			} 
			else if ($("#about").is(":visible")) 
			{
				updateSelectedButton($(this), $("#about_button"));
				toggleView($("#about"), $("#contact"));
			}
		}
	});
	
	$(".network_link").hover(
		function()
		{
			$(this).stop();
			$(this).animate({"paddingLeft": "6px"}, 200);	
		},
		function()
		{
			$(this).stop();
			$(this).animate({"paddingLeft": "20px"}, 200);
		}
		
	);
	
	
	
	$("#works_button").live(
	{
		click: function()
		{

			if ($("#about").is(":visible")) 
			{
				updateSelectedButton($(this), $("#about_button"));
				toggleView($("#about"), $("#works"));
			} else if ($("#contact").is(":visible")) {
				updateSelectedButton($(this), $("#contact_button"));
				toggleView($("#contact"), $("#works"));
			}
		}
	});
};

var toggleView = function (toHide, toShow) {
	var speed = 300;
	toHide.slideUp(speed);
	toShow.slideDown(speed);
}

var updateSelectedButton = function(toSelectButton, toUnselectButton)
{
	toSelectButton.addClass('selected');
	toUnselectButton.removeClass('selected');
}