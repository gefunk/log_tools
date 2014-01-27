<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Used to Selectively Expire Nginx Cache 
 * When something must be refreshed immediately
 * use this class to send nginx a message to dump its cache
 * of the specific url
 *
 * @author rahulgokulnath
 */
class NginxCache {

	public function __construct()
	{
		$this->load->library('async');
	}


	/**
	 * Expire a cache item in the nginx cache
	 * useful when the user changes something and you want it to reflect immediately
	 * 
	 * this will automatically figure out the subdomain and
	 * 
	 * @param $customer_subdomain - the subdomain for the customer (ex: demo, balship),
	 * whatever the customer space is 
	 * @param $uri - the page url you want to expire, should include any 
	 * parameters, example: 'users', 'users/new', 'main', '/'
	 * @param $method - the HTTP method POST or GET, by default it will always be get
	 */
	function expire($customer_subdomain,$uri_path, $method='GET'){
		// only if we are in prod or testing	
		if (defined('ENVIRONMENT') && ENVIRONMENT != 'development'){
			// construct cache key - used by nginx to expire
			$cache_key = "$method$customer_subdomain.amfitir.com/$uri_path";
			// send a message to all the prod cache servers to expire this url	
			$servers = $this->config->item('amfitir_servers');
			// expire cache on all the amfitir servers - stored in config
			foreach($servers as $server){
				$this->async->get("http://$server/purge/$cache_key");
			}	
		}
	}
	
		
	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}
	

}