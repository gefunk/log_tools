<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RuleModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }



	/**
	* SECTION - CHARGE RULES
	*/
	
	/**
	* add a charge rule
	*
	* @param $value the amount or value of this charge
	* @param $charge_code the charge code on the contract for this rule
	* @param $contract the contract this charge belongs to
	* @param $application how to apply this charge, by container, by TEU, etc...- reference to ref_application_types
	* @param $currency the currency code of this charge
	* @param $effective the effective date of this charge
	* @param $expires expiry date of this charge
	* @param $conditions array of conditions and values which will be evaluated to see whether or not this rule will apply
	*/
	function add_charge_rule($value, $charge_code, $contract, $application, $currency, $effective, $expires, $conditions)
	{
		$contract_rule_data = array(
			"value" => $value,
			"charge_code" => $charge_code,
			"contract" => $contract,
			"application" => $application,
			"currency" => $currency,
			"effective" => $effective,
			"expires" => $expires
		);
		
		$this->db->insert("charge_contract_rule", $contract_rule_data);
		
		// get the id of the charge rule
		$charge_rule_id = $this->db->insert_id(); 
		
		/**
		* insert all the conditions for this charge rule
		*/
		for($index = 0; $index < count($conditions); $index++){
			$condition = $conditions[$index];
			$condition_data = array(
				"condition" => $condition["condition"],
				"order" => $index,
				"rule" => $charge_rule_id
			);
			
			$this->db->insert("charge_contract_rule_conditions", $condition_data);
			$condition_id = $this->db->insert_id();
			
			/* insert all the values to meet this condition */
			foreach($condition["values"] as $conditional_value){
				$conditional_value_data = array("value" => $conditional_value, "condition" => $condition_id);
				$this->db->insert("charge_contract_rule_values", $conditional_value_data);
			}
		}
	}

	/**
	* retrieve a charge rule by id
	*
	* @param charge_rule_id the id of the rule you are retrieving
	*/
	function get_charge_rule_by_id($charge_rule_id)
	{

		$select = "ccrv.value as eval_value, ".
			"ccr.value as amount, ".
			"ccrc.condition as condition_id, ". 
			"ccr.effective as effective_date, ".
			"ccr.expires as expiry_date, ".
			"ccr.charge_code as charge_code, ".
			"ccr.id as rule_id, ".
			"ccr.application as application_id, ".
			"ccr.currency as currency_id, ".
			"rcc.name as condition_name, ".
			"rcc.verb as condition_verb, ".
			"rcc.ref_data_source as data_source_id, ".
			"rds.table as data_source_table, ".
			"curr.code as currency_code, ".
			"curr.symbol as currency_symbol, ".
			"rcat.type as charge_app_type";
		
		$this->db->select($select);
		$this->db->from("charge_contract_rule_values ccrv");
		$this->db->join("charge_contract_rule_conditions ccrc","ccrc.id  = ccrv.condition");
		$this->db->join("charge_contract_rule ccr","ccr.id = ccrc.rule");
		$this->db->join("ref_charge_condition rcc","rcc.id = ccrc.condition");
		$this->db->join("ref_currency_codes curr","curr.id = ccr.currency");
		$this->db->join("ref_data_sources rds","rds.id = rcc.ref_data_source");
		$this->db->join("ref_charge_application_type rcat","rcat.id = ccr.application");
		$this->db->where("ccr.id",$charge_rule_id);
		$this->db->where("ccr.deleted","0");
		
		$query = $this->db->get();
		
		return $this->charge_rule_data_parser($query);
	
	}
	
	/**
	* retrieve a charge rule by contract id
	*
	* @param the id of the contract you want charge rules for
	*/
	function get_charge_rule_by_contract_id($contract_id)
	{

		$select = "ccrv.value as eval_value, ".
			"ccr.value as amount, ".
			"ccrc.condition as condition_id, ". 
			"ccr.effective as effective_date, ".
			"ccr.expires as expiry_date, ".
			"ccr.charge_code as charge_code, ".
			"ccr.id as rule_id, ".
			"ccr.application as application_id, ".
			"ccr.currency as currency_id, ".
			"rcc.name as condition_name, ".
			"rcc.verb as condition_verb, ".
			"rcc.ref_data_source as data_source_id, ".
			"rds.table as data_source_table, ".
			"curr.code as currency_code, ".
			"curr.symbol as currency_symbol, ".
			"rcat.type as charge_app_type";
		
		$this->db->select($select);
		$this->db->from("charge_contract_rule_values ccrv");
		$this->db->join("charge_contract_rule_conditions ccrc","ccrc.id  = ccrv.condition");
		$this->db->join("charge_contract_rule ccr","ccr.id = ccrc.rule");
		$this->db->join("ref_charge_condition rcc","rcc.id = ccrc.condition");
		$this->db->join("ref_currency_codes curr","curr.id = ccr.currency");
		$this->db->join("ref_data_sources rds","rds.id = rcc.ref_data_source");
		$this->db->join("ref_charge_application_type rcat","rcat.id = ccr.application");
		$this->db->where("ccr.contract",$contract_id);
		$this->db->where("ccr.deleted","0");
		
		$query = $this->db->get();
		
		return $this->charge_rule_data_parser($query);
	
	}


	/**
	* delete a charge rule
	* sets the deleted flag to 1
	* @param charge_rule_id id of the charge rule you are trying to delete
	*/
	public function delete_charge_rule($charge_rule_id)
	{
		$this->db->update('charge_contract_rule', array("deleted", 1), "id = $charge_rule_id");
	}

	/**
	* Helper function arranges the charge rule into 
	* hierarchical data
	* RULE -> CONDITION -> EVAL_VALUE
	*				    -> EVAL_VALUE
	*					-> EVAL_VALUE
	*	   -> CONDITION -> EVAL_VALUE
	*					-> EVAL_VALUE
	*	   -> CONDITION	-> EVAL_VALUE
	*/
	function charge_rule_data_parser($query)
	{
		$rules = array();
		
		// arrange the data by eval values
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				/*
				* check if the rule has been created
				* if it hasn't create the data for the rule
				*/
				if(!isset($rules[$row->rule_id])){
					$rules[$row->rule_id] = array(
						"charge_app_type" => $row->charge_app_type,
						"charge_app_id" => $row->application_id,
						"data_source_table" => $row->data_source_table,
						"data_source_id" => $row->data_source_id,
						"currency_code" => $row->currency_code,
						"currency_symbol" => $row->currency_symbol,
						"effective_date" => $row->effective_date,
						"expiry_date" => $row->expiry_date,
						"amount" => $row->amount,
						"conditions" => array()
					);
				}
				
				// if this condition hasn't been added already
				$conditions = $rules[$row->rule_id]["conditions"];
				
				if(!isset($conditions[$row->condition_id])){
					$conditions[$row->condition_id] = array(
						"name" => $row->condition_name,
						"verb" => $row->condition_verb,
						"eval_values" => array()
					);
				}
				
				
				// add eval value to condition
				array_push($conditions[$row->condition_id]["eval_values"], $row->eval_value);
				
				// set the value of conditions back
				$rules[$row->rule_id]["conditions"] = $conditions;
				
			}
		}
		
		return $rules;
	}

	/**
	* SECTION - LANE RULES
	*/
	
	/**
	* CREATE TABLE `charge_lane_rule` (
	*  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	*  `charge_code` int(11) unsigned NOT NULL,
	*  `value` float NOT NULL,
	*  `notes` varchar(255) DEFAULT NULL,
	*  `attachment` int(11) unsigned DEFAULT NULL,
	*  `expires` date NOT NULL,
	*  `effective` date NOT NULL,
	*  `currency` int(11) NOT NULL,
	*  `lane` int(11) NOT NULL,
	*  PRIMARY KEY (`id`)
	* ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	*
	* Add a charge rule for a lane
	*/
	function add_lane_rule($lane_id,$charge_code, $effective,$expires, $currency,$value,$notes)
	{
		$data = array(
			"charge_code" => $charge_code,
			"value" => $value,
			"notes" => $notes,
			"expires" => $expires,
			"effective" => $effective,
			"currency" => $currency,
			"lane" => $lane_id
		);
		
		$this->db->insert("charge_lane_rule", $data);
		$rule_id = $this->db->insert_id();
		return $this->get_lane_rule_by_id($rule_id);
	}
	
	function get_lane_rule_for_lane($lane_id)
	{
		$this->db->select("clr.id, clr.value as amount, date_format(clr.expires, '%M %D, %Y') as expiration_date, date_format(clr.effective, '%M %D, %Y') as effective_date, cur.symbol as currency_symbol, cur.code as currency_code, cha.code as charge_code, cha.description as charge_description", FALSE);
		$this->db->from("charge_lane_rule clr");
		$this->db->join("ref_charge_codes cha","cha.id = clr.charge_code");
		$this->db->join("ref_currency_codes cur", "cur.id = clr.currency");
		$this->db->where("clr.lane", $lane_id);
		$this->db->where("clr.deleted", "0");		
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return NULL;
		}
	}
	
	function get_lane_rule_by_id($rule_id)
	{
		$this->db->select("clr.id, clr.value as amount, date_format(clr.expires, '%M %D, %Y') as expiration_date, date_format(clr.effective, '%M %D, %Y') as effective_date, cur.symbol as currency_symbol, cur.code as currency_code, cha.code as charge_code, cha.description as charge_description", FALSE);
		$this->db->from("charge_lane_rule clr");
		$this->db->join("ref_charge_codes cha","cha.id = clr.charge_code");
		$this->db->join("ref_currency_codes cur", "cur.id = clr.currency");
		$this->db->where("clr.id", $rule_id);
		$this->db->where("clr.deleted", "0");	
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();
		}else{
			return NULL;
		}

	}
	/**
	* delete a rule for a lane
	*/
	function delete_lane_rule($lane_id)
	{
		$data = array('deleted' => 1);
		$this->db->where('id', $lane_id);
		$this->db->update('charge_lane_rule', $data);
	}

} // end Rule Model