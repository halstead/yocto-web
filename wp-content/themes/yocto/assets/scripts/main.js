/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
      	
      	

		jQuery(function($) {
			if ( $( ".home" ).length ) {
				var navVisible = false;
				$(window).scroll(function() {
					  var $element = $("body, html");
					  var windowOffset = Math.abs(parseInt($element.offset().top) - parseInt($(window).scrollTop()));
					  var sliderHeight = $('.carousel-inner').outerHeight();
					  var headerHeight = $('.banner').outerHeight();
					  var navigationOffset = parseInt(sliderHeight) - parseInt(headerHeight);
					  console.log(navigationOffset);
					  console.log(windowOffset);
					  if(windowOffset > navigationOffset) {
					    console.log("your mamma");
					    $('.banner').css('background-color', '#2b3034');
					    $('ul.dropdown-menu').css('background-color', '#2b3034');
						navVisible = true;
					  }else{
					    $('.banner').css('background-color', 'transparent');
					    $('ul.dropdown-menu').css('background-color', 'transparent');
						navVisible = false;
					  } 
				});
				
				$( ".banner" ).hover(
				  function() {
				    $('.banner').css('background-color', '#2b3034');
				    $('ul.dropdown-menu').css('background-color', '#2b3034');
				  }, function() {
					  if(navVisible === false){
				  	    $('.banner').css('background-color', 'transparent');
				  	    $('ul.dropdown-menu').css('background-color', 'transparent');
					  }
				  }
				);
					
			}
		});

 

        // JavaScript to be fired on all pages
        //jQuery(".media-item-image").colorbox({rel:'carousel-image', transition:"fade", maxWidth:'95%', maxHeight:'95%'});
        //jQuery(".media-item-video").colorbox({rel:'carousel-image', iframe:true, innerWidth:'80%', innerHeight:'80%', maxWidth:'95%', maxHeight:'95%'}); 
        //jQuery(".media-item-embed").colorbox({rel:'carousel-image', inline:true, maxWidth:'95%', maxHeight:'95%'});
        jQuery(".colorbox").colorbox();
        /* Colorbox resize function */
		var resizeTimer;
		function resizeColorBox() {
		    if (resizeTimer) {
		    	clearTimeout(resizeTimer); 
		    }
		    resizeTimer = setTimeout(function() {
		            if (jQuery('#cboxOverlay').is(':visible')) {
		            	jQuery.colorbox.resize({width:'90%', height:'90%'});
		            }
		    }, 300);
		}

		// Resize Colorbox when resizing window or changing mobile device orientation
		jQuery(window).resize(resizeColorBox);
		window.addEventListener("orientationchange", resizeColorBox, false);
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
