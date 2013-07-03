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
	public function add_line_item($lineitem, $log=TRUE)
	{
		

		
		$data = array(
			"origin" => $lineitem->origin,
			"origin_type" => $lineitem->origin_type,
			"destination" => $lineitem->destination,
			"destination_type" => $lineitem->destination_type,
			"cargo" => $lineitem->cargo,
			"effective" => $lineitem->effective,
			"expires" => $lineitem->expires,
			"currency" => $lineitem->currency,
			"service" => $lineitem->service,
			"contract" => $lineitem->contract
		);
		
		$this->db->insert('line_items', $data);
        $line_item_id = $this->db->insert_id();
        
        if($log)
		  $this->amfitirlog->log_new_contract_line_item($line_item_id);
        
		return $line_item_id;
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
        $query = $this->db->get_where("line_items", array("id" => $id));
        $line_item = NULL;
        if($query->num_rows() > 0){
            $row = $query->row();
            $line_item = self::convertRowToLineItem($row);
        }
        return $line_item;
    }
    
    
    /**
     * get all line items for a contract
     */
    public function get_line_items_for_contract($contract_id){
        $query = $this->db->get_where("line_items", array("container" => $container_id));
        $line_items = NULL;
        if($query->num_rows() > 0){
            $line_items = array();
            foreach($query->result() as $row){
                array_push($line_items, self::convertRowToLineItem($row));
            }   
        }
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

