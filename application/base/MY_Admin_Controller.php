<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Admin_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//if user doesnt have the session redirect them out NOW!
		if(!$this->auth->isAdminLoggedIn()){
			// redirect them to login
			redirect("admin/login", "refresh");
		}	
	}
	
	/**
	* UTILITIES
	* convert bootstrap date to SQL date 
	*/
	function get_sql_date($date)
	{
		$format = "m/j/Y";
		$sql_date = date_parse_from_format ( $format , $date );
		return $sql_date['year']."-".$sql_date['month']."-".$sql_date['day'];

	}
	
	function get_mongo_date($date){
		$format = "m/j/Y";
		$mongo_date = DateTime::createFromFormat ( $format , $date );
		return new MongoDate($mongo_date->getTimestamp());
	}
}