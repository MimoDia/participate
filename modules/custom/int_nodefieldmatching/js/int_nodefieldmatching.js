
/*
(function($, Drupal, drupalSettings){

	$(".panel-body .field--type-starrating rate-image .star1-on .even .s1").hover(
	function() {
	  $(this).css("color", "yellow"));
	}, function() {
	  $(this).css("color", "pink"));
	}
	);

})(jQuery, Drupal, drupalSettings);

*/

(function($, Drupal, drupalSettings){

$(document).ready(function(){

	$('.rate-image').each(function(){
		if ($(this).hasClass('s1')){
			$(this).attr('title', 'trop faible');
		} else if ($(this).hasClass('s2')){
			$(this).attr('title', 'assez faible');
		} else if ($(this).hasClass('s3')){
			$(this).attr('title', 'faible');
		} else if ($(this).hasClass('s4')){
			$(this).attr('title', 'passable');
		} else if ($(this).hasClass('s5')){
			$(this).attr('title', 'assez bien');
		} else if ($(this).hasClass('s6')){
			$(this).attr('title', 'bien');
		} else if ($(this).hasClass('s7')){
			$(this).attr('title', 'tres bien');
		} else if ($(this).hasClass('s8')){
			$(this).attr('title', 'honorable');
		}
		$(this).attr('data-toggle', 'tooltip');
	});
	$('[data-toggle="tooltip"]').tooltip({
		'placement': 'top'
	});

});


})(jQuery, Drupal, drupalSettings);
