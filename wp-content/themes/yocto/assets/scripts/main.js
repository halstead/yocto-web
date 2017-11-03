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
      	 // JavaScript to be fired on all pages
      	
      	// Custom Mobile Navigation Scripts
      	jQuery(document).on("click", "nav.in .menu-item", function(e) {
			e.stopPropagation();
		});
		
		jQuery(document).on("click", "nav.in .menu-item-has-children", function(e) {
			e.preventDefault();
			e.stopPropagation();
		  	var currentElement = jQuery(this);
			var currentElementID = currentElement.attr('id');

			function animateDropDown(currentElement){
		  	  	if(currentElement.hasClass('open-menu') && currentElement.hasClass('menu-item-has-children')){
				  	currentElement.removeClass('open-menu');
			 	}else if(currentElement.hasClass('menu-item-has-children')){
				  	currentElement.addClass('open-menu');
					var activeDropdown = currentElement.find('.dropdown-menu');
					activeDropdown.css('position', 'relative');
					jQuery('div.header-position-container').css('position', 'relative');
					jQuery('body').css('padding-top', '0px');
		  	  		activeDropdown.first().stop(true, true).slideDown(200); //.delay(250)
		  	  	}else{
		  	  		location.href = this.href;
		  	  	}
			}
			jQuery('.dropdown-menu').stop(true, false).slideUp(200).promise().always(function(){
				jQuery('.dropdown-menu').css('position', 'absolute');
				jQuery('div.header-position-container').css('position', 'fixed');
				jQuery('body').css('padding-top', '119px');
				jQuery("nav.in .menu-item-has-children").each(function( index ) {
					if(jQuery(this).attr('id') !== currentElementID){
						jQuery(this).removeClass('open-menu');
					}
				});
			    animateDropDown(currentElement);
			});
	
		});


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
		            	jQuery.colorbox.resize({width:'85%', height:'90%'});
		            }
		    }, 300);
		}
		
		// Custom Block Ajax
		
		function modaleContent(postID, items , thisElement){
			console.log(items);
			jQuery.ajax({
		          url: '/wp-admin/admin-ajax.php',
		          data:{
		               'action':'do_ajax',
		               'dropDown': items,
		               'postID': postID
		               },
		          dataType: 'JSON',
		          success:function(data){
				  		console.log(data);
				  		$('#modal-colorbox-container').empty();
						$('#modal-colorbox-container').html(data);					
						$.colorbox({width:'450px', maxWidth:'90%', inline:true, href:"#bk-ajax-container"});

		          },
		          error: function(errorThrown){
		          	   
		               alert('Error Retrieving Request');
		               console.log(errorThrown);  
		          }
			 });	
		}
		
		$(document.body).on('click', ".open-colorbox",function (e) {
	   	  e.preventDefault();
	   	  $this = $(this);
	   	  $postID = $this.attr('id');
		  modaleContent($postID, 'all', $this);
		});


		// Resize Colorbox when resizing window or changing mobile device orientation
		//jQuery(window).resize(resizeColorBox);
		//window.addEventListener("orientationchange", resizeColorBox, false);
		
		// Block Builder Scripts
      
      	//$('.error').on('click', function(e) {
		jQuery(document.body).on('click',".error",function (e) {
			e.preventDefault();
			var targetID = $(this).attr("id");
			alert(targetID);
		});
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
      
      
      
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
        // HOME NAVIGATION

		//jQuery(function($) {
			//if ( $( ".home" ).length ) {
				var navVisible = false;
				var navActive = false;
				$(window).scroll(function() {
					  var $element = $("body, html");
					  var windowOffset = Math.abs(parseInt($element.offset().top) - parseInt($(window).scrollTop()));
					  var sliderHeight = $('.carousel-inner').outerHeight();
					  var headerHeight = $('.banner').outerHeight();
					  var navigationOffset = parseInt(sliderHeight) - parseInt(headerHeight);
					  //console.log(navigationOffset);
					  //console.log(windowOffset);
					  if(windowOffset > 40) {  //navigationOffset
					    
					    $('.banner').css('background-color', '#2b3034');
					    $('ul.dropdown-menu').css('background-color', '#2b3034');
						navVisible = true;
					  }else{
					  	if(navActive === false){
					  		$('.banner').css('background-color', 'transparent');
					    	$('ul.dropdown-menu').css('background-color', 'transparent');
							navVisible = false;
					  	}
					  } 
				});
				
				$( ".banner" ).hover(
				  function() {
				  	navActive = true;
				    $('.banner').css('background-color', '#2b3034');
				    $('ul.dropdown-menu').css('background-color', '#2b3034');
				   
				  }, function() {
				  	navActive = false;
					if(navVisible === false){
				  		$('.banner').css('background-color', 'transparent');
				  	    $('ul.dropdown-menu').css('background-color', 'transparent');
					}
				  }
				);

					
			//}
		//});
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
    },
    // docs
    'docs': {
      init: function() {
      	console.log('docs page');
        // JavaScript to be fired on the docs page
        // docs dropdown 
	
		var url = '/wp-content/themes/yocto/proxy.php';
		var downloadsURL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=59';
		var docsURL = 'https://www.yoctoproject.org/documentation-api';
		
		var docsObjectArray = [];
		var docsToolObjectArray = [];
		var docsDevelopmentObjectArray = [];
		var docsReferenceObjectArray = [];
		var docsQuickStartObjectArray = [];
		var docsSDKObjectArray = [];
		var docsNoneObjectArray = [];
		
		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			
			selectHtml += '<select id="releaseSelect" name="release-select" class="header-select">';
			jQuery.each(tableData.nodes, function(i, obj) {
				var showAmount = 4;
				if(i < showAmount){
					selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + '</option>';
				}
			});
			
			selectHtml += '</select>';
			jQuery("#dropdownContainer").html(selectHtml);
		}
		
		jQuery.getJSON( url, { csurl: downloadsURL}, function(data){
			buildDropdown(data);
		});

		
		jQuery('#dropdownContainer').on('change', 'select#releaseSelect', function(){
			var releaseNumber = jQuery(this).find(':selected').data('release-number');
			releaseNumber = (releaseNumber + ' ').trim();
			var documentClass = 'ver-' + releaseNumber.split('.').join('-');
			jQuery( ".dynamic-documents" ).hide();
			jQuery( "." + documentClass).show();
			
			//change new section docs		
			var docsSDKObjectArrayLength = docsSDKObjectArray.length;
			var docsQuickStartObjectArrayLength = docsQuickStartObjectArray.length;
			
			for (var i = 0; i < docsSDKObjectArrayLength; i++) {
				if(docsSDKObjectArray[i].docVersion === releaseNumber) {
					jQuery('.featured-doc-blocks').find('.custom-block').last().find('a').attr( "href", docsSDKObjectArray[i].docHTMLFile);
					jQuery('.featured-doc-blocks').find('.custom-block').last().find('.grid-block-copy h6').text(docsSDKObjectArray[i].docTitle);
				}
			}
			
			for (var j = 0; j < docsQuickStartObjectArrayLength; j++) {
				if(docsSDKObjectArray[i].docVersion === releaseNumber) {
		   			jQuery('.featured-doc-blocks').find('.custom-block').first().find('a').attr( "href", docsQuickStartObjectArray[j].docHTMLFile );
		   		 	jQuery('.featured-doc-blocks').find('.custom-block').first().find('.grid-block-copy h6').text(docsQuickStartObjectArray[j].docTitle);
				}
			}
	
		});
		
		
		// docs search
		
	    var cx = '010276533680706855010:dr7y7wxxktw';
	    var gcse = document.createElement('script');
	    gcse.type = 'text/javascript';
	    gcse.async = true;
	    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(gcse, s);
		
		
		// docs sections
		
		function buildSections(tableData){		
			var docsHtml = '';
			var linkHtml = '';
			var hasResults = false;
			
			jQuery.each(tableData.nodes, function(i, obj) {
				var showAmount = 100;
				if(i < showAmount){
		
					var documentVersionArray = obj.node.term_node_tid.split(' ');
					var documentVersion = documentVersionArray[2];
					var documentClass = 'ver-' + documentVersion.split('.').join('-');
					var documentCategory = obj.node.field_section.trim(); 
					var docItem = {docTitle:obj.node.title, docBody:obj.node.body, docHTMLFile:obj.node.HTML, docVersion:documentVersion, docClass:documentClass, docCategory:documentCategory};
					docsObjectArray[i] = docItem;
					documentCategory = docsObjectArray[i].docCategory.replace(/\\n/g, ' ');
	
					switch(documentCategory) {
					    case "Tool":
					        docsToolObjectArray.push(docItem);
					        break;
					    case "Development":
					        docsDevelopmentObjectArray.push(docItem);
					        break;
						case "Reference":
					        docsReferenceObjectArray.push(docItem);
					        break;
						case "Development New User":
					        docsSDKObjectArray.push(docItem);
					        break;
						case "New User":
					        docsQuickStartObjectArray.push(docItem);
					        break;
					    default:
					        docsNoneObjectArray.push(docItem);
					}
				}
			});
			
			function buildSection(sectionArray, sectionContainerID){
				var docsHtml = "";
				var sectionArrayLength = sectionArray.length;
				for (var i = 0; i < sectionArrayLength; i++) {
					docsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents dynamic-documents custom-block ' + sectionArray[i].docCategory + ' ' + sectionArray[i].docClass + '">';
					docsHtml += '	<a href="' + sectionArray[i].docHTMLFile + '" class="inline-block full-width" target="_blank">';		
					docsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
					docsHtml += '			<div class="grid-block-copy">';
					docsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';
					docsHtml += '				<p>' + sectionArray[i].docBody + '</p>';			
					docsHtml += '			</div>';		
					docsHtml += '		</div>';
					docsHtml += '	</a>';
					docsHtml += '</div>';
				}
				jQuery("#" + sectionContainerID).html(docsHtml);
			}
			
			
			function buildUpperSection(sectionArray, section){  //, sectionContainerID
				var sectionArrayLength = sectionArray.length;
				for (var i = 0; i < sectionArrayLength; i++) {
					if(i === 0){
						if(section === 'quickstart'){
							jQuery('.featured-doc-blocks').find('.custom-block').first().find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('.custom-block').first().find('.grid-block-copy h6').text(sectionArray[i].docTitle);
						}else if(section === 'sdk'){
							jQuery('.featured-doc-blocks').find('.custom-block').last().find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('.custom-block').last().find('.grid-block-copy h6').text(sectionArray[i].docTitle);
						}
					}
				}
			}
			
			buildSection(docsToolObjectArray, 'docsToolContainer');
			buildSection(docsDevelopmentObjectArray, 'docsDevelopmentContainer');
			buildSection(docsReferenceObjectArray, 'docsReferenceContainer');
			buildSection(docsNoneObjectArray, 'docsNoneContainer');
			
			buildUpperSection(docsQuickStartObjectArray, 'quickstart');
			buildUpperSection(docsSDKObjectArray, 'sdk');
		}
		
		jQuery.getJSON( url, { csurl: docsURL}, function(data){ //, dataType: "json"
			buildSections(data);
		});
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
        window.onload = function(){
	
			// Customize Google Search
			
			jQuery('input.gsc-input').attr('placeholder', 'Search Documents');
			jQuery('input.gsc-input').css('background-image', 'none');
			
			jQuery('input.gsc-input').blur(function() {
		  		jQuery(this).attr('placeholder', 'Search Documents');
		  		jQuery(this).css('background-image', 'none');
			});
			
			jQuery('.gsc-search-box').submit(function( event ) {
				alert('submit');
				jQuery('input.gsc-input').attr('placeholder', 'Search Documents');
				jQuery('input.gsc-input').css('background-image', 'none');
			});
			
			// Docs Section Navigartion
			
			var getUrlParameter = function getUrlParameter(sParam) {
			    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			        sURLVariables = sPageURL.split('&'),
			        sParameterName,
			        i;
		
			    for (i = 0; i < sURLVariables.length; i++) {
			        sParameterName = sURLVariables[i].split('=');
		
			        if (sParameterName[0] === sParam) {
			            return sParameterName[1] === undefined ? true : sParameterName[1];
			        }
			    }
			};
				
			function animateBody(section, offset) {	
				var currentSection = jQuery('.' + section);
				var headerOffset = jQuery('.header-position-container').outerHeight();
			
				console.log(headerOffset);
				var scrollLocation = currentSection.offset().top - (headerOffset + offset);
				jQuery("html, body").delay(800).animate({scrollTop: scrollLocation }, 'slow');
			}
			
			var searchTerm = getUrlParameter('section');
				
			if(searchTerm !== undefined){
				//console.log('kickit!');
				if(searchTerm === 'featured-doc-blocks'){
					animateBody(searchTerm, 45);	
				}else{
					animateBody(searchTerm, 0);	
				}
			}
		};
      }
    },
    // docs archive
    'archived-documents': {
      init: function() {
      	console.log('docs archive page');
        // JavaScript to be fired on the docs archive page
        
        // docs dropdown 
	
		var url = '/wp-content/themes/yocto/proxy.php';
		var downloadsURL = 'http://api-v1.yoctoproject.org/api/downloads';

		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			
			selectHtml += '<select id="releaseSelect" name="release-select" class="header-select">';
			jQuery.each(tableData.nodes, function(i, obj) {
				var showAmount = 300;
				if(i < showAmount){
					selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + '</option>';
				}
			});
			
			selectHtml += '</select>';
			jQuery("#dropdownContainer").html(selectHtml);
		}
		
		jQuery.getJSON( url, { csurl: downloadsURL}, function(data){
			buildDropdown(data);
		});
		
		jQuery('#dropdownContainer').on('change', 'select#releaseSelect', function(){
			var releaseNumber = jQuery(this).find(':selected').data('release-number');
			//alert(releaseNumber);
			releaseNumber = releaseNumber + ' ';
			var documentClass = 'ver-' + releaseNumber.split('.').join('-').trim();
			jQuery( ".dynamic-documents" ).hide();
			jQuery( "." + documentClass).show();
		});
		
	
		//var url = '/wp-content/themes/yocto/proxy.php';
		var docsURL = 'http://api-v1.yoctoproject.org/documentation-api/archived';
		
		var docsObjectArray = [];
		var docsToolObjectArray = [];
		var docsDevelopmentObjectArray = [];
		var docsReferenceObjectArray = [];
		var docsQuickStartObjectArray = [];
		var docsSDKObjectArray = [];
		var docsNoneObjectArray = [];
		var docsClassArray = [];
		var currentReleaseClass = "";
		

		function buildSections(tableData){		
			var docsHtml = '';
			var linkHtml = '';
			var hasResults = false;
			
			jQuery.each(tableData.nodes, function(i, obj) {
				// var showAmount = 200;
	 			if(obj.node.term_node_tid !== undefined && obj.node.term_node_tid !== ''){
		
					var documentVersionArray = obj.node.term_node_tid.split(' ');
					var documentVersion = documentVersionArray[2];
					console.log("ver: "  + documentVersion);
					var documentClass = 'ver-' + documentVersion.split('.').join('-');
					var documentCategory = obj.node.field_section.trim(); 
					var docItem = {docTitle:obj.node.title, docBody:obj.node.body, docHTMLFile:obj.node.HTML, docVersion:documentVersion, docClass:documentClass, docCategory:documentCategory};
					docsObjectArray[i] = docItem;
					documentCategory = docsObjectArray[i].docCategory.replace(/\\n/g, ' ');
	
					docsClassArray.push(documentClass);
	
					switch(documentCategory) {
					    case "Tool":
					        docsToolObjectArray.push(docItem);
					        break;
					    case "Development":
					        docsDevelopmentObjectArray.push(docItem);
					        break;
						case "Reference":
					        docsReferenceObjectArray.push(docItem);
					        break;
						case "Development New User":
					        docsDevelopmentObjectArray.push(docItem);
					        break;
						case "New User":
					        docsReferenceObjectArray.push(docItem);
					        break;
					    default:
					        docsNoneObjectArray.push(docItem);
					}
				}
			});
			

			function buildSection(sectionArray, sectionContainerID){
				var docsHtml = "";
				var sectionArrayLength = sectionArray.length;
				for (var i = 0; i < sectionArrayLength; i++) {
					docsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents dynamic-documents custom-block ' + sectionArray[i].docCategory + ' ' + sectionArray[i].docClass + '">';
					docsHtml += '	<a href="' + sectionArray[i].docHTMLFile + '" class="inline-block full-width" target="_blank">';		
					docsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
					docsHtml += '			<div class="grid-block-copy">';
					docsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';
					docsHtml += '				<p>' + sectionArray[i].docBody + '</p>';			
					docsHtml += '			</div>';		
					docsHtml += '		</div>';
					docsHtml += '	</a>';
					docsHtml += '</div>';
				}
				
				jQuery("#" + sectionContainerID).html(docsHtml);
			}
			
			
			var docsClassArrayLength = docsClassArray.length;
			for (var i = 0; i < docsClassArrayLength; i++) {
				jQuery('.' + docsClassArray[i]).css('display', 'none');
			}
			
			buildSection(docsToolObjectArray, 'docsToolContainer');
			buildSection(docsDevelopmentObjectArray, 'docsDevelopmentContainer');
			buildSection(docsReferenceObjectArray, 'docsReferenceContainer');
			buildSection(docsNoneObjectArray, 'docsNoneContainer');

			jQuery('.' + docsClassArray[0]).css('display', 'block');
		}
		
		jQuery.getJSON( url, { csurl: docsURL}, function(data){ //, dataType: "json"
			buildSections(data);
		});
      }
    },
    // downloads
    'downloads': {
      init: function() {
      	console.log('downloads page');
        // JavaScript to be fired on the downloadss page
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
