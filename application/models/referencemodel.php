<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ReferenceModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function get_country_codes($array = FALSE)
	{
		$this->db->select('id, name, code')->from('country_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_currency_codes($array = FALSE)
	{
		$this->db->select('id, country_name, description, code')->from('currency_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_container_types($array = FALSE)
	{
		$this->db->select('id, container_type, carrier, description')->from('container_types'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function search_cities($search_term, $page=NULL, $page_size=NULL, $array=FALSE)
	{
		$this->db->select("rc.id, rc.city as city_name, usc.name as state, cc.name as country_name, cc.code as country_code");
		$this->db->from('ref_cities rc');
		$this->db->join('country_codes cc', 'rc.country_code = cc.code', 'left');
		$this->db->join('ref_us_can_region_codes usc', 'rc.state_region = usc.iso_region', 'left');
		$this->db->like('rc.city', $search_term);
		if(isset($page) && isset($page_size)){
			if($page == 1){
				// get the initial page
				$this->db->limit($page_size);
			}else{
				// get the next page
				$this->db->limit($page_size*$page, $page_size*($page-1));
			}
		}
		
		$query = $this->db->get();
		$data['results'] = $query->result_array();
		
		$this->db->select("count(*) as total");
		$this->db->from('ref_cities rc');
		$this->db->like('rc.city', $search_term);
		$query = $this->db->get();
		$data['total'] = $query->row()->total;
		
		return $data;
		
		
	}
	
	
	
}
/** end model **/