$(function() {

// Filter lists on api sites if on home, category, or search results pages
if ( location.hostname.indexOf("api.") > -1 &&
     /\b(?:home|category|search-results)\b/.test( document.body.className ) ) {

  $("form.searchform").find( "input[type=text]" ).liveUpdate( "#body .inner" );

}
});
