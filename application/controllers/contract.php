<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("attachments/datastore");
		$this->load->model("contracthighlights");
		$this->load->library("contracts");
	}
	
	
	
	public function page($contract_id, $customer_id, $page)
	{
		// standard path to retrieve and put contracts	
		$remote_path = $this->contracts->get_remote_url_for_contract($customer_id, $contract_id);
		$page_url = $this->datastore->get($remote_path."/pages/page-".$page.".png");
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
	
	
	public function get_highlights($contract_id, $page)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array($this->contracthighlights->get_for_contract_page($contract_id, $page))));
	}
	
	public function add_highlight()
	{
		$contract_id=$this->input->post('contract_id');
		$position=$this->input->post('position');
		$height=$this->input->post('height');
		$page=$this->input->post('page');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array($this->contracthighlights->add($contract_id, $position, $height, $page))));
		
	}
} // end controller
		