<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('awslib');
		$this->load->model('attachments/datastore');
		$this->load->helper('form');
		$this->load->model('customermodel');
	}

	public function index()
	{
		$data['customers'] = $this->customermodel->get_customers();
		$this->load->view('admin/header');
		$this->load->view('admin/customers/view', $data);
		$this->load->view('admin/footer');		
	}
	
	public function users($customer_id)
	{
		$data['users'] = $this->customermodel->get_users($customer_id);
		$this->load->view('admin/header');
		$this->load->view('admin/customers/users', $data);
		$this->load->view('admin/footer');
	}
	
	public function upload()
	{
		$this->load->view('admin/customers/upload_form', array('error' => ''));
	}
	
	public function upload_file()
	{
		
		$this->load->library('upload');

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('admin/customers/upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			// upload to data storage (s3)
			$this->datastore->put($data['upload_data']['full_path'], $data['upload_data']['orig_name']);
			$this->load->view('admin/customers/upload_success', $data);
		}
	}
	
}

