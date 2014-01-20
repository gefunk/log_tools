
function handle_upload_event(e){
	console.log("Handle Upload Event", e.event);
	if(e.event == 'progress'){
		console.log("Progress:"+e.data);
		$("div#file-upload-messages div.bar").css("width", e.data);
	}else if(e.event == 'success'){
		$("div#file-upload-messages div.bar").css("width", "100%");
		var doc = (e.data.document);
		html = "<tr id="+doc._id.$id+"><td>"+doc.file_name+"</td><td>"+convert_date_to_str_datetime(new Date(doc.date.sec*1000))+"</td></tr>";
		$(html).insertAfter('table#uploaded-files tr:first');
	}else if(e.event == 'error'){
		$("div#file-upload-messages div.bar").after("Upload error: "+e.data);
	}
}


$(document).ready(function(){
	
	/**
	 * Handle Uploading of documents
	 */
	uploader.addSubscriber(handle_upload_event);
	
	// when file change validate
	$('input[name=contract-file]').change(function(){
		
		// reset upload bar
		$("div#file-upload-messages div.bar").css("width", "0%");
		
	    var file = this.files[0];
	    name = file.name;
	    size = file.size;
	    type = file.type;
	    //your validation
	    if(!type.contains('pdf')){
	    	// this is not a pdf file, we currently only accept pdf files
	    	$("div#file-upload-messages").html("Please only upload PDF's");
	    	$(this).replaceWith("<input name='contract-file' type='file' />");
	    }else{
	   		// upload the file
	   		uploader.upload(file);
	    }
	});
	
	
	/**
	 * Handle click into view a specific document
	 */
	
});
