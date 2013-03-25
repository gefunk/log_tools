<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CarrierModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_carriers()
	{
		$this->db->select('id, name')->from('ref_carriers'); 
		$query = $this->db->get();
	    return $query->result();
	}
	
}
/** end model **/