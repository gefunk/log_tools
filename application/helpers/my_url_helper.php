<?php

function uri_string($uri)
{
	$CI =& get_instance();
	if ($CI->config->item('enable_query_strings') == FALSE)
	{
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}
		$uri = trim($uri, '/');
	}
	else
	{
		if (is_array($uri))
		{
			$i = 0;
			$str = '';
			foreach ($uri as $key => $val)
			{
				$prefix = ($i == 0) ? '' : '&';
				$str .= $prefix.$key.'='.$val;
				$i++;
			}
			$uri = $str;
		}
	}
    return $uri;
}


function curhostname() {
 	$pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	 	$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"];
	 } else {
	  $pageURL .= $_SERVER["HTTP_HOST"];
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
			return curhostname().$CI->config->slash_item('index_page').uri_string($uri).$suffix;
		}
		else
		{
			return $CI->config->slash_item('base_url').$CI->config->item('index_page').'?'.uri_string($uri);
		}
	}else{

		return $CI->config->site_url($uri);
	}
	
}


function base_url($uri='')
{
	$CI =& get_instance();
	if (defined('ENVIRONMENT') && ENVIRONMENT == 'production'){
		return $CI->config->slash_item(curhostname()).ltrim(uri_string($uri), '/');
	}else{
		return $CI->config->slash_item('base_url').ltrim(uri_string($uri), '/');
	}
	
}


?>