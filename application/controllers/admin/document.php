<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		// model used in multiple places, used to load charge rules
		// and associated metadata

		$this->load->model("contractmodel");
		$this->load->model('customermodel');
		$this->load->model('lineitem/containermodel');
		$this->load->model("admin/currencycodes");
		$this->load->model("referencemodel");
		$this->load->model("lanemodel");
		$this->load->model("rulemodel");
		$this->load->model("portgroupmodel");
		$this->load->model('assetstorage');
		$this->load->model("attachments/attachmentmodel");
		$this->load->model("attachments/datastore");
		$this->load->model('cargomodel');

		$this->load->library("contracts");
		$this->load->library("async");

	}
	
	
	public function manage($contract_id){
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Manage Documents";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/ports.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/document/manage', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
		
	}
	
	public function add($contract_id)
	{
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Manage Documents";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/document/upload.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/document/add', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}
	
	
	
	/**
	 * upload contract document to the system
	 * @param contract_id the contract id for which this upload belongs to
	 * @param customer_id the customer for which this contract belongs to
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



	
}