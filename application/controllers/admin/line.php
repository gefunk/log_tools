<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(ENTITIES_DIR  . "LineItemEntity.php");
require_once(ENTITIES_DIR  . "LineContainer.php");
require_once(ENTITIES_DIR . "LineItemCharge.php");

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
	}


	public function add($contract_number)
	{
		$this->output->enable_profiler(TRUE);
		$result = $this->contractmodel->get_contract_from_number($contract_number);
		
		
		// header data
		$header_data['title'] = "Add new Line Item";
		$header_data['page_css'] = array('admin/contract/line/add.css');
		// pass javascript to footer
		$footer_data["scripts"] = array("custom-selectors/custom.source.js","admin/contract/line/add.js");	
		// page data
		$data['cargo_types'] = $this->referencemodel->get_cargo_types($result->customer_id,$result->carrier_id);
		$data['container_types'] = $this->referencemodel->get_container_types($result->carrier_id);
		$data['currencies'] = $this->referencemodel->get_currency_codes();
		$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
		$data['port_groups'] = $this->referencemodel->get_port_groups($result->contract_id);
		
			
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/contract/line/add', $data);
		$this->load->view('admin/footer', $footer_data);		
	}
	
	
	public function save()
	{
		
	}

	public function all($contract_id)
	{
		$line_items = $this->lineitemmodel->get_line_items_for_contract($contract_id);
		
		
		
	}
	
	



} // end controller