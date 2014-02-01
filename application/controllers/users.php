<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_In_Controller {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
		$this->load->model("usermodel");
		$this->load->model("customermodel");
		$this->load->library('form_validation');
		$this->load->library('nginxcache');
		$this->load->library('auth');
	}

	public function index(){
		// get customer id of logged in user
		$customer_id = $this->session->userdata("customer_id");
		// get logged in user 
		$data['logged_in_user'] = $this->usermodel->get_user_for_hash($this->session->userdata('amfitir_loggedin'));
		// get all users
		$data['users'] = $this->usermodel->get_users_for_customer($customer_id);
		
		$header_data['title'] = "Manage Users";
		$header_data['page_css'] = array('user-list.css');
		$footer_data['selected_link'] = "users";
		$footer_data['scripts'] = array('users/all.js');
		
		$user_header_data["user_link"] = "all";
		
		$this->load->view('header', $header_data);
		$this->load->view("users/user-header", $user_header_data);
		$this->load->view('users/all', $data);
		$this->load->view('footer', $footer_data);
	}

	/**
	 * Add new user
	 */
	public function add(){
		
		$view = NULL;
		$data = array();
		
		/**
		 * validate user add information
		 */
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'is_natural');
		$this->form_validation->set_message('is_natural', '%s field has to be a valid phone number');
		
		
		$header_data['title'] = "Add User";
		$header_data['page_css'] = array('new-user.css','app/main/main.css');
		$footer_data['selected_link'] = "users";
		$footer_data['scripts'] = array('main/main.js');
		$user_header_data["user_link"] = "new";
			
		if ($this->form_validation->run() != FALSE)
		{
			
			
			// validation success!, get the form information
			$name = $this->input->post("name");
			$email = $this->input->post("email");
			$phone = $this->input->post("phone");
			$notes = $this->input->post("notes");
			$customer_id = $this->session->userdata("customer_id");
			$logged_in_user = $this->usermodel->get_user_for_hash($this->session->userdata('amfitir_loggedin'));
			$customer = $this->customermodel->get_by_id($customer_id);
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
			$data['register_user'] = $logged_in_user['name'];
			$data['customer'] = $customer->name;
			$data['customer_link'] = "https://".$customer->subdomain.".amfitir.com";
			$data['temp_password'] = $password; 
			$msg = $this->load->view('email/welcome', $data, true);
        	$this->email->message($msg);
        	$this->email->send(); 
			
			
			// expire cache for user listing screen
			
			$this->nginxcache->expire($customer->subdomain, 'users');
			
			$data['success'] = "User $name has been registered";
			
			
		}
		
		$this->load->view('header', $header_data);
		$this->load->view("users/user-header", $user_header_data);
		$this->load->view('users/new', $data);
		$this->load->view('footer', $footer_data);
		
		
	}
	
	
	/**
	 * change the role of a user from the user management screen
	 */
	public function changerole(){
		$email = $this->input->post("user_identity");
		$role =  $this->input->post("role");
		$customer_id = $this->session->userdata("customer_id");
		// expire nginx cache after role change
		$customer = $this->customermodel->get_by_id($customer_id);
		$this->nginxcache->expire($customer->subdomain, 'users');
		
		return $this->usermodel->set_user_role($role,$email, $customer_id);
		
	}
	
	/**
	 * change the status of a user from the user management screen
	 */
	public function changestatus(){
		$email = $this->input->post("user_identity");
		$status = (boolean) $this->input->post("status");
		$customer_id = $this->session->userdata("customer_id");
		// expire nginx cache after status change
		$customer = $this->customermodel->get_by_id($customer_id);
		$this->nginxcache->expire($customer->subdomain, 'users');
		
		return $this->usermodel->set_user_status($status,$email, $customer_id);
		
	}

}