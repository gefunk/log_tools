<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(ENTITIES_DIR  . "lineitementity.php");
require_once(ENTITIES_DIR  . "linecontainer.php");
require_once(ENTITIES_DIR . "lineitemcharge.php");

class Line extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("lineitem/lineitemmodel");
        $this->load->model("lineitem/containermodel");
		$this->load->model("lineitem/linechargemodel");
		$this->load->model("contractmodel");
		$this->load->model("referencemodel");
		$this->load->model('customermodel');
		$this->load->model('portgroupmodel');
	}



	public function add($contract_id){
		
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Line Items";
		// pass javascript to footer
		//$footer_data["scripts"] = array("admin/contract/ports.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/document', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer');
		
	}

	public function addold($contract_number)
	{
		//$this->output->enable_profiler(TRUE);
		$result = $this->contractmodel->get_contract_from_number($contract_number);
		
		
		// header data
		$header_data['title'] = "Add new Line Item";
		$header_data['page_css'] = array('lib/famfamflag.css','admin/contract/line/add.css');
		// pass javascript to footer
		$footer_data["scripts"] = 
						array(
							"admin/contract/line/contract.document.js", 
							"admin/contract/line/highlight.contract.document.js", 
							"custom-selectors/custom.source.js",
							"admin/contract/line/add.js"
						);	
		// page data
		$data['cargo_types'] = $this->referencemodel->get_cargo_types($result->customer_id,$result->carrier_id);
		$data['container_types'] = $this->referencemodel->get_container_types($result->carrier_id);
		$data['currencies'] = $this->referencemodel->get_currency_codes();
		$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
		$data['port_groups'] = $this->portgroupmodel->get_port_groups_for_contract($result->contract_id);
		$data['tariffs'] = $this->referencemodel->get_tarriffs_for_carrier($result->carrier_id);
		$data['services'] = $this->referencemodel->get_services_for_carrier($result->carrier_id); 
		
		$data['carrier'] = $result->carrier;
		$data['carrier_id'] = $result->carrier_id;
		$data['contract_number'] = $result->contract_number;
		$data['contract_id'] = $result->contract_id;
		$data['customer_id'] = $result->customer_id;
		
		$data['effective_date'] = date( 'm/d/Y', strtotime($result->start_date)); 
		$data['expires_date'] = date( 'm/d/Y', strtotime($result->end_date));
		// views	
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/contract/line/add', $data);
		$this->load->view('admin/footer', $footer_data);		
	}
	
	
	public function save()
	{
		$contract_id = $this->input->post("contract_id");
		$currency = $this->input->post("currency");
		$cargo = $this->input->post('cargo');
		$origin = $this->input->post('origin');
		$origin_type = $this->input->post('origin_type');
		$destination = $this->input->post('destination');
		$destination_type = $this->input->post('destination_type');
		$service = $this->input->post('service');
		$effective = $this->input->post("effective_date");
		$expires = $this->input->post('expires_date');
		
		
		$lineitem = LineItemEntity::initLineItem($origin, 
												$origin_type, 
												$destination, 
												$destination_type, 
												$cargo, 
												$effective, 
												$expires, 
												$currency, 
												$service, 
												$contract_id);
		// save to db										
		$line_item_id = $this->lineitemmodel->add_line_item($lineitem);
		
		
														
		
	}

	public function all($contract_id)
	{
		//$line_items = $this->lineitemmodel->get_line_items_for_contract($contract_id);
		
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Line Items";
		// pass javascript to footer
		//$footer_data["scripts"] = array("admin/contract/ports.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/line/manage', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer');
		
	}
	
	



} // end controller