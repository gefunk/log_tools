<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RuleModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
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