<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Countrycodes extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_country_codes($array = FALSE)
	{
		$this->db->select('name, code')->from('country_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
}
/** end model **/