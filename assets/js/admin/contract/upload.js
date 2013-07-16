


$(document).ready(function(){
	

	
	// when file change validate
	$('input[name=contract-file]').change(function(){
	    var file = this.files[0];
	    name = file.name;
	    size = file.size;
	    type = file.type;
	    //your validation
	    if(!type.contains('pdf')){
	    	$("div#upload-message").html("Please only upload PDF's");
	    	$(this).replaceWith("<input name='file' type='file' />");
	    }else{
	    	$("div#upload-message").html("Click Upload to send file to server");
	    }
	});


	$('a.upload-button').click(function(){
		$("div#upload-modal input[name='contract_number']").val($(this).data("contract-number"));
		$("div#upload-modal input[name='contract_id']").val($(this).data("contract-id"));
		$("div#upload-modal input[name='customer_id']").val($(this).data("customer-id"));
		
		// initialize new uploader
		contractUploader.initialize($(this).data("contract-id"));
		contractUploader.addCallbackSubscriber(show_upload_modal_status);
		contractUploader.checkStatus();
		
		$('div#upload-modal').modal('show');
	});

	// handle close click
	$("div#upload-modal .close").click(function(){
		// stop any call backs
		contractUploader.destroy();
		$("div#upload-modal").modal('hide');
	});


	// button click upload
	$('a#upload-file').click(function(e){
		
		console.log("Upload button clicked");
		e.preventDefault();
	    var formData = new FormData($("div.modal-body > form")[0]);
	   	// upload the form
	   	contractUploader.upload(formData);
	   	$("div#upload-message").html("");
	    
	});
	
	$("a#overwrite-file").click(function(){
		show_upload_modal_status(false);
	});
	

});


var contractUploader = {
	initialize: function(contract_id){
		// any construction work	
		contractUploader.callbackSubscribers = new Array();
		contractUploader.contract_id = contract_id;
	},
	// add a subscriber to the callback
	addCallbackSubscriber: function(callback){
		contractUploader.callbackSubscribers.push(callback);
	},
	// broadcast to all the subscribers of a check status
	broadcast: function(data){
		if(contractUploader.callbackSubscribers && contractUploader.callbackSubscribers.length > 0){
			for(var i = 0; i < contractUploader.callbackSubscribers.length; i++){
				contractUploader.callbackSubscribers[i](data);
			}	
		}
	},
	upload: function(formData){
		 $.ajax({
	        url: site_url+'/admin/contract/upload',  //server script to process data
	        type: 'POST',
	        xhr: function() {  // custom xhr
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // check if upload property exists
	                myXhr.upload.addEventListener('progress',contractUploader.updateProgress, false); // for handling the progress of the upload
	            }
	            return myXhr;
	        },
	        //Ajax events
	        success: function(){
	        	console.log("contractUploader complete");
	        	contractUploader.addCallbackSubscriber(show_upload_modal_status);
				show_upload_modal_status (0)
	        },
	        error: function(){
	        	console.log("contractUploader error")
	        },
	        // Form data
	        data: formData,
	        //Options to tell JQuery not to process data or worry about content-type
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	},
	updateProgress: function(e){
		if(e.lengthComputable){
	    	var percent = ((e.loaded/e.total)*100)+"%";
	        $('div#upload-progress div.progress > div.bar').css('width', percent);
    	}
	},
	checkStatus: function(){
		$.get(
  			site_url+"/admin/contract/upload_status/"+contractUploader.contract_id,
  			function(data){
  				if(data && data[0] != null){
  					// file has been uploaded and here is the status
  					contractUploader.broadcast(data[0].status);	
  				}else{
  					// no file has ever been uploaded
  					contractUploader.broadcast(false);
  				}
  				
  			}
  		);
	},
	destroy: function(){
		// stop calling back to functions
		contractUploader.callbackSubscribers = null;
	}
	
	
}; // end contract uploader


/**
 * displays either the uploaded contract file
 * the upload file dialog
 * or the processing status of the contract
 * @param {Object} status
 */
function show_upload_modal_status (status) {
	if(!status){
		
		$("div#page-progress").hide();
		$("a#overwrite-file").hide();
		
		$("a#upload-file").show();
		$("input[name=contract-file]").show();
		$("div#upload-progress").show();
	}else if(status < 100){
		
		$("input[name=contract-file]").hide();
		$("div#upload-progress").hide();
		$("a#upload-file").hide();
		$("a#overwrite-file").hide();
		
		$("div#page-progress").show();
		var percent = status+"%";
		//console.log("Percent Contract Progress", percent);
  		$('div#page-progress div.progress > div.bar').css('width', percent);
  		// update again after 5 seconds
  		window.setTimeout(contractUploader.checkStatus, 5000);
	}else{
		// show the uploaded pages
		console.log("should show uploaded pages");
		
		$("input[name=contract-file]").hide();
		$("div#upload-progress").hide();
		$("a#upload-file").hide();
		$("div#page-progress").hide();
		
		
		$("a#overwrite-file").show();
	}
}





