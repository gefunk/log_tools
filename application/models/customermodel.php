<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
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
		$customer_id = $this->db->insert_id();
		// create a new customer id in mongodb
		$this->mongo_db->insert("customers", array("_id" => $customer_id, "contracts" => array()));
	}
	
	function get_customer_by_id($customer_id){
		$this->db->select('c.id, c.name, c.subdomain, rc.code as currency_code');
		$this->db->from('customers c'); 
		$this->db->join("ref_currency_codes rc", "c.default_currency = rc.id");
		$this->db->where("c.id", $customer_id);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}else{
			return FALSE;
		}
	    	
	}
	
	function get_customer_from_contract($contract_id){
		$this->db->select("customer")->from("contracts")->where("id", $contract_id);
		
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$customer_id = $query->row()->customer;
			$this->db->select('c.id, c.name, c.subdomain, rc.code as currency_code');
			$this->db->from('customers c');
			$this->db->join("ref_currency_codes rc", "c.default_currency = rc.id");
			$this->db->where("c.id", $customer_id);
		
			$query = $this->db->get();
			if ($query->num_rows() > 0){
				return $query->row();
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	function get_users($customer_id){
		$this->db->select('id, first_name, last_name, email, phone_num')->from('users');
		$query = $this->db->get();
	    return $query->result();
	}
	
	function get_customer_default_currency($customer_id)
	{
		$key = 'get_customer_default_currency-'.$customer_id;
		if(! $result =  $this->cache->get($key)){
			$this->db->select('default_currency')->from('customers')->where("id", $customer_id);
			$query = $this->db->get();
			$row = $query->row();
			$result = $row->default_currency;
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		return $result;
	}
	
	function get_customer_by_domain($domain)
	{
		$key = 'get_customer_by_domain-'.$domain;
		if(! $customer = $this->cache->get($key)){
		
			$this->db->select("id, name")->from("customers")->where("subdomain", $domain);
			$query = $this->db->get();
			$customer = array();
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
			   {
					$customer["id"] = $row->id;
					$customer["name"] = $row->name;
				}
			}
			$this->cache->save($key, $customer, WEEK_IN_SECONDS);
		}
		return $customer;
	}
	
	function get_subdomain_from_id($id)
	{
		$key = 'get_subdomain_from_id-'.$domain;
		if(! $result = $this->cache->get($key)){
			$this->db->select("subdomain")->from("customers")->where("id", $id);
			$query = $this->db->get();
			$result = NULL;
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
			   {
					$result = $row->group;
				}
			}
			if(isset($result))
				$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		return $result;
	}
	
	
}
/** end model **/