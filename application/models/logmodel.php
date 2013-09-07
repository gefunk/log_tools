<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logmodel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    
	
	/**
	 * Log new contract line item
	 * @param $user the user who did the change
	 * @param $contract_id the contract which is affected by the change
	 * @param $line_item_id the line item which was added
	 * @param $rate_search_ids the rate search elements which were added to the rate search table 
	 */
	function new_contract_line_item($user, $contract_id, $line_item_id, $rate_search_ids){
		$data = array("user" => $user,
					"line_item" => $line_item_id,
					"rate_search_terms" => $rate_search_ids);
		$query = array("_id" => $contract_id);
		$update = array(
					'$addToSet' => array(
						"log" =>array(
							"type" => LOG_EVENT_ADD, 
							"user" => $user, 
							"line_item" => $line_item_id,
							"search_terms" => $rate_search_ids,
							"date" => new MongoDate()
						)
					)
				);
		$this->mongo->db->contracts->update($query, $update);
	}
	
	
	    
    /**
     * Log inserts or deletes to line item
     * @param $user the user id responsible for this event
     * @param $new the id of the object that was created
     * @param $old the id of the object that was destroyed
     * @param $event_type the type of event, look at table:log_event_types
     * @param $object_type the type of object that is referenced in this log - table:log_object_types
     */
    public function insert_log_contract($user, $new, $old, $event_type, $object_type)
    {
        $data = array(
            "user" => $user,
            "new" => $new,
            "old" => $old,
            "event_type" => $event_type,
            "object_type" => $object_type
        );
        
        $this->db->insert('log_contract', $data);
    }
    
	/**
	 * get the latest log entry 
	 * only return the last max id from the table
	 * @return the last log entry
	 */
	public function get_last_entry_for_object($object_id)
	{
		$this->db->where("new", $object_id);
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get("log_contract");
		$row = $query->result();
		return $row[0];
	}

}