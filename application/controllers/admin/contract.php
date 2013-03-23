<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		// model used in multiple places, used to load charge rules
		// and associated metadata
		$this->load->model("chargerulesmodel");
		$this->load->model("contractmodel");
		$this->load->model('customermodel');
		$this->load->model("admin/currencycodes");
		
	}

	/**
	* add a new contract into the system
	* accessible via /admin/contract/add
	*/
	public function add()
	{
		// code igniter framework - validation rules
		$this->form_validation->set_rules('contract_number', 'Contract Number', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// validate
		if ($this->form_validation->run() == FALSE){
			$header_data['title'] = "Add New Contract";
			// pass javascript to footer
			$footer_data['scripts'] = array('admin/contract/add.js');
			// load models needed
			$this->load->model('carriermodel');
			// load data from database
			$data['customers'] = $this->customermodel->get_customers();
			$data['carriers'] = $this->carriermodel->get_carriers();
			// load view
			$this->load->view('admin/header', $header_data);
			$this->load->view('admin/contract/add', $data);
			$this->load->view('admin/footer', $footer_data);		
		}else{
			// retrieve post variables
			$customer = $this->input->post('customer');
			$carrier = $this->input->post('carrier');
			$contract_number = $this->input->post('contract_number');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			// add contract to database
			$this->contractmodel->add_contract($contract_number, $start_date, $end_date,$customer,$carrier);
			redirect('admin/customer/addrules/'.$contract_number);
		}
		
	}
	
	/**
	* add rules to a contract
	* @param the contract id of the contract
	*/
	public function addrules($contract_number)
	{
		//$this->output->enable_profiler(TRUE);
		// validate
		if(isset($contract_number)){
			$result = $this->contractmodel->get_contract_from_number($contract_number);
			if(isset($result)){
				
				$header_data['title'] = "Add Rules to Contract";
				$header_data['page_css'] = array('admin/contract/rule.css');
				$footer_data['scripts'] = array('select2.js','admin/contract/rules.js');
				// set page data
				$data['carrier'] = $result->carrier;
				$data['contract_number'] = $result->contract_number;
				$data['customer'] = $result->customer;
				$data['application_rules'] = $this->chargerulesmodel->get_charge_application_rules();
				$data['application_types'] = $this->chargerulesmodel->get_charge_application_types();
				$data['currencies'] = $this->currencycodes->get_currency_codes();
				$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
				$data['charge_rules'] = $this->chargerulesmodel->get_charge_rules_for_contract($result->contract_id);
				// save contract id for the next page
				$data['contract_id'] = $result->contract_id;
				// load next view
				$this->load->view('admin/header', $header_data);
				$this->load->view('admin/contract/rules', $data);
				$this->load->view('admin/footer', $footer_data);
				

			}
		}else{
			
			echo "Not a Valid Contract Number";
		}
		
	}
	/*
	json from UI
	contract_id : contract_id,
	name : name,
	application_type : rule_application_type,
	currency : currency,
	value : value,
	application_rule : rule_application,
	application_cases : rule_application_cases
	*/
	public function saverule()
	{
		//$this->output->enable_profiler(TRUE);
		$contract_id = $this->input->post('contract_id');
		$rule_name = $this->input->post('name');
		$rule_code = $this->input->post('code');
		$currency = $this->input->post('currency');
		$value = $this->input->post('value');
		$application_rule = $this->input->post('application_rule');
		$application_type = $this->input->post('application_type');
		$application_cases = $this->input->post('application_cases');
		// save into db
		$this->chargerulesmodel->save_rule(
				$contract_id, 
				$rule_name, 
				$application_rule,
				$application_type, 
				$application_cases,
				$rule_code,
				$currency, 			
				$value);
	}
	
	
	public function getrules($contract_id)
	{
		
	}

}

