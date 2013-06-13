<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logmodel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
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
    

}