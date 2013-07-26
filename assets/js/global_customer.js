
/***
 * sets the selected link on the page
*/
$(document).ready(function(){
	$("ul#phone-nav > li#"+selected_link).addClass('active');
	$("ul#dashboard-menu > li#"+selected_link).addClass('active')
		.append('<div class="pointer"><div class="arrow"></div><div class="arrow_border"></div></div>');
});
