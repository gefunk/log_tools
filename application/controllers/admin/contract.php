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
				$footer_data['scripts'] = array('select2.js','admin/contract/rules.js','cities.selector.js', 'countries.selector.js', 'container.selector.js', 'ports.selector.js');
				// set page data
				$data['carrier'] = $result->carrier;
				$data['carrier_id'] = $result->carrier_id;
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
	
	public function lanes($contract_number)
	{
		if(isset($contract_number)){
			$result = $this->contractmodel->get_contract_from_number($contract_number);
			if(isset($result)){
				$header_data['title'] = "Add Rules to Contract";
				$header_data['page_css'] = array('admin/contract/rule.css');
				$footer_data['scripts'] = array('select2.js','cities.selector.js', 'countries.selector.js', 'containers.selector.js', 'ports.selector.js','admin/contract/lanesview.js');
				// set page data
				$data['carrier'] = $result->carrier;
				$data['carrier_id'] = $result->carrier_id;
				$data['contract_number'] = $result->contract_number;
				$data['customer'] = $result->customer;
				$data['currencies'] = $this->currencycodes->get_currency_codes();
				$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
				// save contract id for the next page
				$data['contract_id'] = $result->contract_id;
				// get the lanes for a contract
				$data['lanes'] = $this->chargerulesmodel->get_lanes_for_contract($result->contract_id);
				// load next view
				$this->load->view('admin/header', $header_data);
				$this->load->view('admin/contract/lanesview', $data);
				$this->load->view('admin/footer', $footer_data);
			}else{
				echo "Not a Valid Contract Number";
			}
		}// end if contract_number
	}
	
	public function savelane()
	{
		$contract_id = $this->input->post('contract_id');
		$from_port = $this->input->post('from_port');
		$to_port = $this->input->post('to_port'); 
		$container_type = $this->input->post('container_type');
		$value = $this->input->post('value');
 		$cargo_type = $this->input->post('cargo_type');
		$code = $this->input->post('code');
		$this->chargerulesmodel->save_new_lane($contract_id, $from_port, $to_port, $value, $container_type, $cargo_type, $code);
	}
	
	public function getrules($contract_id=NULL)
	{
		//$this->output->enable_profiler(TRUE);
		if(!isset($contract_id))
			$contract_id = $this->input->get('contract_id');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->chargerulesmodel->get_charge_rules_for_contract($contract_id)));	
		//echo json_encode($this->chargerulesmodel->get_charge_rules_for_contract($contract_id));
	}
	
	public function getruleoptions($contract_id=NULL, $data_source=NULL)
	{
		if(!isset($contract_id) && (!isset($data_source))){
			$contract_id = $this->input->get('contract_id');
			$data_source = $this->input->get('data_source');			
		}
		//$this->output->enable_profiler(TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->chargerulesmodel->get_charge_options_for_rule($contract_id, $data_source)));	
	}
	
	public function getlanes()
	{
		$this->output->enable_profiler(TRUE);
		echo json_encode($this->chargerulesmodel->get_lanes_for_contract(1) );
	}

}

