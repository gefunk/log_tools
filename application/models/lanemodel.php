<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LaneModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->library('map');
    }



	/**
	* get all lanes on a contract
	*/
	function getlanes($contract_id)
	{
		$this->db->select(
			"cl.id, ".
			"date_format(cl.effective_date, '%M %D, %Y') as effective_date, ".
			"date_format(cl.expiration, '%M %D, %Y') as expiration_date, ".
			"rct.container_type as container, ".
			"rct.description as container_description, ".
			"cl.value, rcc.code as currency, ".
			"rcc.symbol as currency_symbol, ".
			"rcart.name as cargo, ".
			"rcs.name as service_name, ".
			"rcs.code as service_code, ".
			"rcart.description as cargo_description", 
			FALSE);
		$this->db->from("contract_lanes cl");
		$this->db->join("ref_container_types rct", "cl.container = rct.id");
		$this->db->join("ref_cargo_types rcart","cl.cargo = rcart.id");
		$this->db->join('ref_currency_codes rcc','cl.currency = rcc.id');
		$this->db->join("ref_carrier_services rcs", "rcs.id = cl.carrier_service", "left");
		$this->db->where('cl.contract',$contract_id);
		$this->db->where('cl.deleted',"0");
		$this->db->order_by("cl.id", "desc");
		$query = $this->db->get();
		$dbresults = $query->result_array();
		
		foreach($dbresults as &$lane){
			$this->db->select('rp.name as location, rusrc.name as state, rp.country_code as country_code, rtt.name as transport_type, rlt.name as leg_type');
			$this->db->from('contract_lane_legs cll');
			$this->db->join('ref_ports rp','rp.id = cll.location');
			$this->db->join('ref_us_can_region_codes rusrc','rusrc.iso_region = rp.state_code', "left");
			$this->db->join('ref_transport_types rtt','rtt.id = cll.service_type');
			$this->db->join('ref_leg_types rlt', 'rlt.id = cll.leg_type');
			$this->db->where('cll.contract_lane', $lane['id']);
			$this->db->order_by("cll.order");
			$query = $this->db->get();
			$lane['legs'] = $query->result_array();
		}
		
		return $dbresults;
		
	}
	
	function get_lanes_by_lane_id($lane_ids)
	{
		
		$lane_ids_sql = "";
		foreach($lane_ids as $lane){
			$lane_ids_sql .= $lane.",";
		}
		$lane_ids_sql = rtrim($lane_ids_sql, ',');
		
		$this->db->select("cl.id, DATE_FORMAT(cl.effective_date,'%M %D, %Y') as effective_date, rct.container_type as container, rct.description as container_description, cl.value, rcc.code as currency, rcc.symbol as currency_symbol, rcart.name as cargo, rcart.description as cargo_description, carrier.name as carrier_name, carrier.image as carrier_image", FALSE);
		$this->db->from('contract_lanes cl');
		$this->db->join('ref_container_types rct', 'cl.container = rct.id');
		$this->db->join('ref_cargo_types rcart','cl.cargo = rcart.id');
		$this->db->join('ref_currency_codes rcc','cl.currency = rcc.id');
		$this->db->join('contracts con','con.id = cl.contract');
		$this->db->join('ref_carriers carrier','carrier.id = con.carrier');
		$this->db->where("cl.id IN ($lane_ids_sql)");
		$this->db->order_by("cl.id", "desc");
		$query = $this->db->get();
		$dbresults = $query->result_array();
		
		foreach($dbresults as &$lane){
			$this->db->select('rp.id as location_id, rp.name as location, rusrc.name as state, rp.country_code as country_code, rtt.name as transport_type, rlt.name as leg_type');
			$this->db->from('contract_lane_legs cll');
			$this->db->join('ref_ports rp','rp.id = cll.location');
			$this->db->join('ref_us_can_region_codes rusrc','rusrc.iso_region = rp.state_code', "left");
			$this->db->join('ref_transport_types rtt','rtt.id = cll.service_type');
			$this->db->join('ref_leg_types rlt', 'rlt.id = cll.leg_type');
			$this->db->where('cll.contract_lane', $lane['id']);
			$this->db->order_by("cll.order");
			$query = $this->db->get();
			$lane['legs'] = $query->result_array();
		}
		
		return $dbresults;
		
	}
	
	/*
	$contract_id = $this->input->post('contract_id');
	$legs = $this->input->post("legs");
	$container_type = $this->input->post('container_type');
	$value = $this->input->post('value');
	$cargo_type = $this->input->post('cargo_type');
	$effective_date = $this->get_sql_date($this->input->post('effective_date'));
	*/
	function addlane($contract_id, $container_type, $value, $cargo_type, $effective_date, $expiration_date, $legs, $currency_code, $service, $tariffs)
	{
		$lane = array(
			'contract' => $contract_id,
			'value' => $value,
			'cargo' => $cargo_type,
			'container' => $container_type,
			'effective_date' => $effective_date,
			'expiration' => $expiration_date,
			'currency' => $currency_code,
			'carrier_service' => $service
		);
		
		// insert lane
		$this->db->insert('contract_lanes', $lane);
		$lane_id = $this->db->insert_id(); 
		
		// insert tariffs
		$tariff_data = array();
		foreach($tariffs as $tariff){
			array_push($tariff_data, array("contract_lane" => $lane_id, "carrier_tariff" => $tariff));
		}
		$this->db->insert_batch('contract_lane_tariffs', $tariff_data);
		
		// prepare data for legs for contract
		$legs_data = array();
		for($index = 0; $index < count($legs); $index++){
			$leg = $legs[$index];
			array_push($legs_data, array(
				'location' => $leg['location'],
				'service_type' => $leg['transport'],
				'leg_type' => $leg['leg_type'],
				'order' => $index,
				'contract_lane' => $lane_id
			));
			/* 
			* add found flag on port, so it shows up with
			* priority in search box
			*/
			$this->found_port($leg['location']);
		}
		
		// insert batch for legs
		$this->db->insert_batch('contract_lane_legs', $legs_data);
	}
	
	/*
	* delete lane
	* and associated contract lane legs
	* and associated contract lange charges
	*/
	function deletelane($lane_id)
	{
		$data = array("deleted" => "1");
		// contract lane legs
		$this->db->where('contract_lane', $lane_id);
		$this->db->update('contract_lane_legs', $data);
		// contract lane charges
		$this->db->where('id', $lane_id);
		$this->db->update('charge_lane_rule', $data);
		// contract lane
		$this->db->where('id', $lane_id);
		$this->db->update('contract_lanes', $data);
	}

	
	
	
	/**
	* 1	Charge only applies when shipping to destination country	Destination Country	is	2
	* 3	Charge only applies when shipping form this Origin Country	Origin Country	is	2
	* 5	Charge only applies when this is the Port of Loading	Port of Loading	is	3
	* 6	Charge only applies when this is the Port of Discharge	Port of Discharge	is	3
	* 7	Charge applies across all rates in this contract	Contract	is	NULL
	* 8	Charge applies to type of Container	Container type	is	4
	* 9	Charges applies when shipping to these destination countries	Destination Countries	are	2
	* 10	Charge applies when shipping from these origin countries	Origin Countries	are	2
	* 13	Charge applies when shpping to these ports	Port of Discharges	are	3
	* 14	Charge applies when shipping from these ports	Port of Loadings	are	3
	* 15	Base Container Charge	Base Container Charge	is	NULL
	* 16	Charge only applies to this Carrier Service	Carrier Service	is	NULL
	* 17	Tariff 	Tariff	is	5
	* 18	Service	Service	is	6
	* 19	Tariffs	Tariffs	are	5
	* 20	Services	Services	are	6
	*/
	
	function get_lanes_affected_by_charge_rule($conditions)
	{
		
		$sql = "select id from contract_lanes ";
		
		$count = 0;
		foreach($conditions as $condition){

			$value = "";
			
			// have to convert values array to comma separated list
			if(count($condition->values) > 1){
				foreach($condition->values as $v){
					$value .= $v.",";
				}
				// remove trailing comma
				$value = rtrim($value, ",");
			}else{
				$value = $condition->values[0];
			}
			// get the where clause for this condition
			$clause = $this->translate_conditions_to_where_clauses($condition->id, $value);
			
			if($count < 1){
				$sql .= "WHERE ".$clause;
			}else{
				$sql .= "AND ".$clause;
			}
			
			$count++;
		}
		
		
		// retrieve lane ids
		$query = $this->db->query($sql);
		$lane_ids = array();
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
				array_push($lane_ids, $row->id);
			}
			return $this->get_lanes_by_lane_id($lane_ids);
		}
		
		return NULL;
		
		
	}
	
	function translate_conditions_to_where_clauses($condition, $value)
	{
		error_log("Condition is".$condition);
		switch($condition){
			case 1: 
				$country_id = $value;
				return $this->get_where_for_countries("destination", $country_id);
				break;
			case 3: 
				$country_id = $value;
				return $this->get_where_for_countries("origin", $country_id);
				break;
			case 9:
				$country_ids = $value;
				return $this->get_where_for_countries("origin", $country_ids);
				break;
			case 10:
				$country_ids = $value;
				return $this->get_where_for_countries("origin", $country_ids);
				break;
			case 5:
				$port_ids = $value;
				return $this->get_where_for_ports("origin", $port_ids);
				break;
			case 6:
				$port_ids = $value;
				return $this->get_where_for_ports("destination", $port_ids);
				break;
			case 13:
				$port_ids = $value;
				return $this->get_where_for_ports("destination", $port_ids);
				break;
			case 14:
				$port_ids = $value;
				return $this->get_where_for_ports("origin", $port_ids);
				break;
		}
	}


	function get_where_for_countries($dest_or_origin, $country_ids)
	{
		$sql = "id IN (select distinct(contract_lane) from contract_lane_legs cll ".
			"where cll.leg_type = (select id from ref_leg_types where name = '$dest_or_origin') ".
			"and location in ( ".
				"select id from ref_ports p where country_code = ( ".
					"select code from ref_country_codes rcc where id IN ($country_ids) ".
				") ".
			"))";
			
		return $sql;
			
	}
	
	function get_where_for_ports($dest_or_origin, $port_ids)
	{
		$sql = "id in ( ".
			"select distinct(contract_lane) from contract_lane_legs cll ".
			"where cll.location in ($port_ids) ".
			"and cll.leg_type = (select id from ref_leg_types where name = '$dest_or_origin') ".
		")";
		return $sql;
	}
	
	/*
	* helper to update port, when used in contract addition
	*/
	function found_port($port_id)
	{
		$this->db->select("rp.found as found, rp.name as name, rp.country_code as country_code, rp.search_term as search_term, rp.port_code as port_code, rp.state_code, rcc.name as country_name");
		$this->db->from('ref_ports rp');
		$this->db->join('ref_country_codes rcc', 'rcc.code =  rp.country_code');
		$this->db->where('rp.id', $port_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			$row = $query->row();
			// get location 
			$location = $this->map->get_location($row->name.", ".$row->state_code." ".$row->country_code);
			$search_term = $row->name." ".$row->country_code.$row->port_code." ".$row->country_name." ".$row->country_code." ".$location['admin_district']." ".$location['admin_district_sub'];
			$data = array("found" => 1, "search_term" => $search_term, 'latitude' => $location['latitude'], 'longitude' => $location['longitude']);
			$this->db->where('id', $port_id);
			$this->db->update('ref_ports', $data);
		}
	}


} // end lane model