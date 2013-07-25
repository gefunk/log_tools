<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	/**
	* admin login screen 
	*/
	public function index(){
		$header_data['title'] = "Administrator Login";
		$this->load->view("admin/header", $header_data);
		$this->load->view("admin/signin");
		$this->load->view("admin/footer");
	}
	
	public function verify_admin_user()
	{
		$identity = $this->input->post("identity");
		$password = $this->input->post("password");
		$remember = $this->input->post("remember");
		
		$redir_link = NULL;
		
		log_message('debug', 'Login Post: '.$identity." Password: ".$password." Remember: ".$remember);
		
		// encrypt the password to test against
		if($this->auth->admin_login($identity, $password, $remember)){
			//successful login
			log_message('debug', 'SUCCESSFUL Login');
			$redir_link = "admin/contract";
		}else{
			log_message('debug', 'Could not Log You in');
			//if the login was un-successful
			//redirect them back to the login page
			$this->session->set_flashdata('messages', "incorrect login");
			//use redirects instead of loading views for compatibility with MY_Controller libraries	
			$redir_link = 'admin/login';
			
		}
		
		log_message('debug', 'LOGIN REDIRECT LINK: '.$redir_link);
		redirect($redir_link, 'refresh');
	}
	
	
	public function create_encrypt_password($password){
		$salt = sha1(md5($password));
		$password = md5($password.$salt);
		echo $password;
	}
	
	
}
	