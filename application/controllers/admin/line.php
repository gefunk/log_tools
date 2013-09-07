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
		
		$this->load->library('amfitirlog');
	}


	/**
	 * Brings up Add New Line Item view
	 */
	public function add($contract_id){
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
		$footer_data["scripts"] = array("admin/contract/line/add.js", "admin/contract/line/line_item_calculate.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/line/add', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
		
		
	}


	
	/**
	 * Saves a new line item into the DB
	 */
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
		
		
		$inserted_rate_ids = array();
		
		// insert rate items
		foreach($rate_search_items as $item){
			$origin = intval($item['origin']['id']);
			$destination = intval($item['destination']['id']);
			$container = intval($item['container']['type']);
			$value = floatval($item['container']['value']);
			$currency = intval($item['container']['currency']);
			
			
			$inserted_rate_ids[] = $this->ratemodel->add_rate(
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
		
		// TODO: get the user from the session cookie
		$this->amfitirlog->log_new_contract_line_item($line_item_id, "admin", $contract_id, $inserted_rate_ids);
		
	}

	/**
	 * Default Landing Page for Line Items
	 * Should display a list of all Line Itemsdb.
	 */
	public function manage($contract_id)
	{
		
		redirect('admin/line/add/'.$contract_id);
		//$line_items = $this->lineitemmodel->get_line_items_for_contract($contract_id);
		
		/**
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
		 * */
		 
		
	}
	
	
	



} // end controller