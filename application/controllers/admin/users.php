<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->model('customermodel');
		$this->load->model('usermodel');		
		$this->load->library('nginxcache');
	}
	
	public function all($customer_id){
		
		$header_data['title'] = "User List";
		$header_data['page_css'] = array('app/admin/users/all.css');
		
		$footer_data["scripts"] = array('admin/users/all.js');
		
		$data['users'] = $this->usermodel->get_users_for_customer($customer_id);
		$data["customer"] =  $this->customermodel->get_by_id($customer_id);
		$data['page'] = 'users';

		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/users/all', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer', $footer_data);
	}

	public function set_status(){
		$status = (boolean) $this->input->post("status");
		$customer_id = $this->input->post("customer_id");
		$user_id = $this->input->post("user_id");
		// expire nginx cache after status change
		$customer = $this->customermodel->get_by_id($customer_id);
		$this->nginxcache->expire($customer->subdomain, 'users');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => $this->usermodel->set_user_status($status, NULL, NULL, $user_id))));
	}
	
	public function set_role(){
		$user_id = $this->input->post("user_id");
		$role =  $this->input->post("role");
		$customer_id = $this->input->post("customer_id");
		// expire nginx cache after role change
		$customer = $this->customermodel->get_by_id($customer_id);
		$this->nginxcache->expire($customer->subdomain, 'users');
		$this->output
		    ->set_content_type('application/json')
		    ->set_output( json_encode(array("success" => $this->usermodel->set_user_role($role, NULL, NULL, $user_id))));
	}

}