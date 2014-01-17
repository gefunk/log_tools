<?php

/**
 * 
 * Interface to remote storage
 * This class will serve as a layer of abstraction for any 
 * 
 * @author rahulgokulnath
 * 
 */
class Datastore extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		// Load Amazon library
		$this->load->library('awsinterface');
	}
	
	/**
	* retrieve asset from S3
	* @param $asset the key to the asset
	* @return url to the asset
	*/
	public function get($asset)
	{
		$bucket = $this->config->item("bucket");		
		$keypath = $bucket."/".$asset;
		return $this->awsinterface->getUrlForRemoteFile($keypath);
	}

	/**
	* put a new file on the remote store
	* @param $filepath the source path to the file, locally on the server 
	* @param $keyname which path to store the file remotely
	*/
	public function put($filepath, $keyname)
	{
		$bucket = $this->config->item("bucket");	
		return $this->awsinterface->putObject($bucket, $keyname, $filepath);
	}
	
	/**
	 * Move a file on AWS, without having stored it locally
	 * @param $original_path - where the file is currently
	 * @param $new_path - where you want to put the file
	 */
	public function move($original_path, $new_path){
		$bucket = $this->config->item("bucket");	
		if( $this->awsinterface->copyObject($bucket, $original_path, $bucket, $new_path)){
			// delete the original object, after the move is sucessful
			return $this->awsinterface->deleteObject($bucket, $original_path);	
		}
		return false;
		
	}
	
	
}