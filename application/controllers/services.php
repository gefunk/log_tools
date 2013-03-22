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
	
	public function list_of_container_types()
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_container_types(TRUE)));
	}
	
	public function list_of_currency_codes()
	{
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($this->referencemodel->get_currency_codes(TRUE)));
	}

}