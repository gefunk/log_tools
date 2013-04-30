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
		$this->db->select('c.id, c.name, c.subdomain, rc.code as currency_code');
		$this->db->from('customers c'); 
		$this->db->join("ref_currency_codes rc", "c.default_currency = rc.id");
		
		$query = $this->db->get();
	    return $query->result();
	}
	
	function add($customer_name, $currency_code, $subdomain){
		$data = array('name' => $customer_name, 'default_currency' => $currency_code, 'subdomain' => $subdomain);
		$this->db->insert('customers', $data);
	}
	
	
	function get_users($customer_id){
		$this->db->select('id, first_name, last_name, email, phone_num')->from('users');
		$query = $this->db->get();
	    return $query->result();
	}
	
	function get_customer_default_currency($customer_id)
	{
		$this->db->select('default_currency')->from('customers')->where("id", $customer_id);
		$query = $this->db->get();
		$row = $query->row();
		return $row->default_currency;
	}
	
	function get_customer_by_domain($domain)
	{
		$this->db->select("id")->from("customers")->where("subdomain", $domain);
		$query = $this->db->get();
		$customer_id = NULL;
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $row)
		   {
				$customer_id = $row->id;
			}
		}
		return $customer_id;
	}
}
/** end model **/