<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
	}
	
	public function get($key){
		var_dump($this->cache->get($key));
	}
	
	public function delete($key){
		$this->cache->delete($key);
	}
	
	public function reset(){
		$this->cache->clean();
	}
	
	
}