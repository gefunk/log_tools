<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('awslib');
		$this->load->model('attachments/datastore');
		$this->load->helper('form');
		$this->load->model('customermodel');
		$this->load->model("referencemodel");
	}

	public function index()
	{
		$data['customers'] = $this->customermodel->get_customers();
		$data['currency_codes'] = $this->referencemodel->get_currency_codes(FALSE);
		$header_data['title'] = "Customers";
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/customers/view', $data);
		$this->load->view('admin/footer');		
	}
	
	/*
	* add new customer
	*/
	public function add()
	{
		$this->output->enable_profiler(TRUE);
		$this->customermodel->add($this->input->post("customer_name"),
								  $this->input->post("currency_code"),
								  $this->input->post("subdomain"));
		redirect('admin/customer');
	}

	public function manager($customer_id){
			
		$data['customer'] = $this->customermodel->get_customer_by_id($customer_id);
		$header_data['title'] = "Manage Customer - ".$data['customer']->name;
		$data['page'] = 'customers';
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/customers/manager-header', $data);
		$this->load->view('admin/customers/manager-view', $data);
		$this->load->view("admin/customers/manager-footer");
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

