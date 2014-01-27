<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		

		$this->load->model("attachments/attachmentmodel");
		$this->load->model("attachments/datastore");
		$this->load->library("async");

	}
	
	
	/**
	 * retrieve page from document store
	 * @param $document_id - the mongo document id, used to lookup where the document is stored
	 * @param $page - which page to retrieve from the datastore
	 */
	public function page($document_id, $page)
	{
		
		// standard path to retrieve documents	
		$page_url = $this->datastore->get($document_id."/pages/".$page.".png");
		if($page_url){
			$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => true,"page" => $page_url))); 
		}else{
			$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => false)));
		}
	}
	
	/**
	 * get total pages for document 
	 * @param $document_id - the document id to get the total pages for
	 */
	public function get_total_pages($document_id){
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("total"=>$this->attachmentmodel->get_total_pages($document_id))));
	}
	
	/**
	 * UPLOAD Section
	 */
	
	/**
	 * upload contract document to the system
	 * @param $_FILES['file'], should be passed in binary format to upload the file
	 */
	public function upload()
	{
		
		
		if(isset($_FILES) && !empty($_FILES) && !empty($_FILES["file"])){
			
			if(isset($_FILES['file']['name']) &&
					$_FILES['file']['error'] == UPLOAD_ERR_OK)
					{

						// move file to uploads directory
						$upload_status = move_uploaded_file($_FILES["file"]["tmp_name"], $this->config->item('upload_directory').$_FILES["file"]["name"]);
						
						// if upload was successful
						if($upload_status){
							// get a mongoid to use as document id to track this file
							// save the contract into the db
							$document = $this->attachmentmodel->insert_uploaded_document($_FILES["file"]["name"]);
						
							$params = array(
								"document_id" => (string) $document['_id'],
			            		"contract_filename" => $_FILES["file"]["name"]
							);
							// upload the file to the conversion service
							$this->async->post(site_url()."/attachments/async_convert_pdf", $params);
							$this->output
		    					->set_content_type('application/json')
		    					->set_output(json_encode(array("success" => true, "document" => $document)));
						}else{
							// upload failed
							$this->output
		    					->set_content_type('application/json')
		    					->set_output(json_encode(array("success" => false, "error" => "Move Uploaded file Failed: $upload_status")));
						}
						
					
				}else{
					$this->output
		    			->set_content_type('application/json')
		    			->set_output( json_encode(array("success" => false, "error"=> $_FILES['file']['error'])));
				}
		}else{
			$this->output
		    	->set_content_type('application/json')
		    	->set_output( json_encode(array("success" => false, "error"=> "$_FILES parameter is empty: ".var_dump($_FILES))));
		}
			
		
	}


	/**
	 * get upload status for contract
	 */
	public function conversion_progress_status($document_id)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->attachmentmodel->get_document_process_progress($document_id)));
	}


	/**
	 * add a tag to the document
	 */
	function add_tag(){
		$document_id = $this->input->post("document_id");
		$tag = $this->input->post("tag");
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode(
				$this->attachmentmodel->add_tag($document_id, $tag)
			));
	}

	/**
	 * remove a tag from the document
	 */
	function remove_tag(){
		$document_id = $this->input->post("document_id");
		$tag = $this->input->post("tag");
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode(
				$this->attachmentmodel->remove_tag($document_id, $tag)
			));
	}

	
}