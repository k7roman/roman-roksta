/* 

My Custom JS
============

Author: Kelvin Roman
Updated: July 2015
Notes: Hand COded for Roman Inc.

*/

$(function() {
	$('#alertMe').click(function(e) { // when you click th big blue button

		e.preventDefault(); // prevent the default behavior of the link

		$('#successAlert').slideDown(); // slide down from the alert box
	});

$(function() {
	$('#alert_Me').click(function(e) { // when you click the sceondary link

		e.preventDefault(); // prevent the default behavior of the link

		$('#dangerAlert').slideDown(); // slide down from the alert box

	});
})

	$('a.pop').click(function(e) {
		e.preventDefault();
	});

	$('a.pop').popover();

	$('[rel="tooltip"]').tooltip();

});

$(function() {
	$('#myNav').affix({
		offset: {
			top: 60
		}
	});
});

