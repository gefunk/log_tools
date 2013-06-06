<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Line extends CI_Controller {

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
				$this->load->view('admin/contract/lines', $data);
				$this->load->view('admin/footer', $footer_data);
			}else{
				echo "Not a Valid Contract Number";
			}
		}// end if contract_number
	}


} // end controller