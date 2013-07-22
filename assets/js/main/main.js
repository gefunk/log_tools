$(document).ready(function(){
	$('input#origin').typeahead({
  		name: 'accounts',
  		remote: site_url+"/services/search_cities/%QUERY",
  		valueKey: 'city_name',
  		template: '<p>{{city_name}}, {{country_name}}</p>',
    	engine: Hogan
	});
});
