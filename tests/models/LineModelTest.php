<?php

// import
require_once(APPPATH  . "/entities/LineItemEntity.php");

/**
 * @group Model
 */

class LineModelTest extends CIUnit_TestCase
{
	
	private $_pcm;
	
	protected $tables = array(
		'line_items'		 => 'line_items'
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
		$this->CI->load->model('lineitem/lineitemmodel');
		$this->_pcm = $this->CI->lineitemmodel;
	}

	public function testLineInsert(){
		$line_item = LineItemEntity::initLineItem(4,4, 4, 4, 4, '2013-01-01', '2013-10-01', 1, NULL, 1);
		$line_item->id = $this->_pcm->add_line_item($line_item);
		$this->assertEquals(4, $line_item->id);
	}	
	
	public function tearDown()
	{
		parent::tearDown();
	}


}
