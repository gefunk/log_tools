<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PortModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	/**
	 * increment the number of times this port was used in a search or in using a contract
	 *
	 * @param $id the id of the port you want to update
	 * @return TRUE or FALSE based on if operation was successful
	 */
	function up_hit_count($id)
	{
		return $this->db->query("update ref_ports set hit_count = hit_count + 1 where id = $id");
	}

}