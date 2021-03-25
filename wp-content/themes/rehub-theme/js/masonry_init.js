jQuery(document).ready(function($) {
'use strict';	
var $containerfull = $('.masonry_grid_fullwidth');
$containerfull.imagesLoaded( function() {
	$containerfull.addClass('loaded');
	var directionrtl = (jQuery('body.rtl').length > 0) ? false : true;
	$containerfull.masonry({
	    itemSelector: '.small_post',
	    isOriginLeft: directionrtl   
	});	
});
});