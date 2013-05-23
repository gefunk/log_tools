
/**
* random string generator
**/
function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}


/**
* initializes from and to date inputs
* @param from_date_id the id of the from date field
* @param to_date_id the id of the to date field
* @param disable_before_today boolean to signify if dates before today should be disabled
*/
function create_start_to_date_fields (from_date_id, to_date_id, disable_before_today) {
	var start_date_options = {};
	/** date picker for start and end date **/
	
	var $start_date, $end_date = null;
	
	if(disable_before_today){
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		start_date_options = {
			onRender: function(date) {
				return date.valueOf() < now.valueOf() ? 'disabled' : '';
			}
		};
	}
	
	$start_date = $("#"+from_date_id).datepicker(start_date_options).on('changeDate', function(ev) {
		if (ev.date.valueOf() > $end_date.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			$end_date.setValue(newDate);
		}
		$start_date.hide();
		$("#"+to_date_id)[0].focus();
	}).data('datepicker');
	
	$end_date = $("#"+to_date_id).datepicker({
			onRender: function(date) {
			return date.valueOf() <= $start_date.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		$end_date.hide();
	}).data('datepicker');
}