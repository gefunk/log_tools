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

		$this->load->model("contractmodel");
		$this->load->model('customermodel');
		$this->load->model("admin/currencycodes");
		$this->load->model("referencemodel");
		$this->load->model("lanemodel");
		$this->load->model("rulemodel");
		$this->load->model('assetstorage');
		$this->load->model("attachments/attachmentmodel");
		
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
	*
	* CODE - CONTRACT RELEVANT SECTION
	*
	*/
	
	
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
			//retrieve post variables
			$customer = $customer_id;
			$carrier = $this->input->post('carrier');
			$contract_number = $this->input->post('contract_number');
			$start_date = $this->input->post('start_date');
			$end_date = $this->input->post('end_date');
			
			$attachment_prefix = "customer-".$customer_id."/"."contract-".$contract_number."/";
			$attachment_id = NULL;
			if(isset($_FILES) && !empty($_FILES) && !empty($_FILES["contract-file"])){
				// get an attachment id
				$attachment_id = $this->attachmentmodel->get_next_attachment_id("contract");
				// loop through files
				for($i=0; $i<count($_FILES['contract-file']); $i++){
					if(isset($_FILES['contract-file']['name'][$i]) &&
						$_FILES['contract-file']['error'][$i] == UPLOAD_ERR_OK)
					{
						// get file info for each file
						$name = $_FILES['contract-file']['name'][$i];
						$content_type = $_FILES['contract-file']['type'][$i];
						$temp_path = $_FILES['contract-file']['tmp_name'][$i];
						$remote_path = $attachment_prefix.$name;
						$response = $this->assetstorage->upload_asset($temp_path, $remote_path);
						// add asset to db, if it was succesfully uploaded
						if($response["success"]){
							$this->attachmentmodel->add_attachment_for_id(
								$attachment_id, 
								$remote_path, 
								$content_type, 
								(($response["local"]) ? 0 : 1)
							);
						}
					}
					
				}
			}
			
			
			// add contract to database
			$this->contractmodel->add_contract(
									$contract_number, 
									$this->get_sql_date($start_date), 
									$this->get_sql_date($end_date),
									$customer,
									$carrier, 
									$attachment_id, 
									$attachment_prefix
								);
			
			
			
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
	
	/**
	* delete a contract from the system
	*/
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
	* SECTION - LANES
	*/
	
	/**
	* load the lanes UI
	*/
	public function lanes($contract_number)
	{
		if(isset($contract_number)){
			$result = $this->contractmodel->get_contract_from_number($contract_number);
			if(isset($result)){
				$header_data['title'] = "Add Rules to Contract";
				$header_data['page_css'] = array('select2.css', 'admin/contract/lanes.css', 'lib/bootstrap-wysihtml5.css');
				$footer_data['scripts'] = array('select2.js','custom-selectors/cities.selector.js', 'custom-selectors/countries.selector.js', 'custom-selectors/containers.selector.js', 'custom-selectors/ports.selector.js', 'custom-selectors/tariff.selector.js', 'admin/contract/lanes.js', 'wysihtml5-0.3.0.js' ,'bootstrap-wysihtml5-0.0.2.js');
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
	
	
	
	/**
	* SECTION - CHARGE RULES
	*/
	
	/**
	* rules
	**/
	public function rules($contract_number)
	{
			if(isset($contract_number)){
				$result = $this->contractmodel->get_contract_from_number($contract_number);
				if(isset($result)){
					$header_data['title'] = "Add Charges to Contract";
					$header_data['page_css'] = array('select2.css', 'admin/contract/rule.css');
					$footer_data['scripts'] = array('select2.js','custom-selectors/cities.selector.js', 'custom-selectors/countries.selector.js', 'custom-selectors/containers.selector.js','custom-selectors/ports.selector.js','custom-selectors/tariff.selector.js','custom-selectors/service.selector.js','admin/contract/rules.js');
					// set page data
					$data['carrier'] = $result->carrier;
					$data['carrier_id'] = $result->carrier_id;
					$data['contract_number'] = $result->contract_number;
					$data['contract_id'] = $result->contract_id;
					$data['customer'] = $result->customer;
					$data['currencies'] = $this->currencycodes->get_currency_codes();
					$data['leg_types'] = $this->referencemodel->get_leg_types();
					$data['transport_types'] = $this->referencemodel->get_transport_types();
					$data['cargo_types'] = $this->referencemodel->get_cargo_types($result->customer_id, $result->carrier_id);
					$data['charge_codes'] = $this->referencemodel->get_charge_codes_for_carrier($result->carrier_id);
					$data['currencies'] = $this->referencemodel->get_currency_codes();
					$data['container_types'] = $this->referencemodel->get_container_types($result->carrier_id);
					$data['tariffs'] = $this->referencemodel->get_tarriffs_for_carrier($result->carrier_id);
					$data['services'] = $this->referencemodel->get_services_for_carrier($result->carrier_id);
					$data['conditions'] = $this->referencemodel->get_charge_conditions();
					$data['application_types'] = $this->referencemodel->get_application_types();
					$data['customer_default_currency_code'] = $this->customermodel->get_customer_default_currency($result->customer_id);
					// save contract id for the next page
					$data['contract_id'] = $result->contract_id;
					// get the lanes for a contract
					//$data['lanes'] = $this->chargerulesmodel->get_lanes_for_contract($result->contract_id);
					// load next view
					$this->load->view('admin/header', $header_data);
					$this->load->view('admin/contract/rules', $data);
					$this->load->view('admin/footer', $footer_data);
				}else{
					echo "Not a Valid Contract Number";
				}
			}// end if contract_number
	}
	
	
	public function savechargerule()
	{
		// TODO: implementation
		$value = $this->input->post("value");
		$charge_code = $this->input->post("charge_code");
		$contract = $this->input->post("contract");
		$application = $this->input->post("charge_application_type");
		$currency = $this->input->post("currency");
		$effective = $this->get_sql_date($this->input->post("effective"));
		$expires = $this->get_sql_date($this->input->post("expires"));
		
		$conditions = $this->input->post("conditions");
		
		echo var_dump($conditions);
	}
	
	
	public function getlanesaffected()
	{
		//$this->output->enable_profiler(TRUE);
		$conditions = json_decode($this->input->post('conditions'));
		
		//echo var_dump($conditions);
		
	
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->lanemodel->get_lanes_affected_by_charge_rule($conditions)));
		
		
	}
	
	public function testsavechargerule()
	{
		$this->output->enable_profiler(TRUE);
		// data for charge
		$value = 500.00;
		$charge_code = 12;
		$contract = 13;
		$application = 3;
		$currency = 5;
		$effective = "2013-01-01";
		$expires = "2013-12-31";
		
		// data for conditions
		$condition["condition"] = 13;
		$condition["values"] = array(
			"value" => 75253
		);
		
		$conditions = array($condition);
		 
		$this->rulemodel->add_charge_rule($value, $charge_code, $contract, $application, $currency, $effective, $expires, $conditions);
	}
	
	
	public function testgetchargerule($charge_rule_id)
	{
		$this->output->enable_profiler(TRUE);
		$rules = $this->rulemodel->get_charge_rule_by_id($charge_rule_id);
		echo var_dump($rules);
	}
	public function testgetchargerulejson($charge_rule_id=2)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->rulemodel->get_charge_rule_by_id($charge_rule_id)));
		
	}
	
	public function testgetchargerulebycontractid($contract_id=13)
	{
		$this->output->enable_profiler(TRUE);
		$rules = $this->rulemodel->get_charge_rule_by_contract_id($contract_id);
		echo var_dump($rules);
	}
	
	/**
	* UTILITIES
	*/
	function get_sql_date($date)
	{
		$format = "m/j/Y";
		$sql_date = date_parse_from_format ( $format , $date );
		return $sql_date['year']."-".$sql_date['month']."-".$sql_date['day'];
		
	}

}

