<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('referencemodel');
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy')); 
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
		
		if(! $countries = $this->cache->get('list-countries')){
			$countries = $this->referencemodel->get_country_codes(TRUE);
			$this->cache->save('list-countries', $countries, WEEK_IN_SECONDS);	
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($countries));
	}
	
	public function list_of_container_types($carrier_id)
	{
		$key = 'container-types-'.$carrier_id;
		if(! $container_types = $this->cache->get($key)){
			$container_types = $this->referencemodel->get_container_types($carrier_id, TRUE);
			$this->cache->save($key, $container_types, DAY_IN_SECONDS);
		}
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($container_types));
	}
	
	public function list_of_currency_codes()
	{
		$key = 'currency_codes';
		if(! $currency_codes = $this->cache->get($key)){
			$currency_codes = $this->referencemodel->get_currency_codes(TRUE);
			$this->cache->save($key, $currency_codes, WEEK_IN_SECONDS);
		}
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
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->search_cities($query, $page, $page_size, TRUE)));	
		
		
	}
	
	public function test_list_of_cities(){
		$this->output->enable_profiler(TRUE);
		$query = 'Atlanta GA';
		$page = 1;
		$page_size = 10;
		echo json_encode($this->referencemodel->search_cities($query, $page, $page_size, TRUE));
		
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
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->search_ports($query, $page, $page_size, TRUE)));	
		
		
	}
	
	public function ports_type_ahead()
	{
		//$this->output->enable_profiler(TRUE);
		$query = $this->input->get('query');
		$page_size = $this->input->get('page_size');
		
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->typeahead_ports($query, $page_size)));
		
	}
	
	public function port_groups($contract_id)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_port_groups($contract_id)));
	}
	
	public function get_ports_for_group($group_name, $contract)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_ports_for_group($group_name, $contract)));
	}
	
	public function list_of_charge_codes($carrier_id)
	{
			$this->output
			    ->set_content_type('application/json')
			    ->set_output( json_encode($this->referencemodel->get_charge_codes_for_carrier($carrier_id)));
	}
	
	public function list_of_tariffs($carrier_id)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($this->referencemodel->get_tarriffs_for_carrier($carrier_id)));
	}
	
	public function list_of_carrier_services($carrier_id)
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode($this->referencemodel->get_services_for_carrier($carrier_id)));
	}
}
