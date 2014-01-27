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
		$this->load->model('cargomodel');
		$this->load->model('carriermodel');

		$this->load->library("contracts");
		$this->load->library("async");
		$this->load->library("Bcrypt");

	}

	/**
	 * show all contracts for a customer
	 * @param $customer_id - the customer you want to retrieve all results for
	 * @param $json - result in json, or to the admin contract page
	 */
	public function all($customer_id, $json=FALSE)
	{
		// retrieve all contracts for a customer	
		$contracts = $this->contractmodel->get_contracts_for_customer($customer_id);
		foreach($contracts as $contract){
			$contract->carrier = $this->carriermodel->get_carrier_by_id($contract->carrier);
		}
		
		if($json){
			$this->output
		    	->set_content_type('application/json')
		    	->set_output(json_encode($contracts));	
		}else{
			$header_data['title'] = "All Contracts";
			$data['contracts'] = $contracts;
			$data["customer"] =  $this->customermodel->get_by_id($customer_id);
			$data['page'] = 'contracts';
			$footer_data["scripts"] = array("admin/contract/view.js");

			$this->load->view('admin/header', $header_data);
			$this->load->view("admin/customers/manager-header", $data);
			$this->load->view('admin/contract/landing', $data);
			$this->load->view("admin/customers/manager-footer");
			$this->load->view('admin/footer', $footer_data);
		}
	}
	
	public function manage($contract_id){
		$contract = (object) $this->contractmodel->get_contract_from_id($contract_id);
		$contract->carrier = (object) $this->carriermodel->get_carrier_by_id($contract->carrier);
		$data['contract'] = $contract;
		$data["customer"] =  $this->customermodel->get_by_id($data['contract']->customer);
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
	 * Assign a document to a contract
	 * this will remove the document from the Inbox
	 * @param customer - customer id  of the customer 
	 * @param contract - contract id to assign the document to
	 * @param doc_id - the document id to assign
	 */
	public function assign_document(){
		$customer = $this->input->post("customer");
		$contract = $this->input->post("contract");
		$doc_id = $this->input->post("doc_id");
		$this->attachmentmodel->assign_to_contract($doc_id, $contract, $customer);
		$url = "/admin/contract/manage/$contract";
		redirect($url, "refresh");
	}
	
	/**
	 * show view of all documents for a contract
	 * @param $contract - contract id
	 */
	public function documents($contract){
		$data['contract'] =  (object) $this->contractmodel->get_contract_from_id($contract);
		$data["customer"] =  $this->customermodel->get_from_contract($contract);
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data["documents"] = $this->attachmentmodel->get_for_contract($contract);
		$data['page'] = 'contracts';
		
		$header_data['title'] = "Documents Associated With Contract ".$data['contract']->number;
		$header_data['page_css'] = array(
			"app/documents/thumbnail.css", 
			"app/documents/overlay.css", 
			"app/admin/contract/document/list.css",
			"app/admin/tag.css");
		
		$footer_data["scripts"] = array("admin/contract/document/docreader.js", "admin/contract/document/list.js");

		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/document/list', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}
	

	
	
	
	
	/**
	 * Add contract view
	 */
	public function add($customer_id){
		
		
		$data['contracts'] = $this->contractmodel->get_contracts_for_customer($customer_id);
		$data["customer"] =  $this->customermodel->get_by_id($customer_id);
		$data["carriers"] = $this->referencemodel->get_carriers();
		$data['page'] = 'contracts';
		
		$header_data['title'] = "All Documents";
		$footer_data["scripts"] = array("admin/contract/view.js");

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
		$data['customer'] = $this->customermodel->get_from_contract($contract_id);
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
	
	public function containers($contract_id){
		//$this->output->enable_profiler(TRUE);
		$data['customer'] = $this->customermodel->get_from_contract($contract_id);
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
	 * load the cargo management screen
	 * @param $contract_id - the contract for the cargo manager
	 */
	public function cargo($contract_id){
		$data['customer'] = $this->customermodel->get_from_contract($contract_id);
		$data['contract'] = $this->contractmodel->get_contract_from_id($contract_id);
		$data['page'] = 'contracts';
		$header_data['title'] = "Manage Cargo";
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/cargo.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/contract/cargo', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}
	
	/**
	 * list of cargo types
	 * @param $contract_id - the contract to get the cargo types for
	 */
	public function get_cargo_types($contract_id){
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( 
		    	json_encode($this->cargomodel->get_cargo_types_for_contracts($contract_id)));
	}
	
	/**
	 * Add a new cargo type to this contract
	 */
	public function add_cargo_type(){
		$cargo = $this->input->post('cargo');
		$contract_id = $this->input->post('contract_id');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( 
		    	json_encode($this->cargomodel->add_cargo_type_to_contract($contract_id, $cargo)));
	}
	
	/**
	 * remove a cargo type from this contract
	 */
	public function remove_cargo_type(){
		$cargo = $this->input->post('cargo');
		$contract_id = $this->input->post('contract_id');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( 
		    	json_encode($this->cargomodel->remove_cargo_type_from_contract($contract_id, $cargo)));
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
					$this->get_mongo_date($start_date),
					$this->get_mongo_date($end_date),
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
	* SECTION - TESTING
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

    
	public function test_date_parse(){
		$parsed_date = DateTime::createFromFormat("Y-m-d",'2013-11-19');
		var_dump($parsed_date);
		echo $parsed_date->format('Y-m-d');
	}
	
	public function test_contract_number($contract_number){
		$contract_number = rawurldecode($contract_number);
		var_dump($this->contractmodel->get_contract_from_number($contract_number));
	}

	function test_carriers(){
		foreach($this->referencemodel->get_carriers() as $carrier){
			echo $carrier['name'];	
			#var_dump($carrier);
		}
	}
	
	function test_customer_get($contract_id){
		var_dump($this->customermodel->get_customer_id_from_contract($contract_id));
	}
	
	function test_port_groups($contract_id){
		var_dump($this->portgroupmodel->get_port_groups_for_contract($contract_id));
	}
	
	function show_hash(){
		echo $this->bcrypt->hash("Rupinder724");
	}
	
	function verify_hash(){
		$existingHash = '$2a$07$IUaNRyGWcIHMY4ucbR0fbOLlQLLvz9HdcHOG2QR4BotY/ix./0Bwu';
		$input="Rupinder724";
		echo $this->bcrypt->verify($input, $existingHash);
	}

	public function emailraw()
	{
		$data['subject'] = "testing out email";
		$this->load->view('email/basic', $data);
	}





}

