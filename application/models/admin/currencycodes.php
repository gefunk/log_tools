<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currencycodes extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_currency_codes()
	{
		$this->db->select('id, country_name, description, code')->from('currency_codes'); 
		$query = $this->db->get();
	    return $query->result();
	}
	

	
}
/** end model **/