<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PortGroupModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }


	function add_port_group($name, $contract)
	{
		$data = array(
			"name" => $name,
			"contract" => $contract
		);
		$this->db->insert("contract_entry_port_group_name", $data);
		$group_id = $this->db->insert_id();
		// clear the cache so it is the latest
		$this->cache->delete("get_port_groups-".$contract);
		return $group_id;
	}

	/*
	* add a port group to this contract
	*/
	function add_port_to_group($port_id, $group_id)
	{
		$data = array(
			
			"port_id" => $port_id,
			"group_id" => $group_id
		);
		
		$this->db->insert("contract_entry_port_groups", $data);
		// clear the cache, so it is refreshed with the latest
		$this->cache->delete('get_ports_for_group-'.$group_id);
		
	}
	
	/**
	 * remove a port from a group
	 * @param $port_id the port to remove from the group
	 * @param $group_id the group to remove the port from
	 */
	function remove_port_from_group($port_id, $group_id)
	{
		$this->db->delete('contract_entry_port_groups', array('port_id' => $port_id, 'group_id' => $group_id)); 
		$this->cache->delete('get_ports_for_group-'.$group_id);
	}
	
	/**
	 * get all port groups in a contract
	 */
	function get_port_groups_for_contract($contract_id)
	{
		$key = "get_port_groups-".$contract_id;
		if(! $result = $this->cache->get($key)){
			$this->db->select("id,name");
			$this->db->from("contract_entry_port_group_name");
			$this->db->where("contract", $contract_id);
			$query = $this->db->get();
			
			$result = $query->result();
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		return $result;
	}
	
	/**
	 * get all ports in a group
	 */
	function get_ports_for_group($group_id)
	{
		$key = 'get_ports_for_group-'.$group_id;
		if(! $data = $this->cache->get($key)){
		
			$this->db->select("port_id");
			$this->db->from("contract_entry_port_groups");
			$this->db->where("group_id", $group_id);
			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				$port_in_clause = ""; 
				foreach ($query->result() as $row) {
					$port_in_clause .= $row->port_id.",";
				}
				
				$port_in_clause = rtrim($port_in_clause, ",");
				
				$this->db->select("rp.id, rp.name, rp.country_code, rp.port_code, rcc.name as country_name, rp.rail, rp.road, rp.airport, rp.ocean, rp.found, ruscrc.name as state, rp.state_code as state_code");
				$this->db->from('ref_ports rp');
				$this->db->join('ref_country_codes rcc', 'rcc.code = rp.country_code');
				$this->db->join('ref_us_can_region_codes ruscrc', 'ruscrc.iso_region = rp.state_code', 'left');
				$this->db->where("rp.id IN ($port_in_clause)");
				
				
				$query = $this->db->get();
				
				$data['results'] = $query->result_array();
				$this->cache->save($key, $data, WEEK_IN_SECONDS);
			}
			
		}
		return $data;
	}
	

}


