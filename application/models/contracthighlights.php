<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContractHighlights extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function add($contract_id, $position, $height, $page)
	{
		$data = array(
			"contract" => $contract_id,
			"position" => $position,
			"height" => $height, 
			"page" => $page
		);
		$this->db->insert("contract_highlights", $data);
		return $this->db->insert_id();
	}
	
	public function get_for_contract_page($contract_id, $page)
	{
		$this->db->select("id, position, height");
		$this->db->where("contract", $contract_id);
		$this->db->where("page", $page);
		$this->db->from("contract_highlights");
		$query = $this->db->get();
		return $query->result();
	}
	
	public function delete($highlight_id)
	{
		$this->db->delete('contract_highlights', array('id' => $highlight_id)); 
	}

}