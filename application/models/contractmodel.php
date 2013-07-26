<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContractModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }

	/**
	* add a new contract to the database
	**/
	function add_contract(
				$contract_number, 
				$start_date, 
				$end_date,
				$customer,
				$carrier,
				$attachment,
				$attachment_prefix)
	{
		$data = array(
			'start_date' => $start_date, 
			'end_date' => $end_date,
			'number' => $contract_number,
			'customer' => $customer,
			'carrier' => $carrier,
			'attachment' => $attachment,
			'attachment_prefix' => $attachment_prefix);
		$this->db->insert('contracts', $data);
		$this->cache->delete('get_contracts_for_customer-'.$customer);
	}
	
	/**
	* get contract information from contract number
	* @param contract number of the contract trying to retrieve
	* @return customer name, carrier name, contract number
	*/
	function get_contract_from_number($contract_number)
	{
		$key = 'get_contract_from_number-'.$contract_number;
		if(! $result = $this->cache->get($key)){
			
			$this->db->select("customers.name as customer, customers.id as customer_id, contracts.id as contract_id, ref_carriers.name as carrier, ref_carriers.id as carrier_id, contracts.number as contract_number, contracts.end_date, contracts.start_date");
			$this->db->from("contracts");
			$this->db->join('ref_carriers', 'contracts.carrier = ref_carriers.id');
			$this->db->join('customers', 'contracts.customer = customers.id');
			$this->db->where("contracts.number", $contract_number);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$result = $query->row();
				$this->cache->save($key, $result, WEEK_IN_SECONDS);
			}else
				$result = null;
				
		}
		return $result;
	}
	
	/**
	* get contract information from contract number
	* @param contract number of the contract trying to retrieve
	* @return customer name, carrier name, contract number
	*/
	function get_contract_from_id($contract_id)
	{
		$key = 'get_contract_from_id-'.$contract_id;
		if(! $result = $this->cache->get($key)){
			
			$this->db->select("customers.name as customer, customers.id as customer_id, contracts.id as contract_id, ref_carriers.name as carrier, ref_carriers.id as carrier_id, contracts.number as contract_number, contracts.end_date, contracts.start_date");
			$this->db->from("contracts");
			$this->db->join('ref_carriers', 'contracts.carrier = ref_carriers.id');
			$this->db->join('customers', 'contracts.customer = customers.id');
			$this->db->where("contracts.id", $contract_id);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$result = $query->row();
				$this->cache->save($key, $result, WEEK_IN_SECONDS);
			}else
				$result = null;
				
		}
		return $result;
	}
	
	/*
	* get all contracts for a customer
	*/
	function get_contracts_for_customer($customer_id)
	{
		$key = 'get_contracts_for_customer-'.$customer_id;
		if(! $result = $this->cache->get($key)){
			$this->db->select("c.id, start_date, end_date, number, rcarriers.name as carrier_name, rcarriers.image, cu.number_of_pages");
			$this->db->from("contracts c");
			$this->db->join('ref_carriers rcarriers', 'rcarriers.id = c.carrier');
			$this->db->join('contract_uploads cu', 'cu.contract = c.id');
			$this->db->where("c.customer", $customer_id);
			$this->db->where("c.deleted", "0");
			$this->db->order_by("cu.upload_time", "desc");
			$this->db->limit(1);		
			$query = $this->db->get();
			$result = $query->result();
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		return $result;
	}
	
	
	function get_contract_dates($contract_number)
	{
		$this->db->select("effective_date as start_date, expiration as end_date");
		$this->db->from("contracts");
		$this->db->where("contracts.number", $contract_number);
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return NULL;
		}
	}
	
	
	/*
	* delete contract 
	*/
	function delete($contract_id)
	{
		$contract = $this->get_contract_from_id($contract_id);
		$this->cache->delete('get_contract_from_number-'.$contract->contract_number);
		$this->cache->delete('get_contracts_for_customer-'.$contract->customer_id);
		$this->cache->delete('get_contract_from_id-'.$$contract->contract_id);
		$this->db->update('contracts', array("deleted" => "1"), array('id' => $contract_id));
		
	}
	
	
}
/** end model **/