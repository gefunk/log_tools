<?php



function curhostname() {
 	$pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	 	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"];
	 }
	 return $pageURL;
}

function site_url($uri = '')
{
	$CI =& get_instance();

	if (defined('ENVIRONMENT') && ENVIRONMENT == 'production'){
		$host = $_SERVER["SERVER_NAME"];
		
		if ($uri == '')
		{
			return curhostname().$CI->config->item('index_page');
		}
		if ($CI->config->item('enable_query_strings') == FALSE)
		{
			$suffix = ($CI->config->item('url_suffix') == FALSE) ? '' : $CI->config->item('url_suffix');
			return curhostname().$CI->config->slash_item('index_page').$CI->config->_uri_string($uri).$suffix;
		}
		else
		{
			return $CI->config->slash_item('base_url').$CI->config->item('index_page').'?'.$CI->config->_uri_string($uri);
		}
	}else{

		return $CI->config->site_url($uri);
	}
	
}

?>