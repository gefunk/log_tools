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
		$this->db->select('id, description, code')->from('ref_currency_codes'); 
		$query = $this->db->get();
	    return $query->result();
	}
	

	
}
/** end model **/