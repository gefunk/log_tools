<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Base Model to help load specifid drivers
 */
class MY_Model extends CI_Model{
	public function __construct(){
	    parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
	}
	
}

/**
 * Any class that wants to use mongo, should use this
 */
class Base_Model extends MY_Model{

	public function __construct(){
	    parent::__construct();
		
	}

	/**
	 * @param $cursor - mongo cur
	 */
	protected function convert_mongo_cursor_to_object($cursor){
		$results = array();
		foreach($cursor as $doc){
			$results[] = (object) $doc; 
		}
		# check if results should be set to null
		return $results;
	}

}



