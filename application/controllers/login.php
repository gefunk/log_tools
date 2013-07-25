<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Out_Controller {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model("customermodel");
   	}

	/**
	* folks that have a session (already logged in) will never even get here.
	* because the parent class MY_Out_Controller already redirected them back in.
	*/
	public function index(){
		$this->load->view("signin");
	}
	

	/*
	* this is only a method to use for local development, this is to circumvent
	* the issue with subdomain checking locally
	*/
	public function signin_local($subdomain){
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
			
			/**
			 * this does the function of MY_Controller in the 
			 * dev environment
			 */
			$customer = $this->customermodel->get_customer_by_domain($subdomain);
			// forward to customer login page
			$customer_data = array(
				"customer_id" => $customer["id"],
				"customer_name" => $customer["name"],
				"subdomain" => $subdomain
			);
			$this->session->set_userdata($customer_data);
			$customer_cookie = array(
				'name'   => 'amfitir_customer',
				'value'  => $customer["id"],
				'expire' => '86500', // set to 24 hours
				'secure' => FALSE
			);
			$this->input->set_cookie($customer_cookie);
			log_message("debug", "Set session data: ".$this->session->userdata("subdomain"));
			$this->load->view("signin");
		}else{
			// in production and testing redirect this url to index
			redirect("login", "refresh");
		}
	}
	
	public function login_user()
	{
		$identity = $this->input->post("identity");
		$password = $this->input->post("password");
		$remember = $this->input->post("remember");
		
		$redir_link = NULL;
		
		log_message('debug', 'Login Post: '.$identity." Password: ".$password." Remember: ".$remember);
		
		// encrypt the password to test against
		if($this->auth->login($identity, $password, $remember, $this->session->userdata("customer_id"))){
			//successful login
			log_message('debug', 'SUCCESSFUL Login');
			$redir_link = "main";
		}else{
			log_message('debug', 'Could not Log You in');
			//if the login was un-successful
			//redirect them back to the login page
			$this->session->set_flashdata('messages', "incorrect login");
			if (defined('ENVIRONMENT') && (ENVIRONMENT == 'development')){
				log_message("debug", "Customer ID: ",$this->session->userdata("customer_id")." Subdomain: ".$this->session->userdata("subdomain"));
				$redir_link = 'login/signin_local/'.$this->session->userdata("subdomain");
			}else{
				log_message("debug", "this should only happen in qa or production");
				//use redirects instead of loading views for compatibility with MY_Controller libraries	
				$redir_link = 'login';
			}
		}
		
		log_message('debug', 'LOGIN REDIRECT LINK: '.$redir_link);
		redirect($redir_link, 'refresh');
	}
	

	
	public function register()
	{
		// session information already contains customer data
		$this->load->view("register");
	}
	
	/**
	* register user
	*/
	public function register_user(){

		$customer_id = $this->session->userdata("customer_id");
		$password = $this->input->post("password");
		$email = $this->input->post("email");
		$first_name = $this->input->post("first_name");
		$last_name = $this->input->post("last_name");
		$phone_no = $this->input->post("phone_no");
		$additional_data = array(
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone' => $phone_no
		);

		// add user to db
		$this->usermodel->add($email, $this->encrypt_password($password), $customer_id, $additional_data);
	}
	
}