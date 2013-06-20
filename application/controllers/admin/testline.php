<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(ENTITIES_DIR  . "LineItemEntity.php");
require_once(ENTITIES_DIR  . "LineContainer.php");

class TestLine extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("lineitem/lineitemmodel");
        $this->load->model("lineitem/containermodel");
		
		// library for unit testing
		$this->load->library('unit_test');
	}
	
	public function testlineitems(){
		// test adding new line item
		$line_item = LineItemEntity::initLineItem(1, 1, 1, 1, 1, '2013-01-01', '2013-10-01', 1, NULL, 1);
		$line_item->id = $this->lineitemmodel->add_line_item($line_item);
		$this->unit->run($line_item->id, "is_int", "Add Line Item");
		// test adding a new line item container
		$line_container = LineContainer::initLineContainer($line_item->id, 1, 100.00);
		$line_container->id = $this->containermodel->add_container_to_line_item($line_container);
		$this->unit->run($line_container->id, "is_int", "Add Line Item Container");
		// test updating the line item
		$line_item->effective = '2013-11-10';
		$line_item->id = $this->lineitemmodel->update_line_item($line_item);
		$this->unit->run($line_item->id, "is_int", "Updated Line Item DB update successful");
		// test value of updated line item
		$updated_line_item = $this->lineitemmodel->get_line_item($line_item->id);
		$this->unit->run($line_item->effective, $updated_line_item->effective, "Updated Line Item Date Equal");
		
		echo $this->unit->report();
	}

	
}// test all functions regarding lines