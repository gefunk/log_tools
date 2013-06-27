<?php

// import
require_once(APPPATH  . "/entities/LineItemCharge.php");

/**
 * @group Model
 */

class LineSurchargeTest extends CIUnit_TestCase
{
	
	private $_pcm;
	
	protected $tables = array(
		'line_item_surcharges'		 => 'line_item_surcharges'
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
		
		$this->CI->load->model('lineitem/linechargemodel');
		$this->_pcm = $this->CI->linechargemodel;

	}
	
	
	public function tearDown()
	{
		parent::tearDown();
	}
	
	
	public function testGetSurcharge()
	{
		$charge = $this->_pcm->get_charge(1);
		$this->assertEquals($charge->id, 1);
		$this->assertEquals($charge->amount, 100);
		$this->assertEquals($charge->application,1);
		$this->assertEquals($charge->effective, '2010-01-11');
		$this->assertEquals($charge->expires, '2011-10-01');
		
	}
	
	/**
	 * public $id;
	public $line_item_id;
    public $application;
    public $charge_code;
    public $effective;
    public $expires;
    public $currency;
    public $amount;
    public $deleted;
	 */
	public function testAddSurcharge()
	{
		$charge = LineItemCharge::initLineItemCharge(1, 1, 1, '2011-01-01', '2012-01-01', 1, 500.00);
		$charge_id = $this->_pcm->add_charge($charge);
		$dbcharge = $this->_pcm->get_charge($charge_id);
		$this->assertEquals($dbcharge->id, $charge_id);
		$this->assertEquals($dbcharge->line_item_id, $charge->line_item_id);
		$this->assertEquals($dbcharge->application,$charge->application);
		$this->assertEquals($dbcharge->amount,$charge->amount);
		$this->assertEquals($dbcharge->deleted, 0);
		$this->assertEquals($dbcharge->effective, $charge->effective);
		$this->assertEquals($dbcharge->expires,$charge->expires);
	}
	
	public function testGetSurchargeForLineItems()
	{
		$charges = $this->_pcm->get_charges_for_line_item(1);
		
		foreach($charges as $dbcharge){
			$this->assertEquals($dbcharge->line_item_id, 1);
		}
		
	}
	
	
} // end of linesurchargetest.php 
	