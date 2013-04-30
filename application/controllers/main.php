<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('map');
		
		$this->load->model('querymodel');
		$this->load->model('lanemodel');
    }

	public function index()
	{
		$header_data['title'] = "Query Rates";
		$header_data['page_css'] = array('query.css', 'select2.css');
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
	
	public function signin()
	{
		$this->load->view("signin");
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