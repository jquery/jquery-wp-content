/*
 * All sites
 */
$(function() {
	// CDN auto-select-all
	$( ".cdn input" ).on( "click", function() {
		if ( typeof this.select === "function" ) {
			this.select();
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
 * jquery.org
 */
$(function() {
	$(".flexslider").flexslider({
		controlNav: "false"
	});

	/*
	 * Join page
	 */
	(function() {
		function isEmail( str ) {
			return (/^[a-zA-Z0-9.!#$%&'*+\/=?\^_`{|}~\-]+@[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*$/).test( str );
		}

		// Enlarged gifts
		$(".enlarge").colorbox();

		// Gift forms
		var gifts = $(".choose-gifts").hide();
		$(".member-level .join").on( "click", function() {
			var gift = $( this ).nextAll(".choose-gifts").slideToggle();
			gifts.not( gift ).slideUp();
		});

		function processMembership( data ) {
			$.ajax({
				url: StripeForm.url,
				data: $.extend({
					action: StripeForm.action,
					nonce: StripeForm.nonce
				}, data )
			}).done(function() {
				window.location = "/join/thanks/";
			}).fail(function() {
				// TODO
			});
		}

		$(".member-level .pay").on( "click", function( event ) {
			event.preventDefault();
			var button = $( this ),
				form = $( this.form ),
				firstName = $.trim( form.find( "[name=first-name]" ).val() ),
				lastName = $.trim( form.find( "[name=last-name]" ).val() ),
				email = $.trim( form.find( "[name=email]" ).val() ),
				address = $.trim( form.find( "[name=address]" ).val() ),
				gifts = form.find( "select" ),
				errors = form.find( ".errors" ).empty().hide(),
				valid = true;

			function showError( msg ) {
				$( "<li>" ).text( msg ).appendTo( errors );
				valid = false;
			}

			// Verify all fields
			if ( !firstName ) {
				showError( "Please provide your first name." );
			}
			if ( !lastName ) {
				showError( "Please provide your last name." );
			}
			if ( !isEmail( email ) ) {
				showError( "Please provide a valid email address" );
			}
			if ( address.length < 10 ) {
				showError( "Please provide your full address." );
			}
			if ( gifts.filter(function() { return !$( this ).val(); }).length ) {
				showError( "Please choose a size for each gift." );
			}

			if ( !valid ) {
				errors.slideDown();
				return;
			}

			StripeCheckout.open({
				key: StripeForm.key,
				image: button.data("image"),
				name: button.data("name"),
				description: button.data("description"),
				panelLabel: button.data("panel-label"),
				amount: button.data("amount"),
				token: function( stripeData ) {
					var data = {
						token: stripeData.id,
						planId: button.data( "plan-id" ),
						firstName: firstName,
						lastName: lastName,
						email: email,
						address: address
					};
					gifts.each(function() {
						data[ this.name ] = this.value;
					});
					processMembership( data );
				}
			});
		});
	})();
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

/*
* Creating navigation elements for smaller-width screens using tinyNav and Chosen
*/
$(function() {

	// For the site-specific navigation, we just need to create and style the select
	var $siteMenu = $("#menu-top").tinyNav(),
	$siteNav = $siteMenu.next();

	// In order for Chosen to work as we'd like, 
	// we have to insert the placeholder attribute, an empty option, and select it before instantiation
	$siteNav.attr("data-placeholder", "Navigate...").prepend("<option></option>").val("").chosen();

	// For the global site navigation, we move the generated control to the
	// site footer so it doesn't appear above the header
	var $globalLinks = $("#global-nav .links").tinyNav(),
	$nav = $globalLinks.next(),
	$container = $("<div class='tinynav-container'></div>"),
	$header = $("<h3><span>More jQuery Sites</span></h3>");

	$container.append( $header, $nav ).insertBefore("ul.footer-icon-links");
	$nav.attr("data-placeholder", "Browse...").prepend("<option></option>").val("").chosen();
});
