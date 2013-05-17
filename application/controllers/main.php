<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('map');
		
		$this->load->model('querymodel');
		$this->load->model('lanemodel');
		$this->load->model('customermodel');
		$this->load->library("ion_auth");
    }

	public function index()
	{
		$header_data['title'] = "Query Rates";
		$header_data['page_css'] = array('query.css', 'lib/select2.css');
		$footer_data['scripts'] = array('select2.js', 'query.js', 'cities.selector.js', 'ports.selector.js');
		$this->load->view('header', $header_data);
		$this->load->view('queryrates');
		$this->load->view('footer', $footer_data);
	}
	
	public function searchlanes($origin_city_id, $destination_city_id, $customer_id)
	{
		$search_lanes = ($this->querymodel->search_lanes($origin_city_id, $destination_city_id, $customer_id));
		$search_lanes['lane_detail'] = $this->lanemodel->get_lanes_by_lane_id($search_lanes['lanes']);
			$this->output
			    ->set_content_type('application/json')
			    ->set_output(json_encode($search_lanes));
	}
	
	public function signin(){
		$this->load->view("signin");
	}
	
	public function signin_local($subdomain){
		$this->load->model("customermodel");
		$customer = $this->customermodel->get_customer_by_domain($subdomain);
		// forward to customer login page
		$customer_data = array(
			"customer_id" => $customer["id"],
			"customer_name" => $customer["name"]
		);
		$this->session->set_userdata($customer_data);
		$data["customer_name"] = $customer["name"];
		$this->load->view("signin", $data);
	}
	
	public function login_user($customer_id)
	{
		$identity = $this->input->post("identity");
		$password = $this->input->post("password");
		$remember = $this->input->post("remember");
		if($this->ion_auth->login($identity, $password, $remember)){
			//successful login
			
		}else{
			// unsuccessful login
		}
		
	}
	
	public function register()
	{
		$customer_id = $this->session->userdata('customer_id');
		$this->session->set_userdata('customer_group' ,$this->customermodel->get_customer_group($customer_id));
		$data["customer_name"] = $this->session->userdata("customer_name");
		$this->load->view("register", $data);
	}
	
	/**
	* register user
	*/
	public function register_user(){

		$customer_group = $this->session->userdata("customer_data");
		$password = $this->input->post("password");
		$email = $this->input->post("email");
		$first_name = $this->input->post("first_name");
		$last_name = $this->input->post("last_name");
		$phone_no = $this->input->post("phone_no");
		$additional_data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone' => $phone_no
		);
		// group 2 signifies members, customer groups the user by customer
		$group = array($customer_group);
		$this->ion_auth->register($email, $password, $email, $additional_data, $group);
		
	}
	
	
	public function testsearchlanes()
	{
		$this->output->enable_profiler(TRUE);
		$origin_city_id = 2457684;
		$destination_city_id = 2338691;
		$customer_id = 9;
		$search_lanes = ($this->querymodel->search_lanes($origin_city_id, $destination_city_id, $customer_id));
		$search_lanes['lane_detail'] = $this->lanemodel->get_lanes_by_lane_id($search_lanes['lanes']);
		echo var_dump($search_lanes);
		
	}
	
	public function testclosest($city_id)
	{
		$this->output->enable_profiler(TRUE);
		echo var_dump($this->querymodel->find_closest_ports_to_city($city_id, 9));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */