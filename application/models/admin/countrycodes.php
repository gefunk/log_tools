<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Countrycodes extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_country_codes()
	{
		$this->db->select('name, code')->from('country_codes'); 
		$query = $this->db->get();
	    return $query->result();
	}
	
}
/** end model **/