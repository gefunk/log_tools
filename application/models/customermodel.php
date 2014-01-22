<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	 * get all customers for amfitir
	 */
	function get_all()
	{		
		return $this->convert_mongo_result_to_object($this->mongo->db->customers->find());

	}
	
	function add($customer_name, $currency_code, $subdomain){
		$data = array('name' => $customer_name, 'currency' => $currency_code, 'subdomain' => $subdomain);
		$this->mongo->db->customers->insert($data);
	}
	
	/**
	 * get a customer by id
	 * @param $customer_id the id of the customer you want to retrieve
	 */
	function get_by_id($customer_id){
		return  $this->convert_mongo_result_to_object($this->mongo->db->customers->findOne(array("_id" => new MongoId($customer_id))));		
	}
	
	
	/**
	 * get customer id from contract
	 */
	function get_id_from_contract($contract_id){
		$contract_query = array("_id" => new MongoId($contract_id));
		$contract_projection = array('customer');
		$customer_id = $this->mongo->db->contracts->findOne($contract_query, $contract_projection);
		if($customer_id)	
			return $customer_id['customer'];
		
		return NULL;

	}
	
	function get_from_contract($contract_id){
		return $this->get_by_id($this->get_id_from_contract($contract_id));
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
			$query = array("_id" => $customer_id);
			$projection = array('$elemMatch' => 'currency');
			$result = (object) $this->mongo->db->customers->findOne($query, $projection);
			if($result)
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