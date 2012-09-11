$(function() {

var projectToggle = $( ".toggle-projects" ).on( "click", function( event ) {
	event.preventDefault();

	if ( projectToggle.hasClass( "active" ) ) {
		projectToggle.removeClass( "active" );
		$( "body" ).css( "marginTop", 0 );
	} else {
		projectToggle.addClass( "active" );
		$( "body" ).css( "marginTop", 150 );
	}
});


$( document ).click(function( event ) {
	var target = $( event.target );
	if ( target.closest( ".project-select, .toggle-projects" ).length ||
			!projectToggle.hasClass( "active" ) ) {
		return;
	}

	projectToggle.removeClass( "active" );
	$( "body" ).css( "marginTop", 0 );
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

// all API sites
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
			"</head>" )
		// IE <10 executes scripts in the order in which they're loaded,
		// not the order in which they're written. So we need to defer inline
		// scripts so that scripts which need to be fetched are executed first.
		.replace( /<script>(.+)<\/script>/,
			"<script>" +
			"window.onload = function() {" +
				"$1" +
			"};" +
			"</script>" );

	var iframe = document.createElement( "iframe" );
	iframe.src = "about:blank";
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

// jqueryui.com
var demoFrame = $( ".demo-frame" ),
	demoDescription = $( ".demo-description" ),
	sourceView = $( ".view-source pre" ),
	demoList = $( ".demo-list" );
	currentDemo = location.hash.substr( 1 );

demoList.on( "click", "a", function( event ) {
	event.preventDefault();

	var filename = event.target.pathname,
		parts = filename.split( "/" ),
		plugin = parts[ 3 ],
		demo = parts[ 4 ].substr( 0, parts[ 4 ].length - 5 );

	$.getJSON( "/resources/demos/demo-list.json" ).then(function( demoList ) {
		demoDescription.html( demoList[ plugin ][ demo ].description );
		demoFrame.attr( "src", filename );
	});

	$.get( filename ).then(function( content ) {
		sourceView.text( content );
	});

	demoList.find( ".active" ).removeClass( "active" );
	$( this ).parent().addClass( "active" );
	location.hash = "#" + demo;
});

$( ".view-source a" ).on( "click", function() {
	sourceView.animate({
		opacity: "toggle",
		height: "toggle"
	});
});

if ( currentDemo ) {
	demoList.find( "a" ).filter(function() {
		return this.pathname.split( "/" )[ 4 ] === (currentDemo + ".html");
	}).click();
}

});
