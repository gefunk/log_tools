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
		$this->load->model("referencemodel");
		$this->load->model("lanemodel");
		
	}

	public function index($customer_id=NULL)
	{
		$header_data['title'] = "View Contracts";
		$data['customers'] = $this->customermodel->get_customers();
		if($customer_id != NULL){
			$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
			$data["customer_id"] = $customer_id;
			$data["carriers"] = $this->referencemodel->get_carriers();
		}
		$footer_data["scripts"] = array("admin/contract/view.js");
			
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/contract/view', $data);
		$this->load->view('admin/footer', $footer_data);
	}

	/**
	* add a new contract into the system
	* accessible via /admin/contract/add
	*/
	public function add($customer_id)
	{
		//$this->output->enable_profiler(TRUE);	
		// page level data
		$header_data['title'] = "View Contracts";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/view.js");
		// load data from database
		$data["customer_id"] = $customer_id;
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data['customers'] = $this->customermodel->get_customers();
		
		
		// code igniter framework - validation rules
		$this->form_validation->set_rules('contract_number', 'Contract Number', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// validate
		if ($this->form_validation->run() == FALSE){
			$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
			// load view
			$this->load->view('admin/header', $header_data);
			$this->load->view('admin/contract/view', $data);
			$this->load->view('admin/footer', $footer_data);		
		}else{
			// retrieve post variables
			$customer = $customer_id;
			$carrier = $this->input->post('carrier');
			$contract_number = $this->input->post('contract_number');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			// add contract to database
			$this->contractmodel->add_contract($contract_number, $this->get_sql_date($start_date), $this->get_sql_date($end_date) ,$customer,$carrier);
			// retrieve contract
			$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
			// reload the view with success message;
			$message['type'] = "success";
			$message["body"] = "Added Contract Successfully!";
			$header_data["messages"] = array($message);
			$this->load->view('admin/header', $header_data);
			$this->load->view('admin/contract/view', $data);
			$this->load->view('admin/footer', $footer_data);
		}
		
	}
	
	public function delete($customer_id, $contract_id)
	{
		
		// delete contract
		$this->contractmodel->delete($contract_id);
		
		
		$header_data['title'] = "View Contracts";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/view.js");
		// load data from database
		$data["customer_id"] = $customer_id;
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data['customers'] = $this->customermodel->get_customers();
		$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
		// reload the view with success message;
		$message['type'] = "success";
		$message["body"] = "Deleted Contract Successfully!";
		$header_data["messages"] = array($message);
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/contract/view', $data);
		$this->load->view('admin/footer', $footer_data);
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
				$header_data['page_css'] = array('select2.css', 'admin/contract/lanes.css');
				$footer_data['scripts'] = array('select2.js','cities.selector.js', 'countries.selector.js', 'containers.selector.js', 'ports.selector.js','admin/contract/lanes.js');
				// set page data
				$data['carrier'] = $result->carrier;
				$data['carrier_id'] = $result->carrier_id;
				$data['contract_number'] = $result->contract_number;
				$data['contract_id'] = $result->contract_id;
				$data['customer'] = $result->customer;
				$data['currencies'] = $this->currencycodes->get_currency_codes();
				$data['leg_types'] = $this->referencemodel->get_leg_types();
				$data['transport_types'] = $this->referencemodel->get_transport_types();
				$data['cargo_types'] = $this->referencemodel->get_cargo_types();
				$data['currencies'] = $this->referencemodel->get_currency_codes();
				$data['container_types'] = $this->referencemodel->get_container_types($result->carrier_id);
				$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
				// save contract id for the next page
				$data['contract_id'] = $result->contract_id;
				// get the lanes for a contract
				//$data['lanes'] = $this->chargerulesmodel->get_lanes_for_contract($result->contract_id);
				// load next view
				$this->load->view('admin/header', $header_data);
				$this->load->view('admin/contract/lanes', $data);
				$this->load->view('admin/footer', $footer_data);
			}else{
				echo "Not a Valid Contract Number";
			}
		}// end if contract_number
	}
	
	// save lane to contract
	public function savelane()
	{
		$contract_id = $this->input->post('contract_id');
		$legs = $this->input->post("legs");
		$container_type = $this->input->post('container_type');
		$value = $this->input->post('value');
 		$cargo_type = $this->input->post('cargo_type');
		$effective_date = $this->get_sql_date($this->input->post('effective_date'));
		$currency_code = $this->input->post("currency");
		$this->lanemodel->addlane($contract_id, $container_type, $value, $cargo_type, $effective_date, $legs, $currency_code);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode("success"));
	}
	
	public function deletelane()
	{
		$lane_id = $this->input->post("lane_id");
		$this->lanemodel->deletelane($lane_id);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode("success"));
	}
	
	public function getlanes($contract_id){
		//$this->output->enable_profiler(TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->lanemodel->getlanes($contract_id)));
	}
	
	public function getlanestest($contract_id=12)
	{
			$this->output->enable_profiler(TRUE);
			//echo json_encode($this->lanemodel->getlanes($contract_id));
			json_encode($this->lanemodel->getlanes($contract_id));
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
	/*
	public function getlanes()
	{
		$this->output->enable_profiler(TRUE);
		echo json_encode($this->chargerulesmodel->get_lanes_for_contract(1) );
	}*/
	
	function get_sql_date($date)
	{
		$format = "m/j/Y";
		$sql_date = date_parse_from_format ( $format , $date );
		return $sql_date['year']."-".$sql_date['month']."-".$sql_date['day'];
		
	}

}

