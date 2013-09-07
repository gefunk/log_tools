<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RateModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }
	
	
	function add_rate(
			$line_item_id,
			$origin,
			$destination,
			$container,
			$currency,
			$value,
			$status,
			$effective,
			$enddate,
			$customer,
			$carrier,
			$cargo){
		
		$data = array(
			'line_item_id' => "$line_item_id",
			'origin' => $origin,
			'destination' => $destination,
			'container' => $container,
			'value' => $value,
			'status' => $status,
			'effective' => $effective,
			'enddate' => $enddate,
			'customer' => $customer,
			'carrier' => $carrier,
			'cargo' => $cargo,
			'currency' => $currency);
		
		$this->db->insert('rate_search', $data);
		return $this->db->insert_id();					
	}

}