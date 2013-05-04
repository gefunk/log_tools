<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
		$this->load->model('referencemodel');
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
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_container_types($carrier_id, TRUE)));
	}
	
	public function list_of_currency_codes()
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_currency_codes(TRUE)));
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
	
	public function list_of_charge_codes($carrier_id)
	{
			$this->output
			    ->set_content_type('application/json')
			    ->set_output( json_encode($this->referencemodel->get_charge_codes_for_carrier($carrier_id)));
	}
	

}
