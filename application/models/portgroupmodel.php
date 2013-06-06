<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PortGroupModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/*
	* add a port group to this contract
	*/
	function add_port_group($name, $port_id, $contract)
	{
		$data = array(
			"name" => $name,
			"port_id" => $port_id,
			"contract" => $contract
		);
		
		$this->db->insert("contract_entry_port_groups", $data);
		
	}
	
	/**
	* get all port groups associated with this contract
	*/
	function get_port_groups($contract)
	{
		$this->db->select('name');
		$this->db->distinct();
		$this->db->from('contract_entry_port_groups');
		$this->db->where('contract',$contract);
		$query = $this->db->get();
		return $query->result();
	}

}


