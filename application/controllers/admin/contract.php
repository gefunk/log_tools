<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends MY_Admin_Controller {

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
		$this->load->model('lineitem/containermodel');
		$this->load->model("admin/currencycodes");
		$this->load->model("referencemodel");
		$this->load->model("lanemodel");
		$this->load->model("rulemodel");
		$this->load->model("portgroupmodel");
		$this->load->model('assetstorage');
		$this->load->model("attachments/attachmentmodel");
		$this->load->model("attachments/datastore");

		$this->load->library("contracts");
		$this->load->library("async");

	}

	public function all($customer_id)
	{
		//$this->output->enable_profiler(TRUE);
		
		$header_data['title'] = "All Contracts";
		
		$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
		$data["customer"] =  $this->customermodel->get_customer_by_id($customer_id);
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data['page'] = 'contracts';
		
		$footer_data["scripts"] = array("admin/contract/view.js", "admin/contract/upload.js");

		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/landing', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}

	
	public function manage($contract_id){
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data["customer"] =  $this->customermodel->get_customer_by_id($data['contract']->customer_id);
		$data['page'] = 'contracts';
		
		$header_data['title'] = "Contract - ".$data['contract']->number;
		$footer_data = NULL;
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/manage', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}
	
	
	/**
	 * Add contract view
	 */
	public function add($customer_id){
		
		
		$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
		$data["customer"] =  $this->customermodel->get_customer_by_id($customer_id);
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data['page'] = 'contracts';
		
		$header_data['title'] = "All Contracts";
		$footer_data["scripts"] = array("admin/contract/view.js", "admin/contract/upload.js");

		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/new', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}
	
	
	
	/**
	 * Manage ports view
	 */
	public function ports($contract_id)
	{
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['port_groups'] = $this->portgroupmodel->get_port_groups_for_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$header_data['title'] = "Manage Ports";
		$header_data['page_css'] = array('lib/famfamflag.css', "admin/contract/port_groups.css");
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/ports.js");
		$data['page'] = 'contracts';
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/ports', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
		
	}
	
	public function document($contract_id)
	{
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Manage Documents";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/ports.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/document', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
		
	}

	public function containers($contract_id){
		//$this->output->enable_profiler(TRUE);
		$data['customer'] = $this->customermodel->get_customer_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['container_types'] = $this->containermodel->get_ref_container_types();
		$data['containers'] = $this->containermodel->get_containers_for_contract($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Manage Containers";
		
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/container.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/containers', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}

	
	public function add_container(){
		$contract= $this->input->post('contract_id');
		$text = $this->input->post('text');
		$type =$this->input->post('type');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($this->containermodel->add_container_to_contract($contract, $type, $text)));
	}
	
	public function delete_container(){
		$contract_id= $this->input->post('contract_id');
		$rational_container_id =$this->input->post('type');
		$this->containermodel->remove_container_from_contract($contract_id, $rational_container_id);
	}
	

	/**
	* add a new contract into the system
	* accessible via /admin/contract/add
	*/
	public function save($customer_id)
	{
		//retrieve post variables
		$customer = $customer_id;
		$carrier = $this->input->post('carrier');
		$contract_number = $this->input->post('contract_number');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');
			// add contract to database
		$this->contractmodel->
				add_contract(
					$contract_number,
					$this->get_sql_date($start_date),
					$this->get_sql_date($end_date),
					$customer,
					$carrier
				);
			
		redirect("admin/contract/all/".$customer_id);

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
	 * upload contract to the system
	 * @param contract_id the contract id for which this upload belongs to
	 * @param customer_id the customer for which this contract belongs to
	 */
	public function upload()
	{

		$customer_id = $this->input->post("customer_id");
		$contract_id = $this->input->post('contract_id');
		$contract_number = $this->input->post('contract_number');
					
		// standard path to retrieve and put contracts	
		$remote_path = $this->contracts->get_remote_url_for_contract($customer_id, $contract_id);

		if(isset($_FILES) && !empty($_FILES) && !empty($_FILES["contract-file"])){
			// get an attachment id
			$attachment_id = $this->attachmentmodel->get_next_attachment_id("contract");
				if(isset($_FILES['contract-file']['name']) &&
					$_FILES['contract-file']['error'] == UPLOAD_ERR_OK)
				{
						// get file info for each file
						$name = $_FILES['contract-file']['name'];
						$content_type = $_FILES['contract-file']['type'];

						// move file to uploads directory
						$upload_status = move_uploaded_file($_FILES["contract-file"]["tmp_name"], $this->config->item('upload_directory').$_FILES["contract-file"]["name"]);
						
						if($upload_status){
							$params = array(
								"contract_id" => $contract_id,
			            		"contract_filename" => $name,
			            		"remote_path" => $remote_path
							);
							// upload the file to the backend
							$this->async->post(site_url()."/attachments/async_upload_contract_remote", $params);
							$this->output
		    					->set_content_type('application/json')
		    					->set_output(json_encode(array("success" => true, "attachment_id" => $attachment_id)));
						}else{
							// upload failed
							$this->output
		    					->set_content_type('application/json')
		    					->set_output(json_encode(array("success" => false, "error" => "Move Uploaded file Failed: $upload_status")));
						}
						
					
				}else{
					$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => false, "error"=> $_FILES['contract-file']['error'])));
				}
		}else{
			$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => false, "error"=> "$_FILES parameter is empty: ".var_dump($_FILES))));
		}
			
		
	}


	public function upload_status($contract_id)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->attachmentmodel->get_latest_upload_for_contract($contract_id)));
	}

	/**
	* SECTION - PORT GROUPS
	*/

	/*
	* save port groups in table
	*/
	public function save_new_port_group()
	{
		$name = $this->input->post("name");
		$contract = $this->input->post("contract");

		
		$group_id =	$this->portgroupmodel->add_port_group($name, $contract);
		

		$response_data = array(
			"name" => $name,
			'id' => $group_id
		);

		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($response_data));
	}

	
	public function save_new_port_to_group()
	{
		$port_id = $this->input->post("port_id");
		$group_id = $this->input->post('group_id');
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->portgroupmodel->add_port_to_group($port_id, $group_id)));
	}

	public function delete_port_from_group()
	{
		$port_id = $this->input->post("port_id");
		$group_id = $this->input->post('group_id');
		$this->portgroupmodel->remove_port_from_group($port_id, $group_id);
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


	public function testgets3()
	{
		$asset = "customer-10/contract-SC836739/oocl-balship.pdf";
		echo $this->datastore->get($asset);
	}

	public function testimagicks3(){
		$asset = "customer-10/contract-SC836739/oocl-balship.pdf";
		$url = $this->datastore->get($asset);
		$uploaddir = './assets/uploads/';
		file_put_contents($uploaddir."balship.pdf", fopen($url, 'r'));

		$im = new Imagick($uploaddir."balship.pdf[0]");
		/* Convert to png */
		$im->setImageFormat( "png" );

		/* Send out */
		header( "Content-Type: image/png" );
		echo $im;
	}

	public function testpage2(){
		$uploaddir = './assets/uploads/';
		$im = new Imagick($uploaddir."balship.pdf[1]");
		/* Convert to png */
		$im->setImageFormat( "png" );

		/* Send out */
		header( "Content-Type: image/png" );
		echo $im;
	}

	public function testimagickidentify()
	{
		$uploaddir = './assets/uploads/';
		$im = new Imagick($uploaddir."balship.pdf");
		//var_dump( $im->identifyImage());
		echo "Number of Pages: ".$im->getNumberImages();
	}

	public function testgenerateimagesfrompdf()
	{
		$asset = './assets/uploads/balship.pdf';

		for($i = 0; $i < 27; $i++){
			$page = $asset."[".$i."]";
			error_log("Working on page: ".$page);
			$img = new Imagick();
			// keep it clear - set to high resolution
			$img->setResolution( 300, 300 );
			$img->readImage($page);
			/* Convert to png */
			$img->setImageFormat( "png" );
			$img->writeImage('./assets/uploads/page-'.$i.'.png');       // Write to disk
			ob_clean(); // clear buffer
			$img->destroy();
		}


	}

	public function test_async()
	{
		$params = array(
					"contract_id" => '5',
		            "contract_filename" => "anl.pdf",
		            "remote_path" => "test_async/seacorp_test"
		);
		$this->async->post(site_url()."/attachments/async_upload_contract_remote", $params);
	}

	public function showimage($image_name)
	{
		$this->output
		    ->set_content_type('png')
		    ->set_output(file_get_contents('./assets/uploads/'.$image_name.'.png'));
	}

	public function testhighlight()
	{
		$data['contract_page'] = $this->datastore->get('test_async/seacorp_test/page-7.png');
		error_log("Data: ".$data['contract_page']);
		$header_data['title'] = "Contract";
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/contract/highlight", $data);
		$this->load->view('admin/footer');

	}
	
	/**
	 * mongo db test
	 */
	public function testmongo(){
		$this->output->enable_profiler(TRUE);
		$doc = $this->mongo->db->contracts->findOne(array("_id"=>39), array("_id"=>FALSE, "containers"=>TRUE));
		var_dump( $doc['containers'] );
	}
	
	public function testmongoinsert(){
		$this->output->enable_profiler(TRUE);
		var_dump($this->mongo_db
				->where("_id", 9)
				->push("contracts", array("_id" => 30))
				->update("customers"));
	}
	
	public function testaddcontainer_to_contract($contract_id, $rational_container_id, $container_name){
		$this->output->enable_profiler(TRUE);
		$query = array("_id"=>intval($contract_id));
		$update = array('$push' => array("containers" => array("type"=> $rational_container_id, "text" => $container_name)));
		echo $contract_id. " Type: ".gettype($contract_id);
		//var_dump($this->mongo->db->contracts->findOne($query));
		var_dump($this->mongo->db->contracts->update($query, $update));
	}
	
	public function testgetcustomerfromcontract($contract_id){
		$this->output->enable_profiler(TRUE);
		var_dump($this->customermodel->get_customer_from_contract($contract_id));
	}

    public function testemail()
    {
        $this->output->enable_profiler(TRUE);

        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item("email_from"),'Do Not Reply'); // change it to yours
        $this->email->to('rahul@logiwareinc.com'); // change it to yours
        $this->email->subject('testing out emails');
		$data['subject'] = "testing out email";
		$msg = $this->load->view('email/basic', $data, true);
        $this->email->message($msg);
        $this->email->send(); 
		
        $this->email->print_debugger();


    }


	public function emailraw()
	{
		$data['subject'] = "testing out email";
		$this->load->view('email/basic', $data);
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

