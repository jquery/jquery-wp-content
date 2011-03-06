
//
// Executes on DOM ready
//
App.subscribe("init", function(){
	
	//	
	// Add Syntax Highlighting
	//
	SyntaxHighlighter.all();
	
	//
	// Add Search Interactions
	//
	$("#search").bind('focus', function(){
		$(this).parent().find("label").animate({opacity:'0.5'}, 200);
	}).bind('blur', function(){
		$(this).parent().find("label").animate({opacity:'1'}, 200);
	}).bind('keypress', function(){
		$(this).parent().find('label').hide();
	}).bind('keyup', function(){
		if($(this).val() == ''){
			$(this).parent().find('label').show();
		}
	});
	
	
	//
	// Fancy Dropdown
	//
	$(".sdropdown").bind("mouseover", function(){
		$(this).find("ul").stop().slideDown(200);
	}).bind("mouseout", function(){
		$(this).find("ul").stop().slideUp(200);
	});

	//
	// Temporary: REMOVE
	// Change page color
	//
	var colors = [ "jquery", "jquery-ui", "jquery-mobile", "jquery-project" ],
		color_string = colors.join(' ');
	$("ul.projects").delegate("li:lt(3)", "click", function(e) {
		e.preventDefault();
		$(document.documentElement)
			.removeClass(color_string)
			.addClass(this.className);
		window.location.hash = this.className;
	});
	
	if (window.location.hash && $.inArray(window.location.hash.substr(1), colors) > -1) {
		$(document.documentElement)
			.removeClass(color_string)
			.addClass(window.location.hash.substr(1));
	}
	
	//
	// Project Select Show/Hide
	//
	$(".toggle-projects").bind("click", function(e){
		e.preventDefault();
		$("body").animate({"marginTop":"140px"}, 500);
	});
	
});




