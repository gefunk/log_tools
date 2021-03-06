<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CarrierModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
    }

	function get_carriers()
	{
		$key = 'get_carriers';
		if(! $result = $this->cache->get($key)){
			$result = $this->mongo->db->carriers->find();
			$this->cache->save($key, $result, WEEK_IN_SECONDS);
		}
	    return $result;
	}
	
	function get_by_id($id){
		$key = 'carriermodel->get_by_id->'.$id;
		if(! $result = $this->cache->get($key)){
			$carrier_id = new MongoId($id);
			$result = $this->convert_mongo_result_to_object($this->mongo->db->carriers->findOne(array('_id' => $carrier_id)));
			if($result){
				$this->cache->save($key, $result, WEEK_IN_SECONDS);
			}
		}
		return $result;
	}
	
}
/** end model **/