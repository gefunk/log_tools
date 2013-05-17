<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachments extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('assetstorage');
	}

	function index()
	{
		$this->load->view('upload', array('error' => ' ' ));
	}

	function get_asset($path)
	{
		$response = $this->assetstorage->get_asset($path);
		header('Content-Type:'.$response['header']);
		echo $response['body'];
	}

	/**
	* Upload files to server
	* @param the key to use when creating a folder in the s3 bucket or locally
	*/
	function upload_file($keypath){
		// directory to store upload in
		$uploaddir = './assets/uploads/';
		
		$response_data = array();

		
		/**
		* loop through files uploaded
		*/
		for($i=0; $i < count($_FILES); $i++){
			$file = ($_FILES["file-".$i]);
			if($file["error"] > 0){
				// file could not be uploaded
				array_push($response_data, array("file" => $file['name'], "success" => false, "message" => $file["error"]));
			}else{
				$temp_path = $file['tmp_name'];
				$orig_name = $file['name'];
				$remote_path = $keypath."/".$orig_name;
				$response_data = $this->assetstorage->upload_asset($temp_path, $remote_path);
				
			}
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($response_data));
			
	}

	function do_upload($keypath)
	{
		$config['upload_path'] = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
		//$config['max_size']	= '100';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		$this->load->library('upload', $config);
		
		$response_data = array();

		if ( ! $this->upload->do_upload())
		{
			$response_data["success"] = false;
			$response_data["message"] = $this->upload->display_errors();	
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			// derive remote path
			$remote_path = $keypath.'/'.$data['upload_data']['orig_name'];
			// upload to image storage
			$response = $this->assetstorage->upload_asset($data['upload_data']['full_path'], $remote_path);
			// delete the file
			if($response){
				unlink($data['upload_data']['full_path']);
				$response_data["filename"] = $remote_path;
			}else{
				$response_data["remote_fail"] = "Could not upload to S3 ".$response->body;
				$response_data["filename"] = $data['upload_data']['full_path'];
			}
			
			$response_data["success"] = true;

			
				
		}
		
		$this->output
		    ->set_content_type('application/json')
		    ->set_output(json_encode($response_data));
	}

}