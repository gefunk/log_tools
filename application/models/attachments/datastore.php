<?php

class Datastore extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		// Load Amazon library
		$this->load->library('awslib');
		// Define a mebibyte
		define('MB', 1048576);

	}
	
	/*
	* retrieve asset from S3
	*/
	public function get($asset)
	{
		$bucket = $this->config->item("bucket");		
		$s3 = new AmazonS3();
		$response = $s3->get_object(
		                      $bucket,
		                      $asset);
		// Success?
		if($response->isOK())
		{
			return array(
				"header" => $response->header['content-type'],
				"body" => $response->body
			);
		}
	}

	/*
		passing in the file path
		and the key name
	*/
	public function put($filepath, $keyname)
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
	
}