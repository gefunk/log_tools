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
		$this->db->select('id, name, code')->from('ref_country_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_carriers()
	{
		$this->db->select('id, name')->from('ref_carriers'); 
		$query = $this->db->get();
	    return $query->result();
	}
	
	
	function get_currency_codes($array = FALSE)
	{
		$this->db->select('id, description, code, symbol')->from('ref_currency_codes'); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function get_container_types($carrier_id, $array = FALSE)
	{
		$this->db->select('id, container_type, carrier, description')->from('ref_container_types')->where('carrier', $carrier_id); 
		$query = $this->db->get();
		if($array)
			return $query->result_array();
		else
	    	return $query->result();
	}
	
	function search_cities($search_term, $page=NULL, $page_size=NULL, $array=FALSE)
	{
		$this->db->select("id, city_name, state, country_name, country_code");
		$this->db->from('ref_cities_search');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		
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
		$this->db->from('ref_cities_search');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$query = $this->db->get();
		$data['total'] = $query->row()->total;
		
		return $data;
		
		
	}
	
	function search_ports($search_term, $page=NULL, $page_size=NULL, $array=FALSE)
	{
		$this->db->select("rp.id, rp.name, rp.country_code, rp.port_code, rcc.name as country_name, rp.rail, rp.road, rp.airport, rp.ocean, rp.found, ruscrc.name as state, rp.state_code as state_code");
		$this->db->from('ref_ports rp');
		$this->db->join('ref_country_codes rcc', 'rcc.code = rp.country_code');
		$this->db->join('ref_us_can_region_codes ruscrc', 'ruscrc.iso_region = rp.state_code', 'left');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$this->db->order_by("found", "desc");
		
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
		$this->db->from('ref_ports');
		$this->db->where("MATCH(search_term) AGAINST ('".$this->clean_search_term($search_term)."')");
		$query = $this->db->get();
		$data['total'] = $query->row()->total;
		
		return $data;
		
		
	}
	
	
	
	/*
	* list of transport types:
	* container yard, rail, truck
	*/
	function get_transport_types()
	{
		$query = $this->db->get('ref_transport_types');
		return $query->result();
	}
	
	/*
	* list of leg types:
	* 1. origin, via, destination
	*/
	function get_leg_types()
	{
		$query = $this->db->get('ref_leg_types');
		return $query->result();
	}
	
	
	function get_cargo_types()
	{
		$query = $this->db->get("ref_cargo_types");
		return $query->result();
	}
	
	/*
	* utility function to replace white space with %
	* and strips all commas out
	*/
	function clean_search_term($search_term){
		// strip leading and ending whitespace and remove commas
		$string = str_replace(",", "", trim($search_term));
		//Clean multiple dashes or whitespaces
	    //$string = preg_replace("/[\s]+/", " ", $string);
	    //Convert whitespaces and underscore to %
	    //$string = preg_replace("/[\s]/", "%", $string);
	    return $string;
	}
	
	/*
	* calculate haversine distance formula
	* distance provided based on lat long
	* @param longitude of first location
	* @param latitude of first location
	* @param longitude of second location
	* @param latitude of second location
	* @param optional unit of measure "mi" or "km"
	* @return distance between points
	*/
	function distance($long_1,$lat_1,$long_2,$lat_2, $unit="mi")
	{
		$earth_radius = 3963.1676; // in miles
		if($unit != "mi"){
			$earth_radius =  6335.437; // in km
		}
		
		$sin_lat   = sin(deg2rad($lat_2  - $lat_1)  / 2.0);
		$sin2_lat  = $sin_lat * $sin_lat;

		$sin_long  = sin(deg2rad($long_2 - $long_2) / 2.0);
		$sin2_long = $sin_long * $sin_long;

		$cos_lat_1 = cos($lat_1);
		$cos_lat_2 = cos($lat_2);
		
		$sqrt      = sqrt($sin2_lat + ($cos_lat_1 * $cos_lat_2 * $sin2_long));
		$distance  = 2.0 * $earth_radius * asin($sqrt);
		return $distance;
	}
	
}
/** end model **/