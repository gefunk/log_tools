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
	 * Converts a Mongo Cursor to Object
	 * Also converts Mongo Id to string for convenience
	 * @param $cursor - mongo cursor or mongo object
	 */
	protected function convert_mongo_result_to_object($mongo_result){
		if($mongo_result instanceof MongoCursor){
			$results = array();
			foreach($mongo_result as $doc){
				$results[] = $this->convert_id_in_mongo_object($doc);  
			}
			# check if results should be set to null
			return $results;	
		}else{
			return $this->convert_id_in_mongo_object($mongo_result);
		}			
		
	}
	
	private function convert_id_in_mongo_object($mongo_object){
		$converted_object = (object) $mongo_object;
		if(isset($converted_object->_id) && is_object($converted_object->_id) ){
			$converted_object->id = (string) $converted_object->_id;
		}
		return $converted_object;
	}

}



