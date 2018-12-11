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
		
		
		// Home Page - Experienced Developers Section
		
		var url = '/wp-content/themes/yocto/proxy.php';
		var chart1URL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=59';

		
		function buildButtons(tableData){
			var versionHtml = '';
			var linkHtml = '';
			var hasResults = false;
			
			var releaseObjectArray = [];
			jQuery.each(tableData.nodes, function(i, obj) {
				var showAmount = 4;
				if(i < showAmount){
					var release = {releaseTitle:obj.node.title, releaseVersion:obj.node.field_release_number, releaseGitURL:obj.node.field_git_url,  releaseDownloadURL:obj.node.field_download_urls, releaseDate:obj.node.releasedate};
					releaseObjectArray[i] = release;
				}
			});
			
			var currentVersion = releaseObjectArray[0].releaseTitle;
			var currentGetURL = releaseObjectArray[0].releaseGitURL;
			var currentDownloadURL = releaseObjectArray[0].releaseDownloadURL;
			var currentReleaseDate = releaseObjectArray[0].releaseDate;
	
			var currentReleaseDateArray = currentReleaseDate.split(" ");
			currentReleaseDate =  currentReleaseDateArray[0].replace(/-/g, '.');
			
			jQuery("#version-date").html(currentReleaseDate);
			jQuery("#version-title").html(currentVersion);
			jQuery("#version-link").html(linkHtml);
		} 
		
		jQuery.getJSON( url, { csurl: chart1URL}, function(data){ //, dataType: "json"
			//alert(data);
			buildButtons(data);
		});
		
		
		// Dynamic Activity Table
		
  		var sourceURLBugs = 'https://wiki.yoctoproject.org/charts/attribution.json.txt';   
		var sourceURLCommits = 'http://api-v1.yoctoproject.org/commit_plus_cve.json.txt';  
		var sourceURLCVE = 'http://api-v1.yoctoproject.org/cves.json.txt';
		
		var CommitsTableHasResults = false;
		var CVETableHasResults = false;
 		var BugsTableHasResults = false;

		
		function buildCommitsTable(tableData){
			var layerTableCommitsHtml = '';
			var layerTableCVEHtml = '';
			//var CVEcounter = 0;
			
			jQuery.each(tableData, function(i, obj) {
				var showAmount = 5;
				if(i < showAmount){
					
  				 var myArr = [[obj.when, moment().format()]];
				 var date = moment(myArr[0][0]);
				 var newDate = date.clone().subtract(7, 'hours');
				  newDate = newDate.fromNow();

	  			  layerTableCommitsHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
				  layerTableCommitsHtml += '	<div class="col-xs-12 col-sm-2">' + newDate + '</div>'; 
	  			  //layerTableHtml += '	<div class="col-xs-12 col-sm-2">' + obj.committer + '</div>'; //obj.datetime   + i + 
	  			  layerTableCommitsHtml += '	<div class="col-xs-12 col-sm-6"><p><img src="/wp-content/uploads/2017/07/hexagon-blue.png" class="hexagon"/><a href="' + obj.link + '" target="_blank">' + obj.summary + '</a></p></div>';
	  			  layerTableCommitsHtml += '	<div class="col-xs-12 col-sm-2"><p><a target="_blank" href="' + obj.link + '">' + obj.repo + '</a></p></div>';
	  			  //layerTableCommitsHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj.author + '</p></div>';
	  			  layerTableCommitsHtml += '</div>';
				}
			});

			if(CommitsTableHasResults === false){
			 layerTableCommitsHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Activity Results for Bugs</div>';
			}
			jQuery('#commitsContainer').html(layerTableCommitsHtml);
		}
		
		
		function buildCVETable(tableData){
			var layerTableCVEHtml = '';
			jQuery.each(tableData, function(i, obj) {
				var showAmount = 5;
				if(i < showAmount){
	  			  var priorityClass = obj.priority;

				  var myArr = [[obj.when, moment().format()]];
				  var date = moment(myArr[0][0]);

                  var newDate = date.clone().subtract(7, 'hours');
				  newDate = newDate.fromNow();
				  
  				  var dynamicUrl = obj.link; //jQuery(this).find('a:first').attr('href');
				  var urlPartsArray = dynamicUrl.split('/');
				  var repo = urlPartsArray[5];

	  			  layerTableCVEHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
	  			  layerTableCVEHtml += '	<div class="col-xs-12 col-sm-2">' + newDate + '</div>'; //obj.datetime   + i +
	  			  layerTableCVEHtml += '	<div class="col-xs-12 col-sm-6"><p><img src="/wp-content/uploads/2017/07/hexagon-red-1.png" class="hexagon"/><a href="' + obj.nist + '" target="_blank">' + obj.CVE + '</a></p></div>';
				  //layerTableCVEHtml += '	<div class="col-xs-12 col-sm-6"><p><img src="/wp-content/uploads/2017/07/hexagon-red-1.png" class="hexagon"/>' + obj.CVE + ' | ' + '<a href="' + obj.nist + '" target="_blank">' + obj.nist + '</a></p></div>';
	  			  layerTableCVEHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj.status + '</p></div>';
	  			  layerTableCVEHtml += '	<div class="col-xs-12 col-sm-2"><p><a target="_blank" href="' + obj.link + '">' + repo + '</a></p></div>';
	  			  layerTableCVEHtml += '</div>';
				}
			  
			});

			if(CVETableHasResults === false){
			 layerTableCVEHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Activity Results for CVE </div>';
			}
			jQuery('#cveContainer').html(layerTableCVEHtml);
		}
		
		
		function buildBugsTable(tableData){
			var layerTableHtml = '';
			jQuery.each(tableData, function(i, obj) {
				var showAmount = 5;
				if(i < showAmount){
	  			  var priorityClass = obj.priority; 
				  
				  var myArr = [[obj.datetime, moment().format()]];   //"2017-09-18T08:15:17", obj.datetime
				  var date = moment(myArr[0][0]);
				  
				  var newDate = date.clone().subtract(8, 'hours');
				  newDate = newDate.fromNow();
			  
	  			  if (priorityClass.substring(priorityClass.length-1) === "+") {
  			          priorityClass = priorityClass.substring(0, priorityClass.length-1);
  			      }
				
	  			  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
	  			  layerTableHtml += '	<div class="col-xs-12 col-sm-2">' + newDate + '</div>'; //obj.datetime   + i + 
	  			  layerTableHtml += '	<div class="col-xs-12 col-sm-6"><p><img src="/wp-content/uploads/2017/07/hexagon-yellow.png" class="hexagon"/><a href="' + obj.link + '" target="_blank">' + obj.description + '</a> <span class="priority ' + priorityClass + '">' + obj.priority + '</span></p></div>';
	  			  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj.status + '</p></div>';
	  			  /* layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj.assignee + '</p></div>'; */
	  			  layerTableHtml += '</div>';
				}
			  
			});

			if(BugsTableHasResults === false){
			 layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Activity Results for Bugs</div>';
			}
			jQuery('#bugsContainer').html(layerTableHtml);
		}
		

 		jQuery.getJSON( url, { csurl: sourceURLCommits, dataType: "json"}, function(data){
			//alert(data)
			CommitsTableHasResults = true;
			buildCommitsTable(data);
			
 		});
 		jQuery.getJSON( url, { csurl: sourceURLCVE, dataType: "json"}, function(data){
			//alert(data)
			CVETableHasResults = true;
			buildCVETable(data);

 		});
 		jQuery.getJSON( url, { csurl: sourceURLBugs, dataType: "json"}, function(data){
			//alert(data)
			BugsTableHasResults = true;
			buildBugsTable(data);
 		});
		
		
		jQuery('.activity-table-tab').click(function(e) {
			e.preventDefault();
			var currentID = jQuery(this).attr('id');
			jQuery('.activity-table-tab').removeClass('active');
			jQuery('.activity-table').addClass('hide');
			jQuery(this).addClass('active');
			jQuery('#' + currentID + '-container').removeClass('hide');
			//alert(currentID);
		});


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
		var docsURL = 'http://api-v1.yoctoproject.org/documentation-api';
		
		var docsObjectArray = [];
		var docsToolObjectArray = [];
		var docsDevelopmentObjectArray = [];
		var docsReferenceObjectArray = [];
		var docsQuickStartObjectArray = [];
		var docsSDKObjectArray = [];
		var docsNoneObjectArray = [];
		var releaseCurrentVersion = '';
		var initialVersionClass = '';
		
		function initialDocsSetup() {
			releaseCurrentVersion = (releaseCurrentVersion + ' ').trim();
			//var documentClass = 'ver-' + releaseCurrentVersion.split('.').join('-');
		}
		
		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			selectHtml += '<select id="releaseSelect" name="release-select" class="header-select">';
			jQuery.each(tableData.nodes, function(i, obj) {
				if(i === 0) {
					releaseCurrentVersion = obj.node.field_release_number;
					var versionClassClean = (releaseCurrentVersion + ' ').trim();
					initialVersionClass = 'ver-' + versionClassClean.split('.').join('-');
				}
				selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + '</option>';
			});
			selectHtml += '</select>';
			jQuery("#dropdownContainer").html(selectHtml);
		}
		

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
					jQuery('.featured-doc-blocks').find('.post-305').find('a').attr("href", docsSDKObjectArray[i].docHTMLFile);
					jQuery('.featured-doc-blocks').find('.post-305').find('.grid-block-copy h6').text(docsSDKObjectArray[i].docTitle);
				}
			}

			for (var j = 0; j < docsQuickStartObjectArrayLength; j++) {
				if(docsQuickStartObjectArray[j].docVersion === releaseNumber) {
		   			jQuery('.featured-doc-blocks').find('.post-296').find('a').attr("href", docsQuickStartObjectArray[j].docHTMLFile);
		   		 	jQuery('.featured-doc-blocks').find('.post-296').find('.grid-block-copy h6').text(docsQuickStartObjectArray[j].docTitle);
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
					
					//documentCategory = docsObjectArray[i].docCategory.replace(/\\n/g, ' '); // if used in block builder
					documentCategory = docsObjectArray[i].docCategory.replace(/\n/g, ' ');

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
					if(sectionArray[i].docClass === initialVersionClass){
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
					}else{
						docsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents dynamic-documents custom-block ' + sectionArray[i].docCategory + ' ' + sectionArray[i].docClass + '" style="display:none;">';
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
				}
				jQuery("#" + sectionContainerID).html(docsHtml);
			}
			
			
			function buildUpperSection(sectionArray, section){
				var sectionArrayLength = sectionArray.length;
				for (var i = 0; i < sectionArrayLength; i++) {
					if(sectionArray[i].docVersion === releaseCurrentVersion){ 
						if(section === 'quickstart'){
							jQuery('.featured-doc-blocks').find('.post-296').find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('.post-296').find('.grid-block-copy h6').text(sectionArray[i].docTitle);
						}else if(section === 'sdk'){
							jQuery('.featured-doc-blocks').find('.post-305').find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('.post-305').find('.grid-block-copy h6').text(sectionArray[i].docTitle);
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
			initialDocsSetup();
	
		}
		
		function buildDocsSections() {
			jQuery.getJSON( url, { csurl: docsURL}, function(data){ //, dataType: "json"
				buildSections(data);
			});
		}
		
		jQuery.getJSON( url, { csurl: downloadsURL}, function(data){
			buildDropdown(data);
			buildDocsSections();
		});
		
		
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
        window.onload = function(){
	
			// Customize Google Search
			
			jQuery('input.gsc-input').attr('placeholder', 'Search Documents');
			jQuery('input.gsc-input').css('background-image', 'none');
			jQuery('.gsc-search-box ').css('display', 'block');
			
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
				if(searchTerm === 'featured-doc-blocks'){
					animateBody(searchTerm, 45);	
				}else{
					animateBody(searchTerm, 0);	
				}
			}
		};
      }
    },
    'documentation': {
      init: function() {
      	console.log('docs page');
        // JavaScript to be fired on the docs page
        // docs dropdown 
	
		var url = '/wp-content/themes/yocto/proxy.php';
		var downloadsURL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=59';
		var docsURL = 'http://api-v1.yoctoproject.org/documentation-api';
		
		var docsObjectArray = [];
		var docsToolObjectArray = [];
		var docsDevelopmentObjectArray = [];
		var docsReferenceObjectArray = [];
		var docsQuickStartObjectArray = [];
		var docsSDKObjectArray = [];
		var docsNoneObjectArray = [];
		var releaseCurrentVersion = '';
		var initialVersionClass = '';
		
		function initialDocsSetup() {
			releaseCurrentVersion = (releaseCurrentVersion + ' ').trim();
			//var documentClass = 'ver-' + releaseCurrentVersion.split('.').join('-');
		}
		
		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			selectHtml += '<select id="releaseSelect" name="release-select" class="header-select">';
			jQuery.each(tableData.nodes, function(i, obj) {
				if(i === 0) {
					releaseCurrentVersion = obj.node.field_release_number;
					var versionClassClean = (releaseCurrentVersion + ' ').trim();
					initialVersionClass = 'ver-' + versionClassClean.split('.').join('-');
				}
				selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + '</option>';
			});
			selectHtml += '</select>';
			jQuery("#dropdownContainer").html(selectHtml);
		}
		

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
					jQuery('.featured-doc-blocks').find('.post-305').find('a').attr( "href", docsSDKObjectArray[i].docHTMLFile);
					jQuery('.featured-doc-blocks').find('.post-305').find('.grid-block-copy h6').text(docsSDKObjectArray[i].docTitle);
				}
			}

			for (var j = 0; j < docsQuickStartObjectArrayLength; j++) {
				if(docsQuickStartObjectArray[j].docVersion === releaseNumber) {
		   			jQuery('.featured-doc-blocks').find('.post-296').find('a').attr( "href", docsQuickStartObjectArray[j].docHTMLFile );
		   		 	jQuery('.featured-doc-blocks').find('.post-296').find('.grid-block-copy h6').text(docsQuickStartObjectArray[j].docTitle);
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
					
					//documentCategory = docsObjectArray[i].docCategory.replace(/\\n/g, ' '); // if used in block builder
					documentCategory = docsObjectArray[i].docCategory.replace(/\n/g, ' ');

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
					if(sectionArray[i].docClass === initialVersionClass){
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
					}else{
						docsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents dynamic-documents custom-block ' + sectionArray[i].docCategory + ' ' + sectionArray[i].docClass + '" style="display:none;">';
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
				}
				jQuery("#" + sectionContainerID).html(docsHtml);
			}
			
			
			function buildUpperSection(sectionArray, section){
				var sectionArrayLength = sectionArray.length;
				for (var i = 0; i < sectionArrayLength; i++) {
					if(sectionArray[i].docVersion === releaseCurrentVersion){ 
						if(section === 'quickstart'){
							jQuery('.featured-doc-blocks').find('.post-296').find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('.post-296').find('.grid-block-copy h6').text(sectionArray[i].docTitle);
						}else if(section === 'sdk'){
							jQuery('.featured-doc-blocks').find('.post-305').find('a').attr( "href", sectionArray[i].docHTMLFile );
							jQuery('.featured-doc-blocks').find('post-305').find('.grid-block-copy h6').text(sectionArray[i].docTitle);
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
			initialDocsSetup();
	
		}
		
		function buildDocsSections() {
			jQuery.getJSON( url, { csurl: docsURL}, function(data){ //, dataType: "json"
				buildSections(data);
			});
		}
		
		jQuery.getJSON( url, { csurl: downloadsURL}, function(data){
			buildDropdown(data);
			buildDocsSections();
		});
		
		
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
        window.onload = function(){
	
			// Customize Google Search
			
			jQuery('input.gsc-input').attr('placeholder', 'Search Documents');
			jQuery('input.gsc-input').css('background-image', 'none');
			jQuery('.gsc-search-box ').css('display', 'block');
			
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
    'archived_documents': {
      init: function() {
      	console.log('docs archive page');
        // JavaScript to be fired on the docs archive page
        
        var url = '/wp-content/themes/yocto/proxy.php';	
		var downloadsURL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=57';	
		var docsURL = 'http://api-v1.yoctoproject.org/documentation-api/archived';
		
		var docsObjectArray = [];
		var docsToolObjectArray = [];
		var docsDevelopmentObjectArray = [];
		var docsReferenceObjectArray = [];
		var docsQuickStartObjectArray = [];
		var docsSDKObjectArray = [];
		var docsNoneObjectArray = [];
		var docsClassArray = [];
		var releaseCurrentVersion = '';
		var initialVersionClass = '';
		
		function initialDocsSetup() {
			// initial config
			releaseCurrentVersion = (releaseCurrentVersion + ' ').trim();
			
		}
		

		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			
			selectHtml += '<select id="releaseSelect" name="release-select" class="header-select">';
			jQuery.each(tableData.nodes, function(i, obj) {
				if(i === 0) {
					releaseCurrentVersion = obj.node.field_release_number;
					var versionClassClean = (releaseCurrentVersion + ' ').trim();
					initialVersionClass = 'ver-' + versionClassClean.split('.').join('-');
					//console.log("Set intial Class: " + initialVersionClass);
				}
				selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + '</option>';	
			});
			selectHtml += '</select>';
			jQuery("#dropdownContainer").html(selectHtml);
		}
		
		
		jQuery('#dropdownContainer').on('change', 'select#releaseSelect', function(){
			var releaseNumber = jQuery(this).find(':selected').data('release-number');
			releaseNumber = releaseNumber + ' ';
			var documentClass = 'ver-' + releaseNumber.split('.').join('-').trim();
			jQuery( ".dynamic-documents" ).hide();
			jQuery( "." + documentClass).show();
		});
		

		function buildSections(tableData){		
			var docsHtml = '';
			var linkHtml = '';
			var hasResults = false;
			
			jQuery.each(tableData.nodes, function(i, obj) {
	 			if(obj.node.term_node_tid !== undefined && obj.node.term_node_tid !== ''){
		
					var documentVersionArray = obj.node.term_node_tid.split(' ');
					var documentVersion = documentVersionArray[2];
					console.log("ver: "  + documentVersion);
					var documentClass = 'ver-' + documentVersion.split('.').join('-');
					var documentCategory = obj.node.field_section.trim(); 
					
					var docItem = {docTitle:obj.node.title, docBody:obj.node.body, docHTMLFile:obj.node.HTML, docVersion:documentVersion, docClass:documentClass, docCategory:documentCategory};
					docsObjectArray[i] = docItem;
					
					//documentCategory = docsObjectArray[i].docCategory.replace(/\\n/g, ' '); // if used in block builder
					documentCategory = docsObjectArray[i].docCategory.replace(/\n/g, ' '); // if used in block theme
					
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
				//console.log('init Class: ' + initialVersionClass);
				for (var i = 0; i < sectionArrayLength; i++) {
					if(sectionArray[i].docClass === initialVersionClass){
						console.log('match: ' + sectionArray[i].docClass);
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
					}else{
						console.log('no match: ' + sectionArray[i].docClass);
						docsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents dynamic-documents custom-block ' + sectionArray[i].docCategory + ' ' + sectionArray[i].docClass + '" style="display:none;">';
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
				}
				jQuery("#" + sectionContainerID).html(docsHtml);
			}
			
			
			buildSection(docsToolObjectArray, 'docsToolContainer');
			buildSection(docsDevelopmentObjectArray, 'docsDevelopmentContainer');
			buildSection(docsReferenceObjectArray, 'docsReferenceContainer');
			buildSection(docsNoneObjectArray, 'docsNoneContainer');
			initialDocsSetup();
		}
		
		function buildDocsSections() {
			jQuery.getJSON( url, { csurl: docsURL}, function(data){ //, dataType: "json"
				buildSections(data);
			});
		}

		
		jQuery.getJSON( url, { csurl: downloadsURL}, function(data) {
			buildDropdown(data);
			buildDocsSections();
		});
		
		
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // downloads
    'downloads': {
      init: function() {
      	console.log('downloads page');
        // JavaScript to be fired on the downloadss page
        new Clipboard('.btn');

 		var url = '/wp-content/themes/yocto/proxy.php';
  		var releaseURL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=59';
		
		var releaseObjectArray = [];
		var releaseCurrentVersion = '';
		var initialVersionClass = '';
		
		function initialToolsSetup() {
			// initial config
			releaseCurrentVersion = (releaseCurrentVersion + ' ').trim();
		}

		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			
			selectHtml += '<h2 style="display:inline-block;" class="block-title">Release</h2><select id="releaseSelect" name="release-select" class="header-select white-background" style="font-size:18px; height:36px; margin-left:0px; display:inline-block;">';
			jQuery.each(tableData.nodes, function(i, obj) {
				if(i === 0) {
					releaseCurrentVersion = obj.node.field_release_number;
					var versionClassClean = (releaseCurrentVersion + ' ').trim();
					initialVersionClass = 'ver-' + versionClassClean.split('.').join('-');
					//console.log("Set intial Class: " + initialVersionClass);
				}
	            var releaseNumber = obj.node.field_release_number + ' '.trim();
				var releaseNumberClass = 'ver-' + releaseNumber.split('.').join('-');
				var releaseInfoCombined = obj.node.field_errata + obj.node.field_security_fixes + obj.node.field_features + obj.node.field_package_updates;
				
				var release = {releaseTitle:obj.node.title, releaseVersion:obj.node.field_release_number, releaseVersionClass:releaseNumberClass, releaseGitURL:obj.node.field_git_url,  releaseDownloadURL:obj.node.field_download_urls, releaseDate:obj.node.releasedate, releaseInfo:releaseInfoCombined};
				releaseObjectArray[i] = release;
				
				var releaseDate = releaseObjectArray[i].releaseDate;
				var currentReleaseDateArray = releaseDate.split(" ");
				releaseDate =  currentReleaseDateArray[0].replace(/-/g, '.');
				selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + ' - ' + releaseDate + '</option>';
			});
			selectHtml += '</select>';
			
			jQuery("#dropdownContainer").html(selectHtml);
			jQuery("#versionCloneInput").val(releaseObjectArray[0].releaseGitURL);
			jQuery("#versionDownloadButton").attr('href', releaseObjectArray[0].releaseDownloadURL);
			jQuery("#versionDownloadButton").find('span').text('Download ' + releaseObjectArray[0].releaseTitle );
			
			jQuery("#versonInfoButton").find('span').text('Release Information - ' + releaseObjectArray[0].releaseTitle );
			jQuery("#versonInfoButton").attr('data-target', releaseObjectArray[0].releaseVersionClass);
		}
	
		
		jQuery('#dropdownContainer').on('change', 'select#releaseSelect', function(){
			var releaseNumber = jQuery(this).find(':selected').data('release-number');
            releaseNumber = releaseNumber + ' '.trim();
			var releaseNumberClass = 'ver-' + releaseNumber.split('.').join('-');
			var numOfReleases = releaseObjectArray.length;
			
			jQuery( ".tool-blocks" ).hide();
			jQuery( "." + releaseNumberClass).show();

			//console.log(releaseNumberClass);
			for (var i = 0; i < numOfReleases; i++) {
			    if(releaseObjectArray[i].releaseVersion === releaseNumber){
					jQuery("#versionCloneInput").val(releaseObjectArray[i].releaseGitURL);
					jQuery("#versionDownloadButton").attr('href', releaseObjectArray[i].releaseDownloadURL);
					jQuery("#versionDownloadButton").find('span').text('Download ' + releaseObjectArray[i].releaseTitle);
					
					jQuery("#versonInfoButton").find('span').text('Release Information - ' + releaseObjectArray[i].releaseTitle );
					jQuery("#versonInfoButton").attr('data-target', releaseObjectArray[i].releaseVersionClass);
			    }
			}
		});
		
		
		jQuery('.release-section').on('click', '#versonInfoButton', function(e){
			e.preventDefault();
			var selectedReleaseVersionClass = jQuery(this).attr('data-target');
			var numOfReleases = releaseObjectArray.length;
			for (var i = 0; i < numOfReleases; i++) {
				if(releaseObjectArray[i].releaseVersionClass === selectedReleaseVersionClass){
 					 //var releaseInfoConverted = releaseObjectArray[i].releaseInfo.replace(/(?:\\r\\n|\\r|\\n)/g, '<br />');// if used in blockbuilder 
					 var releaseInfoConverted = releaseObjectArray[i].releaseInfo.replace(/(?:\r\n|\r|\n)/g, '<br />'); // if used in theme
					 jQuery("#releaseInfoContainer h3, #releaseInfoContainer p").empty();
					 jQuery("#releaseInfoContainer h3").text(releaseObjectArray[i].releaseTitle);
					 jQuery("#releaseInfoContainer p").html(releaseInfoConverted);
					 jQuery.colorbox({inline:true, href:'#releaseInfoContainer', width:"80%"});
				}
			} 
		});
		
		//// Tools Scripts ////
		
		
		//var url = '/wp-content/themes/yocto/proxy.php';
		var toolsURL = 'http://api-v1.yoctoproject.org/tools-api';
		var toolsObjectArray = [];
		
		
		function trimText(input, limit){
			var text = input.trim();
			var maxLength = limit; //300;
			var output = '';
			if(text.length > maxLength){
	            output = text.substring(0, maxLength);
				output = output + '... <a href="/" class="tools-read-more blue-link">Read More &raquo;</a>';
			}else{
				output = text;
			}	
			return output;
		}
		
		
		jQuery('#downloadToolsContainer').on('click', '.tools-read-more', function(e){
			e.preventDefault();
			var releaseNotesFull = jQuery(this).closest('.grid-block').find('.releaseNotesFull');
			jQuery.colorbox({inline:true, href:releaseNotesFull, width:"80%"});
	    });
		

		function buildToolsSection(tableData){		
			var linkHtml = '';
			var latestVersion = '';
			var toolsObjectArray = [];
			
			function addToolBlocks(sectionArray, sectionContainerID){
				var toolsHtml = "";
				var excerpt = '';
				var sectionArrayLength = sectionArray.length;
				
				for (var i = 0; i < sectionArrayLength; i++) {
					if(sectionArray[i].docClass === initialVersionClass){
						excerpt = trimText(sectionArray[i].docReleaseNotes, 150);
						toolsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents tool-blocks custom-block ' + sectionArray[i].docClass + '">';	
						toolsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
						toolsHtml += '			<div class="grid-block-copy" style="position:relative;">';
						toolsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';			
						toolsHtml += '				<p>' + excerpt + '</p>';
						toolsHtml += '				<a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="display:block; width:100%; padding:4px; position:absolute; left:0px; bottom:0px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a>';	
						toolsHtml += '			</div>';
						toolsHtml += '			<div class="hide"><div class="releaseNotesFull" style="max-width:740px; padding:20px;"><p>' + sectionArray[i].docReleaseNotes + '</p><a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="padding:4px 20px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a></div></div>';	
						toolsHtml += '		</div>';
						toolsHtml += '</div>';
					}else{
						excerpt = trimText(sectionArray[i].docReleaseNotes, 150);
						toolsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents tool-blocks custom-block ' + sectionArray[i].docClass + '" style="display:none;"">';	
						toolsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
						toolsHtml += '			<div class="grid-block-copy" style="position:relative;">';
						toolsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';			
						toolsHtml += '				<p>' + excerpt + '</p>';
						toolsHtml += '				<a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="display:block; width:100%; padding:4px; position:absolute; left:0px; bottom:0px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a>';	
						toolsHtml += '			</div>';
						toolsHtml += '			<div class="hide"><div class="releaseNotesFull" style="max-width:740px; padding:20px;"><p>' + sectionArray[i].docReleaseNotes + '</p><a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="padding:4px 20px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a></div></div>';	
						toolsHtml += '		</div>';
						toolsHtml += '</div>';
					}
				}
				jQuery("#" + sectionContainerID).html(toolsHtml);
			}
		
			jQuery.each(tableData.nodes, function(i, obj) {
				var releaseVersionClean = obj.node.field_yocto_version.trim();
				var releaseVersionArray = releaseVersionClean.split(' ');
				var releaseVersion = releaseVersionArray[2];
				var releaseClass = 'ver-' + releaseVersion.split('.').join('-');
				var releaseNotes = obj.node.field_release_notes.trim();
				//console.log('Version' + releaseVersion);
			
				var toolItem = {docTitle:obj.node.Tool, docDownloadLink:obj.node.field_download_urls, docVersion:releaseVersion, docClass:releaseClass, docReleaseNotes:releaseNotes};
				toolsObjectArray[i] = toolItem;
			});
			addToolBlocks(toolsObjectArray, 'downloadToolsContainer');
			initialToolsSetup();
		}
		
		
		function buildToolsSections() {
			jQuery.getJSON( url, { csurl: toolsURL}, function(data){ //, dataType: "json"
				buildToolsSection(data);
			});
		}
		
		jQuery.getJSON( url, { csurl: releaseURL}, function(data){ //, dataType: "json"
			buildDropdown(data);
			buildToolsSections();
		});
		
		
		
		//// Layers Scripts ////
		
		
 		//var url = '/wp-content/themes/yocto/proxy.php';
  		var layersURL = 'https://layers.openembedded.org/layerindex/api/layerItems/?format=json';      
 		var jsonData;
		
		function buildTable(layerData, searchTerm){
			var layerTableHtml = '';
			var hasResults = false;
			//if(searchTerm !== 'all'){
				//var searchTitleString = '<h3>Search Results For: ' + searchTerm + '</h3>'
				//jQuery('.search-title').html(searchTitleString);
				//}
			jQuery.each(layerData, function(i, obj) {
			  var LayerType = '';
  			  switch (obj.layer_type) { 
  			  	case 'A': 
  					LayerType = 'Base';
  			  		break;
  			  	case 'B': 
  			  		LayerType = 'Machine (BSP)';
  			  		break;
  			  	case 'M': 
  			  		LayerType = 'Miscellaneous';
  			  		break;
  			  	case 'D': 
  			  		LayerType = 'Distribution';
  			  		break;
  			  	case 'S': 
  			  		LayerType = 'Software';
  			  		break;
  			  	default:
  			  		LayerType = obj.layer_type;
  			  }
			  if(searchTerm === 'all'){
				  hasResults = true;
				  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a href="' + obj.vcs_web_url + '">' + obj.name + '</a></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.summary + '</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + LayerType + '</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.vcs_url + '</p></div>';
				  layerTableHtml += '</div>';
			  }else{
				  searchTerm = searchTerm.toLowerCase();
				  //if ((obj.name.toLowerCase().indexOf(searchTerm) >= 0) || (obj.summary.toLowerCase().indexOf(searchTerm) >= 0) || (obj.vcs_url.toLowerCase().indexOf(searchTerm) >= 0) || (LayerType.toLowerCase().indexOf(searchTerm) >= 0)){
					if (LayerType.toLowerCase().indexOf(searchTerm) >= 0){
					  hasResults = true;
					  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a href="' + obj.vcs_web_url + '">' + obj.name + '</a></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.summary + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + LayerType + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.vcs_url + '</p></div>';
					  layerTableHtml += '</div>';
				  }  
			  }

			});
			if(hasResults === false){
			 layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Search Results for '+ searchTerm + '.</div>';
			}
			jQuery('#layerTable').html(layerTableHtml);
		}

		
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
		
		var searchTerm = 'bsp';//getUrlParameter('searchTerm'); 
		
		if(searchTerm !== undefined){
			jQuery.getJSON({ dataType: 'json', url: layersURL}, function(data){
				buildTable(data, searchTerm);
			  })
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}else{
			jQuery.getJSON({ dataType: 'json', url: layersURL}, function(data){
				buildTable(data, 'all');
			  })
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}	
		
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // downloads archive
    'archived_releases': { 
      init: function() {
      	console.log("Download Archive Page");
      	//// Release Scripts ////
		new Clipboard('.btn');

 		var url = '/wp-content/themes/yocto/proxy.php';
  		var releaseURL = 'http://api-v1.yoctoproject.org/api/downloads?release%5B%5D=57';
		
		var releaseObjectArray = [];
		var releaseCurrentVersion = '';
		var initialVersionClass = '';
		
		function initialToolsSetup() {
			// initial config
			releaseCurrentVersion = (releaseCurrentVersion + ' ').trim();
			var documentClass = 'ver-' + releaseCurrentVersion.split('.').join('-');
			jQuery( ".tool-blocks" ).hide();
			jQuery( "." + documentClass).show();
		}
		
		
		function buildDropdown(tableData){
			var selectHtml = '';
			var hasResults = false;
			
			selectHtml += '<h2 style="display:inline-block;" class="block-title">Release</h2><select id="releaseSelect" name="release-select" class="header-select white-background" style="font-size:18px; height:36px; margin-left:0px; display:inline-block;">';
			jQuery.each(tableData.nodes, function(i, obj) {
				if(i === 0) {
					releaseCurrentVersion = obj.node.field_release_number;
					var versionClassClean = (releaseCurrentVersion + ' ').trim();
					initialVersionClass = 'ver-' + versionClassClean.split('.').join('-');
					//console.log("Set intial Class: " + initialVersionClass);
				}
	            var releaseNumber = obj.node.field_release_number + ' '.trim();
				var releaseNumberClass = 'ver-' + releaseNumber.split('.').join('-');
				var releaseInfoCombined = obj.node.field_errata + obj.node.field_security_fixes + obj.node.field_features + obj.node.field_package_updates;
				
				var release = {releaseTitle:obj.node.title, releaseVersion:obj.node.field_release_number, releaseVersionClass:releaseNumberClass, releaseGitURL:obj.node.field_git_url,  releaseDownloadURL:obj.node.field_download_urls, releaseDate:obj.node.releasedate, releaseInfo:releaseInfoCombined};
				releaseObjectArray[i] = release;
				
				var releaseDate = releaseObjectArray[i].releaseDate;
				var currentReleaseDateArray = releaseDate.split(" ");
				releaseDate =  currentReleaseDateArray[0].replace(/-/g, '.');
				selectHtml += '<option poky-version="field_poky_version" data-release-number="' + obj.node.field_release_number + '" value="' + obj.node.title + '">' + obj.node.title + ' - ' + releaseDate + '</option>';
			});
			selectHtml += '</select>';
			
			jQuery("#dropdownContainer").html(selectHtml);
			jQuery("#versionCloneInput").val(releaseObjectArray[0].releaseGitURL);
			jQuery("#versionDownloadButton").attr('href', releaseObjectArray[0].releaseDownloadURL);
			jQuery("#versionDownloadButton").find('span').text('Download ' + releaseObjectArray[0].releaseTitle );
			
			jQuery("#versonInfoButton").find('span').text('Release Information - ' + releaseObjectArray[0].releaseTitle );
			jQuery("#versonInfoButton").attr('data-target', releaseObjectArray[0].releaseVersionClass);
		}
	
		
		jQuery('#dropdownContainer').on('change', 'select#releaseSelect', function(){
			var releaseNumber = jQuery(this).find(':selected').data('release-number');
            releaseNumber = releaseNumber + ' '.trim();
			var releaseNumberClass = 'ver-' + releaseNumber.split('.').join('-');
			var numOfReleases = releaseObjectArray.length;
			
			jQuery( ".tool-blocks" ).hide();
			jQuery( "." + releaseNumberClass).show();

			//console.log(releaseNumberClass);
			for (var i = 0; i < numOfReleases; i++) {
			    if(releaseObjectArray[i].releaseVersion === releaseNumber){
					jQuery("#versionCloneInput").val(releaseObjectArray[i].releaseGitURL);
					jQuery("#versionDownloadButton").attr('href', releaseObjectArray[i].releaseDownloadURL);
					jQuery("#versionDownloadButton").find('span').text('Download ' + releaseObjectArray[i].releaseTitle);
					
					jQuery("#versonInfoButton").find('span').text('Release Information - ' + releaseObjectArray[i].releaseTitle );
					jQuery("#versonInfoButton").attr('data-target', releaseObjectArray[i].releaseVersionClass);
			    }
			}
		});
		
		
		jQuery('.release-section').on('click', '#versonInfoButton', function(e){
			e.preventDefault();
			var selectedReleaseVersionClass = jQuery(this).attr('data-target');
			var numOfReleases = releaseObjectArray.length;
			for (var i = 0; i < numOfReleases; i++) {
				if(releaseObjectArray[i].releaseVersionClass === selectedReleaseVersionClass){
 					 //var releaseInfoConverted = releaseObjectArray[i].releaseInfo.replace(/(?:\\r\\n|\\r|\\n)/g, '<br />');// if used in blockbuilder 
					 var releaseInfoConverted = releaseObjectArray[i].releaseInfo.replace(/(?:\r\n|\r|\n)/g, '<br />'); // if used in theme
					 jQuery("#releaseInfoContainer h3, #releaseInfoContainer p").empty();
					 jQuery("#releaseInfoContainer h3").text(releaseObjectArray[i].releaseTitle);
					 jQuery("#releaseInfoContainer p").html(releaseInfoConverted);
					 jQuery.colorbox({inline:true, href:'#releaseInfoContainer', width:"80%"});
				}
			} 
		});
		
		
		//// Tools Scripts ////
		
		
		//var url = '/wp-content/themes/yocto/proxy.php';
		var toolsURL = 'http://api-v1.yoctoproject.org/tools-api';
		var toolsObjectArray = [];
		
		
		function trimText(input, limit){
			var text = input.trim();
			var maxLength = limit; //300;
			var output = '';
			if(text.length > maxLength){
	            output = text.substring(0, maxLength);
				output = output + '... <a href="/" class="tools-read-more blue-link">Read More &raquo;</a>';
			}else{
				output = text;
			}	
			return output;
		}
		
		
		jQuery('#downloadToolsContainer').on('click', '.tools-read-more', function(e){
			e.preventDefault();
			var releaseNotesFull = jQuery(this).closest('.grid-block').find('.releaseNotesFull');
			jQuery.colorbox({inline:true, href:releaseNotesFull, width:"80%"});
	    });
		

		function buildToolsSection(tableData){		
			var linkHtml = '';
			var latestVersion = '';
			var toolsObjectArray = [];
		
			jQuery.each(tableData.nodes, function(i, obj) {
				var showAmount = 100;
				if(i < showAmount){

					var releaseVersionClean = obj.node.field_yocto_version.trim();
					var releaseVersionArray = releaseVersionClean.split(' ');
					var releaseVersion = releaseVersionArray[2];
					var releaseClass = 'ver-' + releaseVersion.split('.').join('-');
					var releaseNotes = obj.node.field_release_notes.trim();
					//console.log('Version' + releaseVersion);
				
					var toolItem = {docTitle:obj.node.Tool, docDownloadLink:obj.node.field_download_urls, docVersion:releaseVersion, docClass:releaseClass, docReleaseNotes:releaseNotes};
					toolsObjectArray[i] = toolItem;
				}
			});


			function addToolBlocks(sectionArray, sectionContainerID){
				var toolsHtml = "";
				var excerpt = '';
				var sectionArrayLength = sectionArray.length;
				
				for (var i = 0; i < sectionArrayLength; i++) {
					if(sectionArray[i].docClass === initialVersionClass){
						excerpt  = trimText(sectionArray[i].docReleaseNotes, 150);
						toolsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents tool-blocks custom-block ' + sectionArray[i].docClass + '">';	
						toolsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
						toolsHtml += '			<div class="grid-block-copy" style="position:relative;">';
						toolsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';			
						toolsHtml += '				<p>' + excerpt + '</p>';
						toolsHtml += '				<a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="display:block; width:100%; padding:4px; position:absolute; left:0px; bottom:0px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a>';	
						toolsHtml += '			</div>';
						toolsHtml += '			<div class="hide"><div class="releaseNotesFull" style="max-width:740px; padding:20px;"><p>' + sectionArray[i].docReleaseNotes + '</p><a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="padding:4px 20px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a></div></div>';	
						toolsHtml += '		</div>';
						toolsHtml += '</div>';
					}else{
						excerpt  = trimText(sectionArray[i].docReleaseNotes, 150);
						toolsHtml += '<div class="col-xs-12 col-sm-6 col-md-3 documents tool-blocks custom-block ' + sectionArray[i].docClass + '" style="display:none;">';	
						toolsHtml += '		<div class="grid-block"><div class="grid-featured-image-container"></div>';			
						toolsHtml += '			<div class="grid-block-copy" style="position:relative;">';
						toolsHtml += '				<h6>' + sectionArray[i].docTitle + '</h6>';			
						toolsHtml += '				<p>' + excerpt + '</p>';
						toolsHtml += '				<a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="display:block; width:100%; padding:4px; position:absolute; left:0px; bottom:0px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a>';	
						toolsHtml += '			</div>';
						toolsHtml += '			<div class="hide"><div class="releaseNotesFull" style="max-width:740px; padding:20px;"><p>' + sectionArray[i].docReleaseNotes + '</p><a href="' + sectionArray[i].docDownloadLink + '" class="btn btn-blue" target="_blank" style="padding:4px 20px;"><img src="/wp-content/uploads/2017/08/icon-btn-download.png" style="width:auto !important;"/> Download</a></div></div>';	
						toolsHtml += '		</div>';
						toolsHtml += '</div>';
					}
				}
				jQuery("#" + sectionContainerID).html(toolsHtml);
				
			}
			addToolBlocks(toolsObjectArray, 'downloadToolsContainer');
			initialToolsSetup();
		}
		
		
		function buildToolsSections() {
			jQuery.getJSON( url, { csurl: toolsURL}, function(data){ //, dataType: "json"
				buildToolsSection(data);
			});
		}
		
				
 		jQuery.getJSON( url, { csurl: releaseURL}, function(data){ //, dataType: "json"
			buildDropdown(data);
			buildToolsSections();
		});

		
		//// Layers Scripts ////
		
		
 		//var url = '/wp-content/themes/yocto/proxy.php';
  		var layersURL = 'https://layers.openembedded.org/layerindex/api/layerItems/?format=json';      
 		var jsonData;
		
		function buildTable(layerData, searchTerm){
			var layerTableHtml = '';
			var hasResults = false;
			//if(searchTerm !== 'all'){
				//var searchTitleString = '<h3>Search Results For: ' + searchTerm + '</h3>'
				//jQuery('.search-title').html(searchTitleString);
				//}
			jQuery.each(layerData, function(i, obj) {
			  var LayerType = '';
  			  switch (obj.layer_type) { 
  			  	case 'A': 
  					LayerType = 'Base';
  			  		break;
  			  	case 'B': 
  			  		LayerType = 'Machine (BSP)';
  			  		break;
  			  	case 'M': 
  			  		LayerType = 'Miscellaneous';
  			  		break;
  			  	case 'D': 
  			  		LayerType = 'Distribution';
  			  		break;
  			  	case 'S': 
  			  		LayerType = 'Software';
  			  		break;
  			  	default:
  			  		LayerType = obj.layer_type;
  			  }
			  if(searchTerm === 'all'){
				  hasResults = true;
				  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a href="' + obj.vcs_web_url + '">' + obj.name + '</a></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.summary + '</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + LayerType + '</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.vcs_url + '</p></div>';
				  layerTableHtml += '</div>';
			  }else{
				  searchTerm = searchTerm.toLowerCase();
				  //if ((obj.name.toLowerCase().indexOf(searchTerm) >= 0) || (obj.summary.toLowerCase().indexOf(searchTerm) >= 0) || (obj.vcs_url.toLowerCase().indexOf(searchTerm) >= 0) || (LayerType.toLowerCase().indexOf(searchTerm) >= 0)){
					if (LayerType.toLowerCase().indexOf(searchTerm) >= 0){
					  hasResults = true;
					  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a href="' + obj.vcs_web_url + '">' + obj.name + '</a></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.summary + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + LayerType + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj.vcs_url + '</p></div>';
					  layerTableHtml += '</div>';
				  }  
			  }
			  
			  
			});
			if(hasResults === false){
			 layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Search Results for '+ searchTerm + '.</div>';
			}
			jQuery('#layerTable').html(layerTableHtml);
		}

		
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
		
		var searchTerm = 'bsp';//getUrlParameter('searchTerm'); 
		
		if(searchTerm !== undefined){
			jQuery.getJSON({ dataType: 'json', url: layersURL}, function(data){
				buildTable(data, searchTerm);
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}else{
			jQuery.getJSON({ dataType: 'json', url: layersURL}, function(data){
				buildTable(data, 'all');
			  })
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
   },
   // compatible layers
    'layers': { 
      init: function() {
   		//alert('init');
   		var url = '/wp-content/themes/yocto/proxy.php';
  		var sourceURL = 'https://layers.openembedded.org/layerindex/api/layers/?filter=yp_compatible_version__isnull:false&format=json';

 		var jsonData;
		
		function buildTable(layerData, searchTerm){
			var layerTableHtml = '';
			var hasResults = false;
			if(searchTerm !== 'all'){
				var searchTitleString = '<h3>Search Results For: ' + searchTerm + '</h3>';
				jQuery('.search-title').html(searchTitleString);
			}
			jQuery.each(layerData, function(i, obj) {
			  if(searchTerm === 'all'){
				  hasResults = true;
				  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a target="_blank" href="' + obj['layer'].vcs_web_url + '">' + obj['layer'].name + '<br />version: ' + obj['branch'].name + '</a></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj['layer'].summary + '</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj['maintainers'][0].name +'</p></div>';
				  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj['layer'].vcs_url + '</p></div>';
				  layerTableHtml += '</div>';
			  }else{
				  searchTerm = searchTerm.toLowerCase();
				  if ((obj['layer'].name.toLowerCase().indexOf(searchTerm) >= 0) || (obj['branch'].name.toLowerCase().indexOf(searchTerm) >= 0) || (obj['layer'].description.toLowerCase().indexOf(searchTerm) >= 0) || (obj['layer'].vcs_url.toLowerCase().indexOf(searchTerm) >= 0) || (obj['maintainers'][0].name.toLowerCase().indexOf(searchTerm) >= 0)){
					  hasResults = true;
					  layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><a target="_blank" href="' + obj['layer'].vcs_web_url + '">' + obj['layer'].name + '<br />version: ' + obj['branch'].name + '</a></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj['layer'].summary + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-2"><p>' + obj['maintainers'][0].name + '</p></div>';
					  layerTableHtml += '	<div class="col-xs-12 col-sm-4"><p>' + obj['layer'].vcs_url + '</p></div>';
					  layerTableHtml += '</div>';
				  }
			  }
			});
			if(hasResults === false){
			 layerTableHtml += '<div class="table-row" style="border-bottom:1px solid #ccced0;">No Search Results for '+ searchTerm + '.</div>';
			}
			jQuery('#layerTable').html(layerTableHtml);
		}

		
		var getUrlParameter = function getUrlParameter(sParam) {
		    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		        sURLVariables = sPageURL.split('&'),
		        sParameterName,
		        i;

		    for (i = 0; i < sURLVariables.length; i++) {
		        sParameterName = sURLVariables[i].split('=');

		        if (sParameterName[0] === sParam) {
		            return sParameterName[1] === undefined ? 'all' : sParameterName[1];
		        }
		    }
		};
		
		var searchTerm = getUrlParameter('searchTerm');
		
		if(searchTerm !== undefined){
			jQuery.getJSON({ dataType: 'json', url: sourceURL}, function(data){
				buildTable(data, searchTerm);
			  })
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}else{
			jQuery.getJSON({ dataType: 'json', url: sourceURL}, function(data){
				buildTable(data, 'all');
			  })
			.fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("error " + textStatus);
			       console.log("incoming Text " + jqXHR.responseText);
			});
		}
      },
   
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
        //alert('final');
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
