<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LaneModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


	function getlanes($contract_id)
	{
		$this->db->select('cl.id, cl.effective_date as effective_date, rct.container_type as container, rct.description as container_description, cl.value, rcc.code as currency, rcc.symbol as currency_symbol, rcart.name as cargo, rcart.description as cargo_description');
		$this->db->from('contract_lanes cl');
		$this->db->join('ref_container_types rct', 'cl.container = rct.id');
		$this->db->join('ref_cargo_types rcart','cl.cargo = rcart.id');
		$this->db->join('ref_currency_codes rcc','cl.currency = rcc.id');
		$this->db->order_by("cl.id", "desc");
		$query = $this->db->get();
		$dbresults = $query->result_array();
		
		foreach($dbresults as &$lane){
			$this->db->select('rp.name as location, rp.country_code as country_code, rtt.name as transport_type, rlt.name as leg_type');
			$this->db->from('contract_lane_legs cll');
			$this->db->join('ref_ports rp','rp.id = cll.location');
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
	function addlane($contract_id, $container_type, $value, $cargo_type, $effective_date, $legs, $currency_code)
	{
		$lane = array(
			'contract' => $contract_id,
			'value' => $value,
			'cargo' => $cargo_type,
			'container' => $container_type,
			'effective_date' => $effective_date,
			'currency' => $currency_code
		);
		
		// insert lane
		$this->db->insert('contract_lanes', $lane);
		$lane_id = $this->db->insert_id(); 
		
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
	*/
	function deletelane($lane_id)
	{
		$this->db->where('contract_lane', $lane_id);
		$this->db->delete('contract_lane_legs');
		
		$this->db->where('id', $lane_id);
		$this->db->delete('contract_lanes');
	}

	
	/*
	* helper to update port, when used in contract addition
	*/
	function found_port($port_id)
	{
		$this->db->select("found")->from('ref_ports')->where('id', $port_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$row = $query->row();
			if($row->found == '1'){
				$data = array("found" => 1);
				$this->db->where('id', $port_id);
				$this->db->update('ref_ports', $data);
			}
		}
	}


} // end lane model