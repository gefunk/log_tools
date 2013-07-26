<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends MY_In_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("attachments/datastore");
		$this->load->model("contracthighlights");
		$this->load->model("contractmodel");
		$this->load->library("contracts");
	}

	
	public function index(){
		$customer_id = $this->session->userdata("customer_id");
		$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
		$header_data['title'] = "View Contracts";
		$header_data["page_css"] = array("contracts/landing.css","contracts/overlay_page_display.css");
		$footer_data['scripts'] = array("contracts/landing.js");
		$footer_data['selected_link'] = "contracts";
		$this->load->view('header', $header_data);
		$this->load->view('contract/landing', $data);
		$this->load->view('footer', $footer_data);
		
	}
	
	public function get_page($contract_id, $page){
		// standard path to retrieve and put contracts	
		$customer_id = $this->session->userdata("customer_id");
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
	
	
	public function delete_highlight()
	{
		$id = $this->input->post('id');
		$this->contracthighlights->delete($id);
	}
} // end controller
		