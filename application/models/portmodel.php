<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PortModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('map');
    }
	
	/**
	 * increment the number of times this port was used in a search or in using a contract
	 *
	 * @param $id the id of the port you want to update
	 * @return TRUE or FALSE based on if operation was successful
	 */
	function up_hit_count($id)
	{
		$this->found_port($id);
		return $this->db->query("update ref_ports set hit_count = hit_count + 1 where id = $id");
	}
	
		
	/*
	* helper to update port, when used in contract addition
	*/
	function found_port($port_id)
	{
		
		$this->db->select("rp.found as found, ".
						"rp.name as name, ".
						"rp.country_code as country_code, ".
						"rp.search_term as search_term, ".
						"rp.port_code as port_code, ".
						"rp.state_code, ".
						"rcc.name as country_name, ".
						"rp.found, ".
						"rp.map_geocode");
		$this->db->from('ref_ports rp');
		$this->db->join('ref_country_codes rcc', 'rcc.code =  rp.country_code');
		$this->db->where('rp.id', $port_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			$row = $query->row();
			
			if($row->map_geocode != 1 || $row->found != 1){
				// get location 
				$location = $this->map->get_location($row->name.", ".$row->state_code." ".$row->country_code);
				$search_term = $row->name." ".$row->country_code.$row->port_code." ".$row->country_name." ".$row->country_code." ".$location['admin_district']." ".$location['admin_district_sub'];
				$data = array(
							"found" => 1, 
							"map_geocode" => 1, 
							"search_term" => $search_term, 
							'latitude' => $location['latitude'], 
							'longitude' => $location['longitude']
						);
				$this->db->where('id', $port_id);
				$this->db->update('ref_ports', $data);
			}
		}
	}
	

}