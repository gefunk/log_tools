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
		$this->db->select("c.id as id, c.name as name, c.value as value, t.type as application_type, m.description as currency, rca.name as rule_type, rca.description as rule_description, rds.table as data_source");
		$this->db->from("charge_rules c");
		$this->db->join('ref_charge_application_type t', 'c.application_type = t.id');
		$this->db->join('ref_currency_codes m', 'c.currency = m.id');
		$this->db->join('charge_rule_application cra',  'cra.charge_rule = c.id');
		$this->db->join('ref_charge_application_rules rca', 'cra.charge_application_rule = rca.id');
		$this->db->join('ref_data_sources rds', 'rca.ref_data_source = rds.id');
		$this->db->where('c.contract', $contract_id);
		$this->db->group_by('id');
		
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_charge_options_for_rule($charge_rule, $data_source_table)
	{
		$this->db->select("cc.name");
		$this->db->from("charge_rule_application cra");
		$this->db->join("charge_rules cr", "cra.charge_rule = cr.id");
		$this->db->join($data_source_table." cc", "cra.value = cc.id");
		$this->db->where("cr.id", $charge_rule);
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
	
	/**
	* create a new lane in the database for a contract
	* also create a base container charge for the lane
	*/
	function save_new_lane($contract_id, $from_port, $to_port, $value, $container_type, $cargo_type, $code)
	{
		$data = array('port_of_load' => $from_port,
			'port_of_discharge' => $to_port,
			'contract' => $contract_id);
		// save the lane
		$this->db->insert('contract_lanes', $data);	
		// get the id of the lane
		$lane_id = $this->db->insert_id();

		// insert new charge rule, application type is special for container = 15
		$data = array('contract' => $contract_id, 
			'application_type' => 15,
			'currency' => 5,
			'value' => $value,
			'name' => "Base Container Charge",
			'code' => $code);
		
		$this->db->insert('charge_rules', $data);	
		
		// get charge rule to insert into charge_base_container table
		$charge_rule_id = $this->db->insert_id();
		
		// insert special charge rule for container
		$data = array("container" => $container_type,
			'cargo' => $cargo_type,
			'charge_rule_id' => $charge_rule_id);		
		$this->db->insert('charge_base_container', $data);
		
		// attach the charge to the lane, by adding to charges_for_lane table
		$this->save_charge_to_lane($charge_rule_id, $lane_id);
		
	}
	
	/*
	* apply a charge to a lane
	* @param charge_rule_id: the charge to associate with the lane
	* @param lane_id: the lane to associate the charge to
	*/
	function save_charge_to_lane($charge_rule_id, $lane_id)
	{
		$data = array('charge_id' => $charge_rule_id, 'lane_id' => $lane_id);
		$this->db->insert('charges_for_lane', $data);
	}
	
	function get_lanes_for_contract($contract_id)
	{
		$this->db->select("cl.port_of_load as port_load, 
							cl.port_of_discharge as port_discharge,
							cl.id as lane_id,
							cr.name as charge_name,
							cr.code as charge_code,
							cr.value as charge_amount,
							rct.container_type as container_type,
							rct.id as container_type_id,
							rcargotypes.name as cargo_type,
							rcargotypes.id as cargo_type_id");
		$this->db->from("contract_lanes cl");
		$this->db->join("charges_for_lane cfl", "cfl.lane_id = cl.id");
		$this->db->join("charge_rules cr", "cfl.charge_id = cr.id");
		$this->db->join("charge_base_container c", "c.charge_rule_id = cr.id");
		$this->db->join("ref_container_types rct", "c.container = rct.id");
		$this->db->join("ref_cargo_types rcargotypes", "rcargotypes.id = c.cargo");
		$this->db->where('cl.contract', $contract_id);
		
		$query = $this->db->get();
		$dbresults = $query->result_array();
		
		foreach($dbresults as &$result){
			// get port load data
			$this->db->select("rp.id as port_id,
							rp.name as port_name, 
							rcc.name as country_name,
							rp.rail,
							rp.road,
							rp.airport,
							rp.ocean, 
							rp.found");
			$this->db->from('ref_ports rp');
			$this->db->join('ref_country_codes rcc', 'rp.country_code = rcc.code');
			$this->db->where('rp.id', $result['port_load']);
			$query = $this->db->get();
			$result['port_load'] = $query->result_array();

			// get port discharge data
			$this->db->select("rp.id as port_id,
							rp.name as port_name, 
							rcc.name as country_name,
							rp.rail,
							rp.road,
							rp.airport,
							rp.ocean, 
							rp.found");
			$this->db->from('ref_ports rp');
			$this->db->join('ref_country_codes rcc', 'rp.country_code = rcc.code');
			$this->db->where('rp.id', $result['port_discharge']);
			$query = $this->db->get();	
			$result['port_discharge'] = $query->result_array();
		}
		
		return $dbresults;

	}
	
	
	
}