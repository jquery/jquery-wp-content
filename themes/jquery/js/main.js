$(document).ready(function() {

//Slide Form Open////////////////////////////////////////

$('li.toggle-projects').click(function() {$('#global-project-select').slideToggle(000);
return false;
});

//Join Page Sign-up /////////////////////////////////////
 $(document).ready(function() {
                  $('div.choose-gifts').hide();
                  $('.member-level> a.join').click(function() {
                      $(this).nextAll('div.choose-gifts').slideToggle(300).siblings('div.choose-gifts:visible').slideUp(700);
                      $(this).toggleClass("close");
                  });
              });

//Colorbox Enlarge//////////////
$(".enlarge").colorbox({rel:'goodies'});



<!-- Hook up the FlexSlider -->

	$(window).load(function() {
		$('.flexslider').flexslider({
		
		controlNav: "false"
		});
		
	});


// Filter lists on api sites if on home, category, or search results pages
if ( location.hostname.indexOf("api.") > -1 &&
     /\b(?:home|category|search-results)\b/.test( document.body.className ) ) {

  $("form.searchform").find( "input[type=text]" ).liveUpdate( "#body .inner" );

}

}); //Close Document Ready
