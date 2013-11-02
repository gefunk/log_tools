<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AttachmentModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	* once a contract is uploaded 
	* we want to save it in the db to pull it again
	*/
	function insert_uploaded_document($contract_id, $remote_path, $file_name)
	{
		$query = array("_id" => intval($contract_id));
		$document_id = new MongoId();
		$update = array(
					'$addToSet' => array(
						"documents" =>array(
							"_id" => $document_id, 
							"path" => $remote_path, 
							"file_name" => $file_name,
							"date" => new MongoDate()
						)
					)
				);
		$this->mongo->db->contracts->update($query, $update);
		return $document_id;
	}
	
	/**
	* save the image of each page of the contract
	* @param $page_number the page number that corresponds to the page number on the contract
	* @param $upload_id corresponds to the version of the contract uploaded
	*/
	function insert_uploaded_page($page, $upload_id)
	{
		$query = array("document._id" => $upload_id);
		$update = array('$addToSet' => array("documents" => array("pages" => array("name" => $page, "progress" => 0))));
		$this->mongo->db->contracts->update($query, $update);
	}
	
	function update_document_process_progress($upload_id, $page, $progress)
	{
		$query = array("documents._id" => $upload_id, "documents.pages.name" => $page);
		$update = array('$addToSet' => array("documents" => array("pages" => array("progress" => $progress))));
		$this->mongo->db->contracts->update($query, $update);
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