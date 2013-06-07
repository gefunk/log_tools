<?php

class Assetstorage extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		// Load Amazon library
		$this->load->library('awslib');
		// Define a mebibyte
		define('MB', 1048576);

	}
	
	/**
	* retrieve an asset either from s3 or locally
	* @param local specifies whether asset is local or remote
	*/
	public function get_asset($keyname, $local=FALSE)
	{
		if($local){
			// return local asset
		}else{
			return $this->get_asset_remote($keyname);
		}
	}
	
	protected function get_asset_remote($keyname)
	{
		$bucket = $this->config->item("bucket");		
		$s3 = new AmazonS3();
		$response = $s3->get_object(
		                      $bucket,
		                      $keyname);
		// Success?
		if($response->isOK())
		{
			return array(
				"header" => $response->header['content-type'],
				"body" => $response->body
			);
		}
	}


	protected function get_asset_local($keyname)
	{
		$uploaddir = './assets/uploads/';
		
	}

	public function upload_asset($filepath, $keyname){
		$response_data = NULL;
		
		$response = $this->upload_remote($filepath,$keyname);
		if($response){
			$response_data = array("file" => $keyname, "success" => true, "local" => false);
		}else{
			// s3 could not upload so saving locally
			$local_response = $this->upload_local($filepath, $keyname);
			if($local_response["success"]){
				$response_data = array("file" => $keyname, "success" => true, "local" => true);
			}else{
				$response_data = array("success" => false, "message" => $local_response["msg"]);
			}
			
		}
		
		return $response_data;
	}

	/*
		passing in the file path
		and the key name
	*/
	protected function upload_remote($filepath, $keyname)
	{
		$bucket = $this->config->item("bucket");

		// Instantiate the class
		$s3 = new AmazonS3();

		// 1. Initiate a new multipart upload. (Array parameter is optional)
		$response = $s3->initiate_multipart_upload($bucket, $keyname, array(
		    'storage' => AmazonS3::STORAGE_REDUCED
		));

		if (!$response->isOK())
		{
		    throw new S3_Exception('Bad!');
		}

		// Get the Upload ID.
		$upload_id = (string) $response->body->UploadId;

		// 2. Upload parts.
		// Get part list for a given input file and given part size.
		// Returns an associative array.
		$parts = $s3->get_multipart_counts(filesize($filepath), 5*MB);

		$responses = new CFArray(array());

		foreach ($parts as $i => $part)
		{
		    // Upload part and save response in an array.
		    $responses[] = $s3->upload_part($bucket, $keyname, $upload_id, array(
		        'fileUpload' => $filepath,
		        'partNumber' => ($i + 1),
		        'seekTo' => (integer) $part['seekTo'],
		        'length' => (integer) $part['length'],
		    ));
		}

		// Verify that no part failed to upload, otherwise abort.
		if (!$responses->areOK())
		{
		    // Abort an in-progress multipart upload
		    $response = $s3->abort_multipart_upload($bucket, $keyname, $upload_id);

		    throw new S3_Exception('Failed!');
		}

		// 3. Complete the multipart upload. We need all part numbers and ETag values.
		$parts = $s3->list_parts($bucket, $keyname, $upload_id);
		$response = $s3->complete_multipart_upload(
		                             $bucket, $keyname, $upload_id, $parts);

		return $response->isOK();
		
	}
	
	
	
	protected function upload_local($filepath, $keyname)
	{
		$uploaddir = './assets/uploads/';
		
		$dir = $uploaddir.substr($keyname, 0, strrpos ( $keyname , "/" ));
		
		
		if(!is_dir($dir)){
			if (mkdir($dir, 0777, true)) {
				move_uploaded_file($filepath, $uploaddir.$keyname);
			}else{
				$error = "AssetStorage - Error: Could not make dir: ".$dir;
				error_log($error);
				return array("success" => false, "msg" => $error);
			}
		}
		
		return array("success" => true);
		
	}
}