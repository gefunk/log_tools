<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_customers()
	{
		$this->db->select('id, name')->from('customers'); 
		$query = $this->db->get();
	    return $query->result();
	}
	
	function get_users($customer_id){
		$this->db->select('id, first_name, last_name, email, phone_num')->from('users');
		$query = $this->db->get();
	    return $query->result();
	}
	
}
/** end model **/