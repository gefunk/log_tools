<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class QueryModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('map');
    }


	/*
	* returns the best lanes for a search
	* @param origin_city_id - id of the origin city
	* @param destination_city_id - id of where to ship to
	* @param customer_id - the id of the customer being searched for
	*/
	function search_lanes($origin_city_id, $destination_city_id, $customer_id)
	{
		// find the closest ports to origin city
		$origin_closest = $this->find_closest_ports_to_city($origin_city_id, $customer_id);
		// closest to destination city
		$destination_closest = $this->find_closest_ports_to_city($destination_city_id, $customer_id, true);
		
		$origin_closest_ports = "";
		$origin_data = array();
		foreach($origin_closest as $port){
			$origin_closest_ports .= $port->id.",";
			$origin_data[$port->id] = $port->distance;
		}
		$origin_closest_ports = rtrim($origin_closest_ports, ',');
		
		$dest_closest_ports = "";
		$dest_data = array();
		foreach($destination_closest as $port){
			$dest_closest_ports .= $port->id.",";
			$dest_data[$port->id] = $port->distance;
		}
		$dest_closest_ports = rtrim($dest_closest_ports, ',');
		
		$lanes = $this->get_lanes_match_origin_destination($origin_closest_ports, $dest_closest_ports, $customer_id);
		
		$lane_ids = array();
		
		foreach($lanes as $lane){
			array_push($lane_ids, $lane->id);
		}
		
		$data["origin_ports"] = $origin_data;
		$data["dest_ports"] = $dest_data;
		$data["lanes"] = $lane_ids;
		return $data;
		
		
	}
	
	/*
	* get the lanes which match the origin destination
	*/
	function get_lanes_match_origin_destination($origin_closest_ports, $dest_closest_ports, $customer_id)
	{
		// query for the best contract lanes
		$sql = "select distinct(origin.contract_lane) as id ".
		"from ".
			"contract_lane_legs origin, ".
			"contract_lane_legs destination ".
		"where origin.contract_lane = destination.contract_lane ".
		"and origin.leg_type = 1 ".
		"and destination.leg_type = 2 ".
		"and origin.location in ($origin_closest_ports) ".
		"and destination.location in ($dest_closest_ports) ".
		"and origin.contract_lane in( ".
			"select distinct(cl.id) from contract_lanes cl ".
			"JOIN contracts c on cl.`contract` = c.id ".
			"JOIN customers cust on cust.`id` = c.customer ".
			"and cust.id = $customer_id ".
		")";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	
	/*
	* closest ports to the city you are searching for
	* distance should never be null, because we are making a call out 
	* to the maps api to geocode everytime we add a new lane
	* @param city_id - the id of the city
	* @param customer_id - the id of the customer that you are searching for
	*/
	function find_closest_ports_to_city($city_id, $customer_id, $search_close_to_dest=false){
		$leg_type = 1;
		if($search_close_to_dest){
			$leg_type = 2;
		}
		
		$sql =  	"SELECT "
					."port.id,"
					."3956 * 2 * ASIN(SQRT( POWER(SIN((city.latitude - port.latitude)* pi()/180 / 2), 2) + COS(city.latitude * pi()/180) * 	COS(port.latitude * pi()/180) * POWER(SIN((city.longitude - port.longitude) * pi()/180 / 2), 2) )) as distance "
					."from "
						."ref_cities city,"
						."ref_ports port "
					."where "
						."city.id = $city_id "
					."and port.found = 1 "
					."and port.id in( "
						."select distinct(location) "
						."from contract_lane_legs cll "
						."where cll.`leg_type` = $leg_type "
						."and contract_lane in("
							."select id "
							."from contract_lanes cl "
							."where contract = (select con.id from contracts con where con.customer = $customer_id)"
						.")"
					.")"
				  	."order by distance";
				
		$query = $this->db->query($sql);
		
		return $query->result();
		
		
	}

} // end Query Model