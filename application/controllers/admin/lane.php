<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lane extends CI_Controller {

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
		$this->load->model("admin/currencycodes");
		$this->load->model("referencemodel");
		$this->load->model("lanemodel");
		$this->load->model("rulemodel");
		$this->load->model("portgroupmodel");		
		$this->load->model('assetstorage');
		$this->load->model("attachments/attachmentmodel");
		
	}

	/**
	* load the lanes UI
	*/
	public function index($contract_number=NULL)
	{
		if(isset($contract_number)){
			$result = $this->contractmodel->get_contract_from_number($contract_number);
			if(isset($result)){
				$header_data['title'] = "Add Rules to Contract";
				$header_data['page_css'] = array('select2.css', 'admin/contract/lanes.css', 'lib/bootstrap-wysihtml5.css');
				$footer_data['scripts'] = array('select2.js','custom-selectors/cities.selector.js', 'custom-selectors/countries.selector.js', 'custom-selectors/containers.selector.js', 'custom-selectors/ports.selector.js', 'custom-selectors/tariff.selector.js', 'wysihtml5-0.3.0.js' ,'bootstrap-wysihtml5-0.0.2.js', 'admin/contract/lanes.js');
				// set page data
				$data['carrier'] = $result->carrier;
				$data['carrier_id'] = $result->carrier_id;
				$data['contract_number'] = $result->contract_number;
				$data['contract_id'] = $result->contract_id;
				$data['contract_start_date'] = $result->start_date;
				$data['contract_end_date'] = $result->end_date;
				$data['customer'] = $result->customer;
				$data['currencies'] = $this->currencycodes->get_currency_codes();
				$data['leg_types'] = $this->referencemodel->get_leg_types();
				$data['transport_types'] = $this->referencemodel->get_transport_types();
				$data['cargo_types'] = $this->referencemodel->get_cargo_types($result->customer_id,$result->carrier_id);
				$data['currencies'] = $this->referencemodel->get_currency_codes();
				$data['container_types'] = $this->referencemodel->get_container_types($result->carrier_id);
				$data['tariffs'] = $this->referencemodel->get_tarriffs_for_carrier($result->carrier_id);
				$data['services'] = $this->referencemodel->get_services_for_carrier($result->carrier_id);
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
	
	/**
	* get all the lanes for this contract id
	*
	*/
	public function getlanes($contract_id){
		//$this->output->enable_profiler(TRUE);
		
		$lanes = $this->lanemodel->getlanes($contract_id);
		
		foreach($lanes as &$lane){
			$lane_id = $lane['id'];
			$lane['rules'] = $this->rulemodel->get_lane_rule_for_lane($lane_id);
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($lanes));
	}
	
	// save lane to contract
	/*
	cargo_type	1
	container_type	1
	contract_id	12
	currency	5
	effective_date	04/15/2013
	legs[0][leg_type]	1
	legs[0][location]	69334
	legs[0][transport]	1
	legs[1][leg_type]	2
	legs[1][location]	22550
	legs[1][transport]	1
	value	2025.00
	*/
	public function savelane()
	{
		$contract_id = $this->input->post('contract_id');
		$legs = $this->input->post("legs");
		$container_type = $this->input->post('container_type');
		$value = $this->input->post('value');
 		$cargo_type = $this->input->post('cargo_type');
		$effective_date = $this->input->post('effective_date');
		$expiry_date = $this->input->post('expiration_date');
		$currency_code = $this->input->post("currency");
		$service = $this->input->post("service");
		$tariff = $this->input->post("tariff");
		
		$this->lanemodel->addlane($contract_id, $container_type, $value, $cargo_type, $effective_date, $expiry_date, $legs, $currency_code, $service, $tariff);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode("success"));
	}
	
	/**
	* delete a lane from the system
	*/	
	public function deletelane()
	{
		$lane_id = $this->input->post("lane_id");
		$this->lanemodel->deletelane($lane_id);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode("success"));
	}
	
	/**
	* SECTION - LANE CHARGES
	*/
	
	/**
	* get the rules which are saved only for this lane
	*/
	public function getlanecharges($lane_id)
	{
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($this->rulemodel->get_rule_for_lane($lane_id)));
	}
	
	
	/*
	* add a rule to a lane
	*/
	public function savelanecharge()
	{
		//$this->output->enable_profiler(TRUE);
		$lane_id = $this->input->post('lane_id');
		$charge_code = $this->input->post('charge_code');
		$effective = $this->get_sql_date($this->input->post('effective'));
		$expires = $this->get_sql_date($this->input->post('expires'));
		$currency = $this->input->post('currency');
		$value = $this->input->post('amount');
		$notes = $this->input->post('notes');		
		// save into db
		$rule = $this->rulemodel->add_lane_rule($lane_id,$charge_code, $effective,$expires, $currency,$value,$notes);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($rule));
	}
	
	/*
	* delete a lane charge 
	*/
	public function deletelanecharge()
	{
		$charge_lane_id = $this->input->post("charge_lane_id");
		$this->rulemodel->delete_lane_rule($charge_lane_id);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode("success"));
	}
	
	public function testdeletelane(){
		$this->output->enable_profiler(TRUE);
		$this->lanemodel->deletelane("24");
	}
	


} // end controller