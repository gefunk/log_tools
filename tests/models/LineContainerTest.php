<?php

// import
require_once(APPPATH  . "/entities/LineItemEntity.php");

/**
 * @group Model
 */

class LineContainerTest extends CIUnit_TestCase
{
	
	private $_pcm;
	
	protected $tables = array(
		'line_item_containers'		 => 'line_item_containers'
		//'user'		  => 'user',
		//'user_group'	=> 'user_group'
	);
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
	}
	
	public function setUp()
	{
		parent::tearDown();
		parent::setUp();
		
		/*
		* this is an example of how you would load a product model,
		* load fixture data into the test database (assuming you have the fixture yaml files filled with data for your tables),
		* and use the fixture instance variable
		
		$this->CI->load->model('Product_model', 'pm');
		$this->pm=$this->CI->pm;
		$this->dbfixt('users', 'products')
		
		the fixtures are now available in the database and so:
		$this->users_fixt;
		$this->products_fixt;
		
		*/
		$this->CI->load->model('lineitem/containermodel');
		$this->_pcm = $this->CI->containermodel;

	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	
	public function testAddContainer()
	{
		$line_container = LineContainer::initLineContainer(1, 3, 100.00);
		$line_container_id = $this->_pcm->add_container_to_line_item($line_container);
		$line_container_test = $this->_pcm->get_container_by_id($line_container_id);
		$this->assertEquals(1, $line_container_test->line_item_id);
		$this->assertEquals(3, $line_container_test->container_type);
		$this->assertEquals(100.00, $line_container_test->price);
	}
	
	/**
	 * test get all containers for a line item
	 */
	public function testGetLineItemContainers()
	{
		$line_containers = $this->_pcm->get_containers_for_line_items(1); 
		$this->assertEquals(3, count($line_containers));	
	}
	
	/**
	 * id: 1
    * line_item_id: 1
    * container_type: 1
    * value: 100
    * deleted: 0
	*/
	public function testGetContainerById()
	{
		$line_container = $this->_pcm->get_container_by_id(1);
		$this->assertEquals(1, $line_container->id);
		$this->assertEquals(1, $line_container->line_item_id);
		$this->assertEquals(1, $line_container->container_type);
		$this->assertEquals(100, $line_container->price);
		$this->assertEquals(0, $line_container->deleted);
	}
	
	public function testDeleteContainer()
	{
		$line_container_id = 1;			
		$this->_pcm->delete_container_from_line_item($line_container_id);
		$line_container = $this->_pcm->get_container_by_id(1);
		$this->assertEquals(1, $line_container->deleted);
	}
	
	public function testDeleteAllContainersForLineItem()
	{
		$this->_pcm->delete_containers_from_line_item(1);
		$line_containers = $this->_pcm->get_containers_for_line_items(1); 
		foreach($line_containers as $container){
			$this->assertEquals(1, $container->deleted);
		}
	}

}