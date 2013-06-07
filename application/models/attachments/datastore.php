<?php

class Datastore extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		// Load Amazon library
		$this->load->library('awsinterface');
	}
	
	/*
	* retrieve asset from S3
	*/
	public function get($asset)
	{
		$bucket = $this->config->item("bucket");		
		$keypath = $bucket."/".$asset;
		return $this->awsinterface->getUrlForRemoteFile($keypath);
	}

	/*
	*	passing in the file path
	*	and the key name
	*/
	public function put($filepath, $keyname)
	{
		$bucket = $this->config->item("bucket");	
		return $this->awsinterface->putObject($bucket, $keyname, $filepath);
		
	}
	
	
	
}