<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class CargoModel extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }

	/**
	 * add cargo to contract
	 * @param $contract_id - the contract to add the cargo type to
	 * @param $cargo - text description of cargo type
	 */
	function add_cargo_type_to_contract($contract_id, $cargo){
		// expire cache
		$key = 'get_cargo_types_for_contracts-'.$contract_id;
		$this->cache->delete($key);
		$query = array("_id"=>new MongoId($contract_id));
		$update = array('$push' => array("cargo_types" => $cargo));
		return $this->mongo->db->contracts->update($query, $update);
	}	
	
	/**
	 * get cargo types for a contract
	 */
	function get_cargo_types_for_contracts($contract_id){
		$key = 'get_cargo_types_for_contracts-'.$contract_id;
		if(! $result = $this->cache->get($key)){
			$query = array("_id"=>new MongoId($contract_id));
			$projection = array("cargo_types" => 1);
			$doc = $this->mongo->db->contracts->findOne($query, $projection);
			$result = $doc['cargo_types'];		
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
		return $result;
	}

	/**
	 * remove cargo type from contract
	 * @param $contract_id - the contract to add the cargo type to
	 * @param $cargo - text description of cargo type
	 */
	function remove_cargo_type_from_contract($contract_id, $cargo){
		$key = 'get_cargo_types_for_contracts-'.$contract_id;
		$this->cache->delete($key);
		$query = array("_id"=>new MongoId($contract_id));
		$update = array('$pull' => array("cargo_types" => $cargo));
		return $this->mongo->db->contracts->update($query, $update);
	}

}

