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
		$this->_logmodel = $this->CI->logmodel;
	}

	/**
	 * test line item insert
	 */
	public function testLineInsert(){
		$line_item = LineItemEntity::initLineItem(4,4, 4, 4, 4, '2013-01-01', '2013-10-01', 1, NULL, 1);
		$line_item->id = $this->_pcm->add_line_item($line_item);
		$this->assertEquals(4, $line_item->id);
		// test if the insert was logged correctly
		$log_event = $this->_logmodel->get_last_entry_for_object($line_item->id);
		$this->assertEquals(LOG_EVENT_ADD, $log_event->event_type);
		$this->assertEquals($line_item->id, $log_event->new);
		$this->assertEquals(LOG_OBJECT_LINE_ITEM, $log_event->object_type);
	}	
	
	/**
	 * test getting a line item
	 */
	public function testLineItemGet()
	{
		$line_item = $this->_pcm->get_line_item(3);
		$expected_result = 3;
		$this->assertEquals($expected_result, $line_item->id);
		
	}
	
	/*
	 * test updating a line item
	 */
	public function testLineItemDelete()
	{
		$line_item_id = $this->_pcm->delete_line_item(3);
		// test correct line item id returned
		$this->assertEquals(3, $line_item_id);
		$line_item = $this->_pcm->get_line_item($line_item_id);
		$this->assertEquals(1, $line_item->deleted);
		$log_event = $this->_logmodel->get_last_entry_for_object($line_item_id);
		// correct line item was logged
		$this->assertEquals($line_item_id, $log_event->new);
		$this->assertEquals(LOG_EVENT_DELETE, $log_event->event_type);
	}
	
	
	public function testLineItemUpdate()
	{
		$line_item = $this->_pcm->get_line_item(3);
		$line_item->effective = '2013-11-10';
		$updated_line_item = $this->_pcm->update_line_item($line_item);
		// make sure the id is not the same as the old one
		$this->assertThat($line_item->id, 
				$this->logicalNot($this->equalTo($updated_line_item->id)));
		// our new value got updated
		$this->assertEquals('2013-11-10', $updated_line_item->effective);
		// lets check the log
		$log_event = $this->_logmodel->get_last_entry_for_object($updated_line_item->id);
		$this->assertEquals($updated_line_item->id, $log_event->new);
		$this->assertEquals($line_item->id, $log_event->old);
		$this->assertEquals(LOG_EVENT_UPDATE, $log_event->event_type);
	}
	
	public function tearDown()
	{
		parent::tearDown();
	}


}
