// Store modal templates
var modalTemplate = $( "#sri-modal-template" )[ 0 ].outerHTML,
	clipboard;

// Show modal on click
$( "body" ).on( "click", ".open-sri-modal", function( event ) {
	if ( !event.ctrlKey && !event.metaKey ) {
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

		return false;
	}
} );

// Helper functions
function replace ( string, values ) {
	$.each( values, function( key, value ) {
		string = string.replace( new RegExp( "\\{\\{" + key + "}}", "g" ), value );
	} );

	return string;
}

clipboard = new Clipboard( "[data-clipboard-text]" );

clipboard.on( "success", function( e ) {
	$( e.trigger )
		.blur()
		.attr( "data-hint", "Copied!" )
		.on( "mouseout", function() {
			$( this ).removeAttr( "data-hint" );
		} );
} );

clipboard.on( "error", function( e ) {
	$( e.trigger )
		.blur()
		.attr( "data-hint", "Press Ctrl+C to copy!" )
		.on( "mouseout", function() {
			$( this ).removeAttr( "data-hint" );
		} );
} );
