<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_In_Controller {

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
		$header_data['page_css'] = array('lib/famfamflag.css','carriers.css','ui-elements.css','main/main.css');
		$footer_data['selected_link'] = "rates";
		$footer_data['scripts'] = array('main/main.js');
		$this->load->view('header', $header_data);
		$this->load->view('main');
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
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */