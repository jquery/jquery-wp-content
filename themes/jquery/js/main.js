// Some CSS depends on the `js` class.
$( "html" ).addClass( "js" ).removeClass( "no-js" );

/*
 * All sites
 */
$(function() {
	// copyable auto-select-all
	$( ".copyable" ).on( "click", function() {
		if ( typeof this.select === "function" ) {
			this.select();
		}
	});

	// collapsible navigation for smaller screens
	$('.menu-trigger').on('click', function (e) {
		var $btn = $(this),
			$nav = $(this).next('.menu');
		if ($nav.hasClass('menu-open')) {
			$btn.attr('aria-expanded', 'false');
			$nav.removeClass('menu-open');
		} else {
			$btn.attr('aria-expanded', 'true');
			$nav.addClass('menu-open');
			$nav.find('a').first().focus();
		}
	});
});





/*
 * API sites
 */
$(function() {
	$( ".entry-example" ).each(function() {
		var iframeSrc,
			src = $( this ).find( ".syntaxhighlighter" ),
			output = $( this ).find( ".code-demo" );

		if ( !src.length || !output.length ) {
			return;
		}

		// Get the original source
		iframeSrc = src.find( "td.code .line" ).map(function() {
			// Convert non-breaking spaces from highlighted code to normal spaces
			return $( this ).text().replace( /\xa0/g, " " );
		// Restore new lines from original source
		}).get().join( "\n" );

		iframeSrc = iframeSrc
			// Insert styling for the live demo that we don't want in the
			// highlighted code
			.replace( "</head>",
				"<style>" +
					"html, body { border:0; margin:0; padding:0; }" +
					"body { font-family: 'Helvetica', 'Arial',  'Verdana', 'sans-serif'; }" +
				"</style>" +
				"</head>" )
			// IE <10 executes scripts in the order in which they're loaded,
			// not the order in which they're written. So we need to defer inline
			// scripts so that scripts which need to be fetched are executed first.
			.replace( /<script>([\s\S]+)<\/script>/,
				"<script>" +
				"window.onload = function() {" +
					"$1" +
				"};" +
				"</script>" );

		var iframe = document.createElement( "iframe" );
		iframe.width = "100%";
		iframe.height = output.attr( "data-height" ) || 250;
		output.append( iframe );

		var doc = (iframe.contentWindow || iframe.contentDocument).document;
		doc.write( iframeSrc );
		doc.close();
	});
});

/*
 * jqueryui.com
 */
$(function() {
	var demoFrame = $( ".demo-frame" ),
		demoDescription = $( ".demo-description" ),
		sourceView = $( ".view-source > div" ),
		demoList = $( ".demo-list" ),
		currentDemo = location.hash.substring( 1 );

	demoList.on( "click", "a", function( event ) {
		event.preventDefault();

		var filename = "/" + event.target.pathname.replace( /^\//, "" ),
			parts = filename.split( "/" ),
			plugin = parts[ 3 ],
			demo = parts[ 4 ].substring( 0, parts[ 4 ].length - 5 );

		if ( demoList.is( "[data-full-nav]" ) ) {
			window.location = "/" + (demo === "default" ? plugin : demo) + "/";
			return;
		}

		$.getJSON( "/resources/demos/demo-list.json" ).then(function( demoList ) {
			demoDescription.html( $.grep( demoList[ plugin ], function( x ) {
				return x.filename === demo;
			})[ 0 ].description );
			demoFrame.attr( "src", filename );
		});

		$.get( filename.replace( "demos", "demos-highlight" ) ).then(function( content ) {
			sourceView.html( content );
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
