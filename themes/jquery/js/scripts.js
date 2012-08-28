
/*******************************************************************************/
/*	Subscriptions */
/*******************************************************************************/

//
// Executes on DOM ready
//
App.subscribe("init", function(){

var tooltip_timeout;
var $search = $('#search');

	//
	// Set Auto Height
	//
	$(window).bind("load resize", function(){
		$(".autoHeight").each(function(){
			var el = $(this), parent = el.parent();
			if(parent.height() >= el.height("auto").height()){
				el.css({"margin-bottom":0,"padding-bottom":"25px","height":parent.height()});
			} else {
				el.css({"margin-bottom":0,"padding-bottom":"25px","height":el.height("auto").height()});
			}
		});
    //		commented 12/29/11 unclear as to purpose
//		$("#container #body").css({"overflow":"visible"});
	});

	//
	// Add Search Interactions
	//
	$search.bind('focus', function(){
		$(this).parent().find("label").animate({opacity:'0.5'}, 200);
	}).bind('blur', function(){
		$(this).parent().find("label").animate({opacity:'1'}, 200);
	}).bind('keypress', function(){
		$(this).parent().find('label').hide();
	}).bind('keyup', function(){
		if($(this).val() === ''){
			$(this).parent().find('label').show();
		}
	});

	if ( $search.val() !== '' ) {
		$search.trigger('keypress');
	}
	//
	// Project Select Show/Hide
	//
	$(".toggle-projects").bind("click", function(e){
		e.preventDefault();
		var el = $(this);
		if(el.hasClass('active')){
			el.removeClass('active');
			$("body").css({"marginTop":"0"});
			el.removeClass('down');
		} else {
			el.addClass('active');
			$("body").css({"marginTop":"150px"});
			el.addClass('down');
		}
	});

	//
	// Project Select Clickoutside
	//
	$(".project-select").bind("clickoutside", function(e, el){
		var target = $(".toggle-projects");
		if($(el).parent(".toggle-projects").length != 1){
			if(target.hasClass('down')){
				target.removeClass("active down");
				$("body").css({"marginTop":"0"}, 300);
			}
		}
	});

	//
	// Footer Books + Presentations
	//
	$(".presentations img, .books img").each(function (i, el) {
		var $img = $(this),
		$span = $img.parent();
		$span.css("background-image", "url(" + $img.attr('src') + ")");
		$img.css("visibility", "hidden");
	});

	//
	// Footer Social Icons
	//
	$(".footer-icon-links")
		.find("li a")
		.append("<span></span>")
		.end()
		.delegate("li a", "mouseenter", function () {
			$(this).find("span").stop(true, false).fadeTo(250, 1.0);
		})
		.delegate("li a", "mouseleave", function () {
			$(this).find("span").stop(true, false).fadeOut(250);
		});

	//
	// Learning Site Specific
	//
	$("#sidebar .paper").bind("click", function(e){
		// Allow the actual links inside to take the normal link behaviour
		if ($(e.target).is("li a")) {
			return;
		}
		e.preventDefault();
		var el = $(this);
		if(el.hasClass("open")){
			el.removeClass("open").addClass("closed").animate({"width":"24%","margin-left":"-30.5%"}, 500);
		} else {
			el.removeClass("closed").addClass("open").animate({"width":"120%","margin-left":"-126.5%"}, 500);
		}
	});
	$("#sidebar").bind("clickoutside", function(e){
		var el = $(".paper",this);
		if(el.hasClass("open")){
			el.removeClass("open").addClass("closed").animate({"width":"24%","margin-left":"-30.5%"}, 500);
		}
	});

	// Project Tooltips
	$(".projects").find(".jquery, .jquery-ui, .jquery-mobile").on( "mouseover", function() {
		var el = $( this ),
			tooltips = $(".tooltips"),
			tooltip = {},
			$tooltip;

		if ( el.hasClass("jquery") ) {
			tooltip = $(".tooltips .jquery");
		}
		else if ( el.hasClass("jquery-ui") ) {
			tooltip = $(".tooltips .jquery-ui");
		}
		else if ( el.hasClass("jquery-mobile") ) {
			tooltip = $(".tooltips .jquery-mobile");
		}

		$tooltip = $( ".tooltip:visible" ,tooltips );
		if ( $tooltip !== tooltip && tooltip_timeout ) {
			clearTimeout( tooltip_timeout );
			$tooltip.fadeOut(200);
		}

		if ( tooltip.is(":hidden") ) {
			setTimeout(function() { tooltip.fadeIn(300); }, 300);
		}
	});

	$(".tooltips").find(".jquery, .jquery-ui, .jquery-mobile").on("mouseout", function() {
		var el = $( this );
		tooltip_timeout = setTimeout(function() { el.fadeOut(200); }, 300);
	}).bind("mouseover", function() {
		clearTimeout( tooltip_timeout );
	});

	// Fancy Dropdown
	$(".links .dropdown").hover(function() {
		$( this ).children("ul").stop( true, true ).slideDown( 100 );
	}, function() {
		$( this ).children("ul").stop( true, true ).slideUp( 100 );
	});

	// CDN auto-select-all
	$('#site-footer .cdn input').on("click", function() {
		if ( typeof this.select === "function" ) {
			this.select();
		}
	});

	$( ".entry-example" ).each(function() {
		var iframeSrc,
			src = $( this ).find( ".syntaxhighlighter" ),
			output = $( this ).find( ".code-demo" );

		if ( !src.length || !output.length ) {
			return;
		}

		iframeSrc = src.find( "td.code" ).text()
			.replace( /\s+/g, " " )
			.replace( "</head>",
				"<style>" +
					"html, body { border:0; margin:0; padding:0; }" +
					"body { font-family: 'Helvetica', 'Arial',  'Verdana', 'sans-serif'; }" +
				"</style>" +
				"</head>" );

		var iframe = document.createElement( "iframe" );
		iframe.src = "/web-base-template/themes/jquery/index-blank.html";
		iframe.width = "100%";
		iframe.height = output.attr( "data-height" ) || 250;
		iframe.style.border = "1px solid #eee";
		output.append( iframe );

		var doc = iframe.contentDocument ||
			(iframe.contentWindow && iframe.contentWindow.document) ||
			iframe.document ||
			null;

		if ( doc === null ) {
			return true;
		}

		doc.open();
		doc.write( iframeSrc );
		doc.close();
	});
});
