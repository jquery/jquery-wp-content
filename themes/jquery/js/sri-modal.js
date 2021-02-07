$( function() {
	// Store modal templates
	var modalTemplate = $( "#sri-modal-template" )[ 0 ].outerHTML;

	// Show modal on click
	$( "body" ).on( "click", ".open-sri-modal", function( event ) {
		if ( event.ctrlKey || event.metaKey ) {
			return;
		}

		$( replace( modalTemplate, {
			link: $( this ).prop( "href" ),
			hash: $( this ).attr( "data-hash" )
		} ) ).removeAttr( "id" ).appendTo( "body" ).dialog( {
			modal: true,
			resizable: false,
			width: 830,
			dialogClass: "sri-modal",
			draggable: false,
			close: function() {
				$( this ).remove();
			}
		} );

		$('.sri-modal-copy-btn')
			.tooltip()
			.on( "click", function() {
				var buttonElem = $( this );
				clipboard
					.writeText( buttonElem.attr( "data-clipboard-text" ) )
					.then( function() {
						buttonElem
							.tooltip( "option", "content", "Copied!" )
							.one( "mouseout", function() {
								buttonElem.tooltip( "option", "content", "Copy to clipboard!" );
							} );
					} )
					.catch( function() {
						buttonElem
							.tooltip( "option", "content", "Copying to clipboard failed!" )
							.one( "mouseout", function() {
								buttonElem.tooltip( "option", "content", "Copy to clipboard!" );
							} );
					} );
			} );
		event.preventDefault();
	} );

	// Helper functions
	function replace ( string, values ) {
		return string.replace( /\{\{([^}]+)}}/g, function( _, key ) {
			return values[key];
		} );
	}
} );
