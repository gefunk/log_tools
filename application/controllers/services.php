<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('referencemodel');
		$this->load->model('portmodel');
		$this->load->model('portgroupmodel');
	}
	
	
	public function cache_info()
	{
		var_dump($this->cache->cache_info());
	}
	
	/**
	* open list of all countries in database
	* @return json encoded value of all countries in table
	*/
	public function list_of_countries()
	{
		
		
		$countries = $this->referencemodel->get_country_codes(TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($countries));
	}
	
	public function list_of_container_types($carrier_id)
	{

		$container_types = $this->referencemodel->get_container_types($carrier_id, TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($container_types));
	}
	
	public function list_of_currency_codes()
	{
		$currency_codes = $this->referencemodel->get_currency_codes(TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($currency_codes));
	}
	
	/**
	* pages through list of
	*
	*/
	public function list_of_cities()
	{
		//$this->output->enable_profiler(TRUE);
		
		$query = $this->input->get('query');
		$page = $this->input->get('page');
		$page_size = $this->input->get('page_size');
		
		$key = 'list_of_cities-'.$query.'-'.$page.'-'.$page_size;
		

		$cities = $this->referencemodel->search_cities($query, $page, $page_size, TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($cities));	
		
		
	}
	
	/**
	* pages through list of
	*
	*/
	public function list_of_ports()
	{
		//$this->output->enable_profiler(TRUE);
		$query = $this->input->get('query');
		$page = $this->input->get('page');
		$page_size = $this->input->get('page_size');
		
		$ports = $this->referencemodel->search_ports($query, $page, $page_size, TRUE);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($ports));	
		
		
	}
	
	public function ports_type_ahead()
	{
		//$this->output->enable_profiler(TRUE);
		$query = $this->input->get('query');
		$page_size = $this->input->get('page_size');
		
		$result = $this->referencemodel->typeahead_ports($query, $page_size);
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
		
	}
	
	public function get_port_groups($contract_id)
	{
		$result = $this->portgroupmodel->get_port_groups_for_contract($contract_id);
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
	}
	
	public function get_ports_for_group($group_id)
	{
		$result = $this->portgroupmodel->get_ports_for_group($group_id);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($result));
	}
	
	public function list_of_charge_codes($carrier_id)
	{
		$result = $this->referencemodel->get_charge_codes_for_carrier($carrier_id);
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
	
	public function list_of_tariffs($carrier_id)
	{
		$result = $this->referencemodel->get_tarriffs_for_carrier($carrier_id);
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
	
	public function list_of_carrier_services($carrier_id)
	{
		
		$result = $this->referencemodel->get_services_for_carrier($carrier_id);
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($result));
	}
	
	public function increment_port_hit_count()
	{
		$id = $this->input->post('port_id');
		$this->portmodel->up_hit_count($id);
	}
	
}
