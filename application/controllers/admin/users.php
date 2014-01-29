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
	
	public function new_user($customer_id){
		$header_data['title'] = "Add new user";
		$header_data['page_css'] = array();
		
		$data['users'] = $this->usermodel->get_users_for_customer($customer_id);
		$data["customer"] =  $this->customermodel->get_by_id($customer_id);
		$data['page'] = 'users';

		$this->load->view('admin/header', $header_data);
		$this->load->view("admin/customers/manager-header", $data);
		$this->load->view('admin/users/new', $data);
		$this->load->view("admin/customers/manager-footer");
		$this->load->view('admin/footer');
	}
	
	public function add(){
			$name = $this->input->post("name");
			$email = $this->input->post("email");
			$phone = $this->input->post("phone");
			$notes = $this->input->post("notes");
			$customer_id = $this->input->post("customer_id");
			$customer = $this->customermodel->get_by_id($customer_id);
			$admin_user = $this->adminusermodel->get_from_hash($this->session->userdata('amfitir_admin'));
			// generate temporary password
			$password = $this->auth->generatePassword();
			// save the user
			$this->usermodel->add($email, $password, $name, $phone, $notes, $customer_id);
			// send email to user
			$this->load->library('email');
        	$this->email->set_newline("\r\n");
        	$this->email->from($this->config->item("email_from"),'Do Not Reply'); // change it to yours
        	$this->email->to($email); // change it to yours
        	$this->email->subject("Congratulations, You have been registered for $customer->subdomain.amfitir.com");
			$data['subject'] = "Please log on to Customer Site";
			$data['register_user'] = $admin_user->first_name." ".$admin_user->last_name." (amfitir-support)";
			$data['customer'] = $customer->name;
			$data['customer_link'] = "https://".$customer->subdomain.".amfitir.com";
			$data['temp_password'] = $password; 
			$msg = $this->load->view('email/welcome', $data, true);
        	$this->email->message($msg);
        	$this->email->send(); 
			
		redirect("/admin/users/all/".$customer_id);
		
	}


	public function reset_password(){
		$customer_id = $this->input->post("customer_id");
		$user_id = $this->input->post("user_id");
		$new_password = $this->auth->generatePassword();
		if($this->usermodel->change_password($new_password, $customer_id, NULL, $user_id)){
			
			// set user to change password on signon
			$this->usermodel->change_password_on_next_signin(NULL, NULL, $user_id);
			
			$user = $this->usermodel->get_by_id($user_id);
			$customer = $this->customermodel->get_by_id($customer_id);
			$admin_user = $this->adminusermodel->get_from_hash($this->session->userdata('amfitir_admin'));
			
			$this->load->library('email');
        	$this->email->set_newline("\r\n");
        	$this->email->from($this->config->item("email_from"),'Amfitir Support'); // change it to yours
        	$this->email->to($user->email); // change it to yours
        	$data['customer_link'] = "https://".$customer->subdomain.".amfitir.com";
        	$this->email->subject("Your password has been changed for ".$data['customer_link']);
			$data['requesting_user'] = $admin_user->first_name." ".$admin_user->last_name." (amfitir-support)";
			$data['password'] = $new_password;
			$data['name'] = $user->name;
			$msg = $this->load->view('email/reset', $data, true);
        	$this->email->message($msg);
        	$this->email->send(); 
			$this->output
		    	->set_content_type('application/json')
		    	->set_output( json_encode(array("success" => TRUE)));
		}else{
			$this->output
		    	->set_content_type('application/json')
		    	->set_output( json_encode(array("success" => FALSE)));
		}
		
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