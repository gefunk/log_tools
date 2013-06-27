<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (ENTITIES_DIR . "LineItemCharge.php");


/**
 * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 * `line_item_id` int(11) unsigned NOT NULL COMMENT 'corresponding line item for this surcharge',
 * `application` int(11) unsigned NOT NULL COMMENT 'how this surcharge should be applied',
 * `charge_code` int(11) unsigned NOT NULL COMMENT 'ref_charge_code is where the charge codes are stored',
 * `effective` date NOT NULL COMMENT 'when this charge code is effective',
 * `expires` date NOT NULL COMMENT 'when this charge code expires',
 * `value` int(11) NOT NULL COMMENT 'the cost of this charge',
 * `currency` int(11) unsigned NOT NULL COMMENT 'what type of currency this charge should use',
 * `deleted` tinyint(1) unsigned NOT NULL
 * 
 * class LineItemCharge {
 *
 *   public $id;
 *   public $application;
 *  public $charge_code;
 *   public $effective;
 *   public $expires;
 *   public $currency;
 *   public $amount;
 *   public $deleted;
 *
 * Represents a charge for a line item
 *  
 */
class LineChargeModel extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	/**
	 * add surcharge to table
	 */
	function add_charge($line_charge){
		$data = array(
			'line_item_id' => $line_charge->line_item_id,
			'application' => $line_charge->application,
			'charge_code' => $line_charge->charge_code,
			'effective' => $line_charge->effective,
			'expires' => $line_charge->expires,
			'value' => $line_charge->amount,
			'currency' => $line_charge->currency
		); 
		
		$this->db->insert('line_item_surcharges', $data);
		return $this->db->insert_id();
	}
	
	function delete_charge($line_charge_id){
		$data = array("deleted" => 1);
        $this -> db -> where('id', $line_charge_id);
        $this -> db -> update('line_item_surcharges', $data);
        return $line_charge_id;
	}
	
	function get_charge($line_charge_id)
	{
		$query = $this->db->get_where('line_item_surcharges', array("id" => $line_charge_id));
		$charge = NULL;
		if($query->num_rows() > 0){
            $row = $query->row();
			$charge = LineItemCharge::initLineItemFromDb($row->id, $row->line_item_id, $row->application, $row->charge_code, $row->effective, $row->expires, $row->currency, $row->value, $row->deleted);
		}
		return $charge;	
	}
	
	function get_charges_for_line_item($line_item_id)
	{
		$query = $this->db->get_where('line_item_surcharges', array("line_item_id" => $line_item_id));
		$charges = NULL;
		if($query->num_rows() > 0){
			$charges = array();
			foreach($query->result() as $row){
				$charge = LineItemCharge::initLineItemFromDb($row->id, $row->line_item_id, $row->application, $row->charge_code, $row->effective, $row->expires, $row->currency, $row->value, $row->deleted);
				array_push($charges,$charge);
			}	
		}
		return $charges;
	}

}
