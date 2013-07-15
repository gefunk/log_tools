<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AttachmentModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	* get an attachment id to use
	* @param element_type the type of element this attachment refers to
	* @return the id of the attachment inserted
	*/
	function get_next_attachment_id($element_type)
	{
		$data = array("element" => $element_type);
		$this->db->insert("attachment", $data);
		return $this->db->insert_id();
	}
	
	/*
	CREATE TABLE `attachment_storage` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `key` varchar(255) NOT NULL DEFAULT '',
	  `content_type` varchar(255) NOT NULL DEFAULT '',
	  `attachment_id` int(11) unsigned NOT NULL,
	  `local` tinyint(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	*/
	function add_attachment_for_id($attachment_id, $key, $content_type, $local)
	{
		$data = array("key" => $key, "content_type" => $content_type, "local" => $local, "attachment_id" => $attachment_id);
		$this->db->insert("attachment_storage", $data);
	}
	
	
	/**
	* once a contract is uploaded 
	* we want to save it in the db to pull it again
	*/
	function insert_uploaded_contract($contract_id, $filename)
	{
		$data = array(
			"contract" => $contract_id,
			"filename" => $filename
		);
		
		$this->db->insert("contract_uploads", $data);
		return $this->db->insert_id();
	}
	
	/**
	* save the image of each page of the contract
	* @param $page_number the page number that corresponds to the page number on the contract
	* @param $upload_id corresponds to the version of the contract uploaded
	*/
	function insert_uploaded_contract_page($page_number, $upload_id)
	{
		$data = array("number_of_pages" => $page_number);
		$this->db->where("id", $upload_id);
		$this->db->update("contract_uploads", $data);
	}
	
	function update_contract_process_progress($progress, $id)
	{
		$data = array(
		               'status' => $progress
		            );

		$this->db->where('id', $id);
		$this->db->update('contract_uploads', $data);
	}
	
	
	
	public function get_latest_upload_for_contract($contract_id)
	{
		$sql = "SELECT status, filename, number_of_pages FROM contract_uploads".
		" where contract = $contract_id".
		" order by upload_time desc LIMIT 1";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
}
/** end model **/