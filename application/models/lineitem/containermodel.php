<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once (ENTITIES_DIR . "LineContainer.php");

class ContainerModel extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function add_container_to_line_item($linecontainer) {
        $data = array("line_item_id" => $linecontainer -> line_item_id, "value" => $linecontainer -> price, "container_type" => $linecontainer -> container_type);
        $this -> db -> insert("line_item_containers", $data);
        return $this -> db -> insert_id();
    }

    public function get_containers_for_line_items($line_item_id) {
        $query = $this -> db -> get_where("line_item_id", $line_item_id);
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
        $query = $this -> db -> get_where("line_item_id", $line_item_id);
        $container = NULL;
        if($query->num_rows() > 0){
            $row = $query->row();
            $container = LineContainer::initLineContainerFromDB($row -> id, $row -> line_item_id, $row -> container_type, $row -> value, $row -> deleted);
        }
        
        return $container;
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
