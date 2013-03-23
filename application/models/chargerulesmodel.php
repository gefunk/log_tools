<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ChargeRulesModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function addrule($name, $application_type, $ref_application_rule,  $application_rule_values, $currency, $value, $contract)
	{
		$data = array(
			'name' => $name, 
			'application_type' => $application_type,
			'contract' => $contract,
			'currency' => $currency,
			'value' => $value);
		$this->db->insert('charge_rules', $data);
		$charge_rule_id = $this->db->insert_id();
		// insert the charge application rule cases
		// when the rule should be applied
		$data = array();
		foreach($application_rule_values as $rule_value){
			$rule_data = array(
				'charge_rule' => $charge_rule_id,
				'charge_application_rule' => $ref_application_rule,
				'value' => $rule_value
			);
			array_push($data, $rule_data);
		}
		// insert all the application of the rules
		$this->db->insert_batch('charge_rule_application', $data);
		
	}

	/**
	* get all charge rules for this contract
	* @param the id of the contract
	* @return the columns of charge_rules
	*/
	function get_charge_rules_for_contract($contract_id)
	{
		$this->db->select("c.name as name, c.value as value, t.type as application_type, m.description as currency");
		$this->db->from("charge_rules c");
		$this->db->join('ref_charge_application_type t', 'c.application_type = t.id');
		$this->db->join('ref_currency_codes m', 'c.currency = m.id');
		$this->db->where('c.contract', $contract_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	* get all charge application rules
	* @return charge application rules: id, description, name 
	*/
	function get_charge_application_rules()
	{
		$this->db->select("*");
		$this->db->from('ref_charge_application_rules');
		$this->db->order_by('name');
		$query = $this->db->get();
		return $query->result();
	}
	
	/**
	* get all charge application types
	* @return charge application type: id, description, type
	*/
	function get_charge_application_types()
	{

		$query = $this->db->get('ref_charge_application_type');
		return $query->result();
	}
	
	/**
	* save a charge rule
	* and all associated cases when to apply it
	*/
	function save_rule(
			$contract_id, 
			$rule_name, 
			$application_rule,
			$application_type, 
			$application_cases,
			$rule_code = NULL,
			$currency = NULL, 			
			$value = NULL)
	{
		$data = array(
			'contract' => $contract_id,
			'application_type' => $application_type,
			'name' => $rule_name
		);
		
		if(isset($rule_code))
			$data['code'] = $rule_code;
		
		if(isset($currency))
			$data['currency'] = $currency;
			
		if(isset($value))
			$data['value'] = $value;
		
		// insert the charge rule
		$this->db->insert('charge_rules', $data);
		// last insert id
		$charge_rule_id = $this->db->insert_id();
		
		if(isset($application_cases)){
			$data = array();
			foreach($application_cases as $case){
				array_push($data, 
					array(
					'charge_rule' => $charge_rule_id,
					'charge_application_rule' => $application_rule,
					'value' => $case
					)
				);
			}
			
			// insert all the charge rule cases
			$this->db->insert_batch('charge_rule_application', $data);
		}

	}
	
}