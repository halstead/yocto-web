/*
 * Very Simple Event List Datepicker
 */

jQuery(document).ready(function ($) { 
	$( "#vsel-date, #vsel-start-date" ).datepicker({ 
		dateFormat: objectL10n.dateFormat 
	});
});