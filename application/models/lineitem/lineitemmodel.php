<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(ENTITIES_DIR  . "lineitementity.php");

class Lineitemmodel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->library("amfitirlog");
    }


	/**
	* add a new line item
	* @param $lineitem the line item object to insert
    * @param $log set this to FALSE if you don't want to add a line in the log, used when updating
	*/
	public function add_line_item(
							$origin, 
							$origin_type, 
							$destination, 
							$destination_type,
							$effective,
							$expires,
							$containers,
							$contract,
							$cargo)
	{
		
		$line_item = array(
			'origin' => array("id" => $origin, "type" => $origin_type),
			'destination' => array("id" => $destination, "type" => $destination_type),
			'effective' => new MongoDate($effective),
			'expires' => new MongoDate($expires),
			'contract' => $contract,
			'containers' => $containers,
			'cargo' => $cargo
		);
		$this->mongo->db->lineitems->insert($line_item);
        
		return $line_item["_id"];
	}
    
    
	
	/**
	* delete line item
	* @param $lineitem_id the id of the line item you want to delete
    * @param $log set this to FALSE if you don't want to add a line in the log, used when updating
	*/
	public function delete_line_item($lineitem_id, $log=TRUE){
		$data = array ("deleted" => 1);
		$this->db->where('id', $lineitem_id);
		$this->db->update('line_items', $data);
        
        if($log)
            $this->amfitirlog->log_delete_contract_line_item($lineitem_id);
        
        return $lineitem_id;
	}
    
    
    /**
     * update a line item
     * @param $lineitem object with all line item information
     */
    public function update_line_item($lineitem){
        $lineitem_id = $lineitem->id;
        $old_lineitem = $this->delete_line_item($lineitem_id, FALSE);
        $new_line_item = $this->add_line_item($lineitem, FALSE);
        $this->amfitirlog->log_update_contract_line_item($new_line_item, $old_lineitem);
		return $this->get_line_item($new_line_item);                  
    }
    
    /**
     * get line item based on id
     */
    public function get_line_item($id)
    {
        $line_item = $this->mongo->db->lineitems->find(array("_id" => $id));
        return $line_item;
    }
    
    
    /**
     * get all line items for a contract
	 * @return $line_items - mongo cursor to list of line items which belong to this contract
     */
    public function get_line_items_for_contract($contract_id){
        $line_items = $this->mongo->db->lineitems->find(array("contract" => $contract_id));
        return $line_items;
    }
    
    
    /**
     * utility function to convert database result to line item object
     */
    private static function convertRowToLineItem($row){
        return LineItemEntity::initLineItemFromDb(
                                                $row->id, 
                                                $row->origin, 
                                                $row->origin_type, 
                                                $row->destination, 
                                                $row->destination_type, 
                                                $row->cargo, 
                                                $row->effective, 
                                                $row->expires, 
                                                $row->currency, 
                                                $row->service,   
                                                $row->deleted,
                                                $row->contract);
    }


} // end line item model

