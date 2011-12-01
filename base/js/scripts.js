jQuery.InFieldLabels.defaultOptions.defaultOpacity = 1;
jQuery.InFieldLabels.defaultOptions.fadeOpacity    = 0.5;


// Executes on DOM ready
App.subscribe("init", function() {
  
  // Add Syntax Highlighting
  SyntaxHighlighter.all();
  
  // Add Search Interactions
  $("#search").inFieldLabels();
  
  // Project Select Show/Hide
  $(".toggle-projects").on( "click", function( e ) {
    e.preventDefault();

    var el = $( this ).toggleClass("active");

    $("body").animate({ "marginTop": ( el.hasClass("active") ? "150px" : "0" ) }, 300, function() {
      el.toggleClass("down");
    });
  });
  
  // Project Select Clickoutside
  $(".project-select").on( "clickoutside", function( e ) {
    var el = $(".toggle-projects");
    if ( e.target.parentNode === el[0] || e.target === el[0] ) {
      return;
    }

    if ( el.hasClass("down") ) {
      el.click();
    }
  });
  
  // Project Tooltips
  $(".projects").find(".jquery, .jquery-ui, .jquery-mobile").on( "mouseover", function() {
    var el = $( this ), 
      tooltips = $(".tooltips"), 
      tooltip = {},
      $tooltip;

    if ( el.hasClass("jquery") ) {
      tooltip = $(".tooltips .jquery");
    } 
    else if ( el.hasClass("jquery-ui") ) {
      tooltip = $(".tooltips .jquery-ui");
    } 
    else if ( el.hasClass("jquery-mobile") ) {
      tooltip = $(".tooltips .jquery-mobile");
    }

    $tooltip = $( ".tooltip:visible" ,tooltips );
    if ( $tooltip !== tooltip ) {
      clearTimeout( App.tooltip_timeout );
      $tooltip.fadeOut(200);
    }

    if ( tooltip.is(":hidden") ) {
      setTimeout(function() { tooltip.fadeIn(300); }, 300);
    }
  });	
  
  $(".tooltips").find(".jquery, .jquery-ui, .jquery-mobile").on("mouseout", function() {
    var el = $( this );
    App.tooltip_timeout = setTimeout(function() { el.fadeOut(200); }, 300);
  }).bind("mouseover", function() {
    clearTimeout( App.tooltip_timeout );
  });
  
  // Fancy Dropdown
  $(".links .dropdown").hover(function() {
    $( this ).children("ul").stop( true, true ).slideDown( 100 );
  }, function() {
    $( this ).children("ul").stop( true, true ).slideUp( 100 );
  });

/*
  // Temporary: REMOVE
  // Change page color
  var colors = [ "jquery", "jquery-ui", "jquery-mobile", "jquery-project" ],
    color_string = colors.join(" ");
  $("ul.projects").delegate("li:lt(3)", "click", function(e) {
    e.preventDefault();
    $(document.documentElement)
      .removeClass(color_string)
      .addClass(this.className);
    window.location.hash = this.className;
  });
*/
  
  if ( window.location.hash && $.inArray( window.location.hash.substr( 1 ), colors ) > -1 ) {
    $( document.documentElement )
      .removeClass( color_string )
      .addClass( window.location.hash.substr( 1 ) );
  }

  $(".presentations, .books").find("img").each(function ( i, el ) {
    var $img = $( this ).css( "visibility", "hidden" );
    $img.parent().css( "background-image", "url(" + $img.attr("src") + ")" );
  });
  
  $(".footer-icon-links")
    .find("li a")
      .append("<span></span>")
      .end()
    .delegate( "li a", "mouseenter", function () {
      $( this ).find("span").stop( true, false ).fadeTo( 250, 1.0 );
    })
    .delegate( "li a", "mouseleave", function () {
      $( this ).find("span").stop( true, false ).fadeOut( 250 );
    });
	
	autoHeight();
	
	$( window ).resize(autoHeight);
	
	function autoHeight() {
		$(".autoHeight").each(function() {
			var el = $( this ), 
        parent = el.parent();

			//console.log(el.height("auto").height());
			//console.log(el.height("auto").height() + " : " + parent.height());
			if ( parent.height() >= el.height("auto").height() ) {
				el.css({ "height": parent.height() });
			} 
      else {
				el.css({ "height": el.height("auto").height() });
			}
		});
	}
	
});

