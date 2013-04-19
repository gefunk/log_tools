<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RouteModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/*
	* @param city_id - the id of the city
	* @param customer_id - the id of the customer that you are searching for
	*/
	function find_closest_port_to_city($city_id, $customer_id){
		$sql =  	"SELECT "
					."port.*,"
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
						."where cll.`leg_type` = 1 "
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

} // end route model
