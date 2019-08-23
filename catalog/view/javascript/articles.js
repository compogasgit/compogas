$(document).ready(function() {
	la_top = $('.la-top').width();
	la_bottom = $('.la-bottom').width();
	if (la_top < 600) {
		$('.la-top > div').attr('class', 'col-lg-6 col-md-6 col-sm-12 col-xs-12');
	} else if (la_top < 900) {
		$('.la-top > div').attr('class', 'col-lg-4 col-md-4 col-sm-6 col-xs-12');
	} else {
		$('.la-top > div').attr('class', 'col-lg-3 col-md-3 col-sm-6 col-xs-12');
	}
	if (la_bottom < 600) {
		$('.la-bottom > div').attr('class', 'col-lg-6 col-md-6 col-sm-12 col-xs-12');
	} else if (la_bottom < 900) {
		$('.la-bottom > div').attr('class', 'col-lg-4 col-md-4 col-sm-6 col-xs-12');
	} else {
		$('.la-bottom > div').attr('class', 'col-lg-3 col-md-3 col-sm-6 col-xs-12');
	}
});
