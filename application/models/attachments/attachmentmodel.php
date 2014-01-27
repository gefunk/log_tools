<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AttachmentModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	* once a document is uploaded 
	* we want to save it in the db to pull it again
	* @param $remote_path - where it is stored on aws
	* @param $file_name - filename of the contract
	* @return $document - the inserted document
	*/
	function insert_uploaded_document($file_name)
	{
		$document = array("file_name" => $file_name,"date" => new MongoDate());
		// we want to make sure this gets written to MongoDB, get acknowledgement that it is written to the db
		$this->mongo->db->documents->insert($document, array("w" => 1));
		return $document;
	}
	
	/**
	 * set the remote path for the document
	 * @param $document_id - the id of the document
	 * @param $remote_path - the path where the document is set
	 */
	function set_remote_path_for_document($document_id, $remote_path){
		$query = array("_id" => new MongoId($document_id));
		$update = array('$set' => array('path' => $remote_path));
		$this->mongo->db->documents->update($query, $update, array("w" => 1));	
	}
	
	/**
	* save the image of each page of the contract
	* @param $page_number the page number that corresponds to the page number on the contract
	* @param $document_id corresponds to the document id
	*/
	function insert_uploaded_page($page, $document_id)
	{
		$query = array("_id" => new MongoId($document_id));
		$update = array('$push' => array("pages" => $page));
		$this->mongo->db->documents->update($query, $update, array("w" => 1));
	}
	
	
	/**
	 * @param $document_id the id of the document
	 */
	function update_document_progress($document_id, $status, $percent)
	{
		$query = array("_id" => new MongoId($document_id));
		$update = array('$set' => array("progress" => array("status" => $status, "percent"=> $percent)));
		$this->mongo->db->documents->update($query, $update);
	}
	
	function get_document_process_progress($document_id){
		$query = array("_id" => new MongoId($document_id));
		$projection =  array("_id" => false, "progress" => true);
		$progress = $this->mongo->db->documents->find_one($query, $projection);
		return $this->convert_mongo_result_to_object($progress);
	}
	
	/**
	 * Documents which have not been assigned to a customer
	 * This signifies that they are in the "Inbox"
	 * @return MongoCursor sorted by most recently uploaded date
	 */
	function get_inbox_docs(){
		$query = array("customer" => array('$exists' => false));
		$cursor = $this->mongo->db->documents->find($query);
		$cursor->sort(array('date' => -1));
		return $this->convert_mongo_result_to_object($cursor);
	}
	
	/**
	 * return the stored representation of the document
	 * @param $document_id - the id of the document
	 */
	function get_document($document_id){
		$query = array("_id" => new MongoId($document_id));
		return $this->convert_mongo_result_to_object($this->mongo->db->documents->findOne($query));
	}

	function get_total_pages($document_id){
		$query = array("_id" => new MongoId($document_id));
		$projection = array("_id" => false, "pages" => true);
		$result = $this->mongo->db->documents->findOne($query, $projection);
		return count($result['pages']);
	}

	/**
	 * Assign a document to a contract
	 * @param $document_id - the document id
	 * @param $contract_id - the contract id to assign this document to
	 * @param $customer_id - the customer id for this contract
	 */
	function assign_to_contract($document_id, $contract_id, $customer_id){
		$query = array("_id" => new MongoId($document_id));
		$update = array('$set' => array("contract" => $contract_id, "customer" => $customer_id));
		$this->mongo->db->documents->update($query, $update);
		
		$query = array("_id" => new MongoId($contract_id));
		$update = array('$push' => array("documents" => array("_id" => $document_id)));
		$this->mongo->db->contracts->update($query, $update);
		
	}
	
	/**
	 * get all documents for a contract
	 * @param $contract_id - contract id to get the documents for
	 * @return all contract documents sorted by last uploaded date, descending
	 */
	function get_for_contract($contract_id){
		$query = array("contract" => $contract_id);
		return $this->convert_mongo_result_to_object(
			$this->mongo->db->documents->find($query)->sort(array("date" => -1))
		);
	}
	
	/**
	 * Add a new tag to the document
	 * @param $document_id - the document id to add the tag to
	 * @param $tag - the tag to add to the document
	 * @return boolean if the update succeeded
	 */
	function add_tag($document_id, $tag){
		$query = array("_id" => new MongoId($document_id));
		$update = array('$push' => array("tags" => $tag));
		return $this->mongo->db->documents->update($query, $update);
	}
	
	/**
	 * Remove tag from document
	 * @param $document_id - the document id to add the tag to
	 * @param $tag - the tag to remove from the document
	 * @return boolean if the update succeeded
	 */
	function remove_tag($document_id, $tag){
		$query = array("_id" => new MongoId($document_id));
		$update = array('$pull' => array("tags" => $tag));
		return $this->mongo->db->documents->update($query, $update);
	}

}
/** end model **/