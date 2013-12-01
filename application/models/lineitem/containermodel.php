<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (ENTITIES_DIR . "linecontainer.php");

class ContainerModel extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }


	function get_ref_container_types(){
		$this->db->select("id, description")->from("ref_container_types");
		$query = $this->db->get();
		return $query->result();
	}


	/**
	* Add a line container to a line item, save it to the DB
	* @param $linecontainer the line container object to add to the db
	*/
    function add_container_to_line_item($linecontainer) {
        $data = array("line_item_id" => $linecontainer -> line_item_id, "value" => $linecontainer -> price, "container_type" => $linecontainer -> container_type);
        $this -> db -> insert("line_item_containers", $data);
        return $this -> db -> insert_id();
    }

	/**
	* Returns a list of containers for a line item
	* @param $line_item_id the id of the line item for which you want all containers
	*/
    public function get_containers_for_line_items($line_item_id) {
        $query = $this -> db -> get_where("line_item_containers", array("line_item_id" => $line_item_id));
        $containers = NULL;
        if ($query -> num_rows() > 0) {
            $containers = array();
            foreach ($query->result() as $row) {
                array_push($containers, LineContainer::initLineContainerFromDB($row -> id, $row -> line_item_id, $row -> container_type, $row -> value, $row -> deleted));
            }
        }
        return $containers;
    }
    
    public function get_container_by_id($linecontainer_id)
    {
    	
		
        $query = $this -> db -> get_where("line_item_containers", array("id" => $linecontainer_id));
        $container = NULL;
        if($query->num_rows() > 0){
            $row = $query->row();
            $container = LineContainer::initLineContainerFromDB($row -> id, $row -> line_item_id, $row -> container_type, $row -> value, $row -> deleted);
        }
        
        return $container;
    }


	function add_container_to_contract($contract_id, $rational_container_id, $container_name){
		 // add container to mongo
		$query = array("_id"=>new MongoId($contract_id));
		$update = array('$push' => array("containers" => array("type"=> $rational_container_id, "text" => $container_name)));
		return $this->mongo->db->contracts->update($query, $update);
	}
	
	function get_containers_for_contract($contract_id){
		$query = array("_id"=>new MongoId($contract_id));
		$projection =  array("_id"=>FALSE, "containers"=>TRUE);
		$doc = $this->mongo->db->contracts->findOne($query, $projection);
		if(isset($doc) && isset($doc['containers'])){
			foreach($doc['containers'] as &$container){
				$this->db->select('description')->from('ref_container_types')->where('id', $container['type']);
				$dbquery = $this->db->get();
				if ($dbquery->num_rows() > 0)
					$container['type_text'] = $dbquery->row()->description;
			}
			return $doc['containers'];
		}else{
			return NULL;
		}
	}
	
	function remove_container_from_contract($contract_id, $rational_container_id){
		//db.contracts.update( { "_id": 28 }, { $pull: { "containers": {"type":"1"} } } )
		$query = array("_id"=>new MongoId($contract_id));
		$update = array('$pull' => array("containers" => array("type"=> $rational_container_id)));
		$this->mongo->db->contracts->update($query, $update);
	}
	
    /**
     * delete one container entry
     * @param $line_container_id the container id to remove from the line item
     */
    function delete_container_from_line_item($line_container_id) {
        $data = array("deleted" => 1);
        $this -> db -> where('id', $line_container_id);
        $this -> db -> update('line_item_containers', $data);
        return $line_container_id;
    }

    /**
     * delete all containers for this line item
     * @param $line_item_id remove all containers for this line item
     */
    function delete_containers_from_line_item($line_item_id) {
        $data = array("deleted" => 1);
        $this -> db -> where('line_item_id', $line_item_id);
        $this -> db -> update('line_item_containers', $data);
        return $line_item_id;
    }

} // end containermodel.php
