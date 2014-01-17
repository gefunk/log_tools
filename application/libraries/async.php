<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Async {

	/**
	 * Asynchronous post - Does not wait for result
	 * @param $url - url to post to
	 * @param $param - the data to send to the url
	 */
	function post($url, $params = array()){

	    $post_params = array();

	    foreach ($params as $key => &$val) {
	          if (is_array($val)) $val = implode(',', $val);
	            $post_params[] = $key.'='.urlencode($val);
	        }
	        $post_string = implode('&', $post_params);

	        $parts=parse_url($url);

	        $fp = fsockopen($parts['host'],
	            isset($parts['port'])?$parts['port']:80,
	            $errno, $errstr, 30);

	        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
	        $out.= "Host: ".$parts['host']."\r\n";
	        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	        $out.= "Content-Length: ".strlen($post_string)."\r\n";
	        $out.= "Connection: Close\r\n\r\n";
	        if (isset($post_string)) $out.= $post_string;

	        fwrite($fp, $out);
	        fclose($fp);
	}
	
	/**
	 * Asynchronous GET
	 * Does not wait for the page to return
	 * @param $url - the url to make the request to
	 */
	function get($url){
		$parts=parse_url($url);

	    $fp = fsockopen($parts['host'],
	            isset($parts['port'])?$parts['port']:80,
	            $errno, $errstr, 30);

	    $out = "GET ".$parts['path']." HTTP/1.1\r\n";
	    $out.= "Host: ".$parts['host']."\r\n";
	    $out.= "Accept: text/html\r\n";
	    $out.= "User-Agent: Amfitir/Cache Expire 0.1\r\n";
	    $out.= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
	    fclose($fp);
	}

	/**
	 * get random strings for upload paths on remote data store
	 * @param $length - optional size of the random string
	 */
	function generate_random_path($length = 10) {
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
        	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
}
}