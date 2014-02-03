<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PortGroupModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }


	function add_port_group($name, $contract)
	{
			
		$group = array('name' =>$name);
		$query = array("_id"=> new MongoId($contract));
		$update = array('$addToSet' => array('port_groups' => $group));
		$this->mongo->db->contracts->update($query, $update);
		
		// clear the cache so it is the latest
		$this->cache->delete("get_port_groups-".$contract);
		return (string) $group["_id"];
	}

	/*
	* add a port group to this contract
	*/
	function add_port_to_group($contract_id, $port_id, $group_name)
	{
		// clear the cache, so it is refreshed with the latest
		$key = 'get_ports_for_group-'.$contract_id."-".$group_name;
		$this->cache->delete($key);
		
		$query = array("_id" => new MongoId($contract_id), "port_groups.name" => $group_name);
		$update = array('$addToSet' => array('port_groups.$.ports' => intval($port_id)));
		$this->mongo->db->contracts->update($query, $update);
		
		$this->db->select("rp.id, rp.name, rp.country_code, rp.port_code, rcc.name as country_name, rp.rail, rp.road, rp.airport, rp.ocean, rp.found, ruscrc.name as state, rp.state_code as state_code");
		$this->db->from('ref_ports rp');
		$this->db->join('ref_country_codes rcc', 'rcc.code = rp.country_code');
		$this->db->join('ref_us_can_region_codes ruscrc', 'ruscrc.iso_region = rp.state_code', 'left');
		$this->db->where("rp.id", $port_id);
		
		$query = $this->db->get();
		
		return $query->result();
		
	}
	
	/**
	 * remove a port from a group
	 * @param $port_id the port to remove from the group
	 * @param $group_id the group to remove the port from
	 */
	function remove_port_from_group($contract_id, $port_id, $group_name)
	{
		$query = array("_id" => new MongoId($contract_id), "port_groups.name" => $group_name);
		$update = array('$pull' => array('port_groups.$.ports' => intval($port_id)));
		$this->mongo->db->contracts->update($query, $update);
	}
	
	/**
	 * get all port groups in a contract
	 */
	function get_port_groups_for_contract($contract_id)
	{
		$key = "get_port_groups-by-contract-".$contract_id;
		if(! $result = $this->cache->get($key)){
			
			$query = array('_id' => new MongoId($contract_id));
			$projection = array( "_id"=> 0, "port_groups" => 1);
			$result = $this->convert_mongo_result_to_object($this->mongo->db->contracts->findOne($query, $projection));
			
			
			if(!empty($results))
				$this->cache->save($key, $results, WEEK_IN_SECONDS);
		}
		return $result;
	}
	

	/**
	 * get all ports in a group
	 */
	function get_ports_for_group($contract_id, $group_name)
	{
		
		$key = 'get_ports_for_group-'.$contract_id."-".$group_name;
		if(! $data = $this->cache->get($key)){
		
			$query = array('_id' => new MongoId($contract_id));
			$projection = array("port_groups"=> array('$elemMatch' => array("name" => $group_name)));
			$group_result = $this->convert_mongo_result_to_object($this->mongo->db->contracts->findOne($query, $projection));
			
			if($group_result && isset($group_result->port_groups[0]['ports'])){
				$port_in_clause = ""; 
				foreach ($group_result->port_groups[0]['ports'] as $port) {
					$port_in_clause .= $port.",";
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
	
	
	function typeahead_port_groups($contract_id, $query){
		$data = NULL;
		$key = "typeahead_port_groups-".$query;
		if(! $data = $this->cache->get($key)){
			$this->db->select("id, name")->from("contract_entry_port_group_name")->where('contract', $contract_id)->like('name', $query);					
			$query = $this->db->get();
			$data = $query->result_array();
			$this->cache->save($key, $data, WEEK_IN_SECONDS);
			
		}
		return $data;
	}

}


