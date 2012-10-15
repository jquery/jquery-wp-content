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



}); //Close Document Ready
