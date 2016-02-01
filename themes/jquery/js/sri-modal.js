$(document).ready(function() {
	// Store modal templates
	var modalTemplate = $( "#sri-modal-template" )[ 0 ].outerHTML,
		clipboard;

	// Show modal on click
	$( "body" ).on( "click", ".open-sri-modal", function( event ) {
		if ( event.ctrlKey || event.metaKey ) {
			return;
		}

		$( replace( modalTemplate, {
			link: document.location.origin + $( this ).attr( "href" ),
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

		$('.sri-modal-copy-btn').tooltip();
		event.preventDefault();
	} );

	// Helper functions
	function replace ( string, values ) {
		return string.replace( /\{\{([^}]+)}}/g, function( _, key ) {
			return values[key];
		});
	}

	clipboard = new Clipboard( "[data-clipboard-text]" );

	clipboard.on( "success", function( e ) {
		$( e.trigger )
			.tooltip( "option", "content", "Copied!" )
			.one( "mouseout", function() {
				$( this ).tooltip( "option", "content", "Copy to clipboard!" );
			} );
	} );

	clipboard.on( "error", function( e ) {
		$( e.trigger )
			.tooltip( "option", "content", "Press Ctrl+C to copy!" )
			.one( "mouseout", function() {
				$( this ).tooltip( "option", "content", "Copy to clipboard!" );
			} );
	} );
});
