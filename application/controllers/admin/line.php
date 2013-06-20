<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once(ENTITIES_DIR  . "LineItemEntity.php");
require_once(ENTITIES_DIR  . "LineContainer.php");

class Line extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("lineitem/lineitemmodel");
        $this->load->model("lineitem/containermodel");
	}

	
	public function test_all()
	{
		$line_item = LineItemEntity::initLineItem(1, 1, 1, 1, 1, '2013-01-01', '2013-10-01', 1, NULL);
        $line_container = LineContainer::initLineContainer(1, 1);
        $line_item->containers = array($line_container);
		echo $this->lineitemmodel->add_line_item($line_item);
        echo $this->containermodel->add_container_to_line_item($linecontainer);
		
	}
	
	public function test()
	{
		$line_container = LineContainer::initLineContainer(1, 1, 100.00);
		echo $this->containermodel->add_container_to_line_item($line_container);
	}


} // end controller