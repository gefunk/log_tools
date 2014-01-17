
var uploader = uploader || {};

(function(){
	var subscribers = new Array();
	var upload_url = site_url+'/admin/document/upload';
	/**
	 * add subscriber to be called back when anything changes
	 * @param subscriber - the subscriber to add to the list of subscribers,
	 * subscribers can handle three different events (progress, success, error),
	 * data is sent back in json format: {event: event_type, data: event_data}
	 */
	this.addSubscriber = function(subscriber){
		subscribers.push(subscriber);
	};
	
	/**
	 * send information to all subscribers 
	 * private method
	 * @param data - the data to send to all subscribers
	 */
	function broadcast(data){
		if(subscribers.length > 0){
			for(var i = 0; i < subscribers.length; i++){
				subscribers[i](data);
			}	
		}
	};
	
	/**
	 * Upload new file to server
	 * @param formData - formData to send to the backend server, usually will be PDF byte array
	 */
	this.upload = function(fileData){
		
		var formData = new FormData();
		formData.append("file_name", fileData.name);
		formData.append("file_size", fileData.size);
		formData.append("file", fileData);
		
		 $.ajax({
	        url: upload_url,  //server script to process data
	        type: 'POST',
	        xhr: function() {  // custom xhr
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // check if upload property exists
	            	// for handling the progress of the upload, will broadcast progress to all the subscribers
	                myXhr.upload.addEventListener(
	                	'progress',
						function(e){
							var percent = ((e.loaded/e.total)*100)+"%";
							broadcast({event: 'progress', data: "+percent+"});
						}, 
						false
					); 
	            }
	            return myXhr;
	        },
	        //Ajax events
	        success: function(data, textStatus, jqXHR){
	        	broadcast({event: 'success', data: data});
	        },
	        error: function(jqXHR,textStatus,errorThrown){
	        	broadcast({event: 'error', data: errorThrown});
	        },
	        // File data
	        data: formData,
	        //Options to tell JQuery not to process data or worry about content-type
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	};
	
	/**
	 * destroy will stop any callbacks
	 */
	this.destroy = function(){
		// reset subscriber array
		subscribers = new Array();
	};
	
}).apply(uploader);

