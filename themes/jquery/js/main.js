/*
 * All sites
 */
$(function() {
	// All projects top nav
	$(".toggle-projects").on( "click", function( event ) {
		$("#global-project-select").slideToggle();
		event.preventDefault();
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
		// Enlarged goodies
		$(".enlarge").colorbox();

		// Gift forms
		var gifts = $(".choose-gifts").hide();
		$(".member-level .join").on( "click", function() {
			var gift = $( this ).nextAll(".choose-gifts").slideToggle();
			gifts.not( gift ).slideUp();
		});
	})();
});





// Filter lists on api sites if on home, category, or search results pages
// if ( location.hostname.indexOf("api.") > -1 &&
// 		/\b(?:home|category|search-results)\b/.test( document.body.className ) ) {
// 	$("form.searchform").find( "input[type=text]" ).liveUpdate( "#body .inner" );
// }
