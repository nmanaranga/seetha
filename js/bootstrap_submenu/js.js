
var Tm = "";

$(document).ready(function(){

	$('[data-submenu]').submenupicker();


	$('.dropdown').on('mouseleave', function() {		
		Tm = setTimeout( 't()',1000);
	});

	$('.dropdown').on('mouseenter', function() {		
		clearTimeout(Tm);
	});
});


function t(){

	clearTimeout(Tm);
	$('.dropdown').removeClass("open");


}