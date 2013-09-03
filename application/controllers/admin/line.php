<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(ENTITIES_DIR  . "lineitementity.php");
require_once(ENTITIES_DIR  . "linecontainer.php");
require_once(ENTITIES_DIR . "lineitemcharge.php");

class Line extends MY_Admin_Controller {

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
		$this->load->model('cargomodel');
		$this->load->model('ratemodel');
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
		
		$contract_id = intval($this->input->post("contract_id"));
		
		$cargo = $this->input->post('cargo');
		$origin = intval($this->input->post('origin'));
		$origin_type = $this->input->post('origin_type');
		$destination = intval($this->input->post('destination'));
		$destination_type = $this->input->post('destination_type');
	
		$bootstrap_date_format = "m/d/Y";
		$effective = DateTime::createFromFormat($bootstrap_date_format,$this->input->post("effective_date"));
		$expires =  DateTime::createFromFormat($bootstrap_date_format,$this->input->post('expires'));
		
		$containers = $this->input->post("containers");
		// clean up containers array
		$data_containers = array();
		foreach($containers as $key => $element){
			$data_containers[] = array(
				'currency' => intval($element['currency']),
				'type' => intval($element['type']),
				'value' => floatval($element['value'])
			);
			
			
		}
		
 		// get line item id from inserting the last row
		$line_item_id = $this->lineitemmodel->add_line_item($origin, $origin_type, $destination, $destination_type, $effective->getTimestamp(), $expires->getTimestamp(), $data_containers, $contract_id, $cargo);
		// the search items we are going to insert into the rate_search table
		$rate_search_items = $this->input->post('items');
		// status is active for all
		$status = 0;
		// format effective dates for sql insert
		$effective = $effective->format("Y-m-d");
		$expires = $expires->format("Y-m-d");
		
		// fill in the other data that we don't get from the page, that is needed for rate search
		$contract_data = $this->contractmodel->get_contract_from_id($contract_id);
		$customer = $contract_data->customer_id;
		$carrier = $contract_data->carrier_id;
		
		// insert rate items
		foreach($rate_search_items as $item){
			$origin = intval($item['origin']['id']);
			$destination = intval($item['destination']['id']);
			$container = intval($item['container']['type']);
			$value = floatval($item['container']['value']);
			$currency = intval($item['container']['currency']);
			
			
			$this->ratemodel->add_rate(
								$line_item_id,
								$origin,
								$destination,
								$container,
								$currency,
								$value,
								$status,
								$effective,
								$expires,
								$customer,
								$carrier,
								$cargo);
		}
		
		
		
				
		
		
														
		
	}

	public function all($contract_id)
	{
		//$line_items = $this->lineitemmodel->get_line_items_for_contract($contract_id);
		
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['containers'] = $this->containermodel->get_containers_for_contract($contract_id);
		$data['cargo_types'] = $this->cargomodel->get_cargo_types_for_contracts($contract_id);
		$data['currencies'] = $this->referencemodel->get_currency_codes();
		$data['effective_date'] = date( 'm/d/Y', strtotime($data['contract']->start_date)); 
		$data['expires_date'] = date( 'm/d/Y', strtotime($data['contract']->end_date));
		$data['page'] = 'contracts';
		$header_data['title'] = "Line Items";
		$header_data['page_css'] = array('lib/famfamflag.css', 'admin/contract/line/manage.css');
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/line/all.js", "admin/contract/line/line_item_calculate.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/line/manage', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
		
	}
	
	
	



} // end controller