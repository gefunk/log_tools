<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* library to handle authentication
*/

class Auth
{
	
	public function __construct()
	{
		$this->load->helper('cookie');
		//Load the session, CI2 as a library, CI3 uses it as a driver
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->load->library('session');
		}
		else
		{
			$this->load->driver('session');
		}
		
		$this->load->model('usermodel');
	}
	
	
	/**
	* check if user is coming in through customer url
	*/
	public function hasCustomerSession()
	{
		if($this->session->userdata("subdomain")){
			return TRUE;
		}
		return FALSE;
	}
	
	
	/**
	* checks if the user is logged into the site
	* @return boolean if user logged TRUE else FALSE
	*/
	public function isLoggedIn()
	{
		$login_hash = $this->session->userdata("amfitir_loggedin");
		$customer_id = $this->session->userdata("customer_id");
		log_message("debug", "Checking logged in hash: ".$login_hash." Customer ID: ".$customer_id);
		/**
		 * if the logged in session 
		 * or the cookie are set then try to look up
		 * the user data and log the user in
		 * 
		 * if they are not set then return not logged in
		 */
		if($login_hash === FALSE){
			// try to retrieve from cookie
			$login_hash = get_cookie('amfitir_remember');
			if($login_hash !== FALSE)
				$this->session->set_userdata('amfitir_loggedin', $login_hash);
		}
		if($customer_id === FALSE){
			$customer_id = get_cookie('amfitir_customer');
			// if successful from cookie put it back into the session
			if($customer_id !== FALSE)
				$this->session->set_userdata('customer_id', $customer_id);
		}
		
				
		/*
		* check if this login hash is valid for this customer
		*/
		if($login_hash !== FALSE && $customer_id !== FALSE){
			if($this->usermodel->is_customer_valid_for_login_hash($login_hash, $customer_id)){
				return TRUE;
			}else{
				// this user doesn't match for the customer, log them out
				$this->logout();
			}
		}else{
			/**
		 	* Haven't been able to restore from cookie 
		 	*
		 	* check if an admin is trying to access
		 	* we want to allow admin's to view any customer
		 	*/
			$login_hash = $this->session->userdata("amfitir_admin");
			// user is an admin
			if($this->usermodel->is_admin_valid_for_login_hash($login_hash)){
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	* checks if the user is logged into the site
	* @return boolean if user logged TRUE else FALSE
	*/
	public function isAdminLoggedIn()
	{
		// load session and cookie, returns false if not set
		$login_hash = $this->session->userdata("amfitir_admin");
		/**
		 * if the logged in session 
		 * or the cookie are set then try to look up
		 * the user data and log the user in
		 * 
		 * if they are not set then return not logged in
		 */
		if($login_hash === FALSE){
			$login_hash =  get_cookie('amfitir_admin');
			log_message("debug", "using remember cookie ".$login_hash);
			// found remember cookie
			// add the hash back to the session
			$this->session->set_userdata('amfitir_admin', $login_hash);
		}	
		/*
		* check if this login hash is valid for this customer
		*/
		if($login_hash !== FALSE){
			if($this->usermodel->is_admin_valid_for_login_hash($login_hash)){
				return TRUE;
			}else{
				// this user doesn't match for the customer, log them out
				$this->logout();
			}
		}
		return FALSE;
		
	}
	
	/**
	* check the user login
	* @param $identity used to verify username
	* @param $password password to check in
	* @param $remember whether to set a cookie for 24 hours
	* @param $customer_id the user belongs to this customer
	*/
	public function login($identity, $password, $remember, $customer_id)
	{
		log_message("debug", "Passed into Auth: ".$identity." Password: ".$password." Customer ID: ".$customer_id);
		$login_hash = $this->usermodel->check_login($identity, $password, $customer_id);
		log_message('debug', 'REMEMBER: '.$remember);
		$login_result = !empty($login_hash);
		log_message('debug', "login_hash: ".$login_hash." login_result: ".$login_result);
		
		/**
		 * in dev we are using HTTP so no secure cookie
		 * but in prod we are HTTPS so secure the cookie
		 */
		$cookie_secure = TRUE;
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
			$cookie_secure = FALSE;
		}
		// if login successful and remember is set to true set cookie
		// set cookies SECURE to false unless using HTTPS
		if($login_result){
			log_message("debug", "Login Result".$login_result);
			if($remember){
				log_message("debug","Setting remember cookie");
				$remember_cookie = array(
				    'name'   => 'amfitir_remember',
				    'value'  => $login_hash,
				    'expire' => '86500', // set to 24 hours
				    'secure' => $cookie_secure
				);
				set_cookie($remember_cookie);
			}
			// set session cookie
			$this->session->set_userdata('amfitir_loggedin', $login_hash);
		}
		return $login_result;
	}
	
	
	/**
	 * check Admin Login
	 */
	/**
	* check the user login
	* @param $identity used to verify username
	* @param $password password to check in
	* @param $remember whether to set a cookie for 24 hours
	* @param $customer_id the user belongs to this customer
	*/
	public function admin_login($identity, $password, $remember)
	{
		log_message("debug", "Passed into Auth: ".$identity." Password: ".$password);
		$login_hash = $this->usermodel->check_admin_login($identity, $password);
		log_message('debug', 'REMEMBER: '.$remember);
		$login_result = !empty($login_hash);
		log_message('debug', "login_hash: ".$login_hash." login_result: ".$login_result);
		
		$cookie_secure = TRUE;
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
			$cookie_secure = FALSE;
		}
		
		
		// if login successful and remember is set to true set cookie
		// set cookies SECURE to false unless using HTTPS
		if($login_result){
			if($remember){
				$remember_cookie = array(
				    'name'   => 'amfitir_admin',
				    'value'  => $login_hash,
				    'expire' => '300', // set to 5 minutes
				    'secure' => $cookie_secure
				);
				set_cookie($remember_cookie);
			}
			// set session cookie
			$this->session->set_userdata('amfitir_admin', $login_hash);
		}
		return $login_result;
	}
	
	
	/**
	* log user out of the system
	*/
	public function logout()
	{
		// remove cookie
		if (get_cookie('amfitir_remember'))
		{
			delete_cookie('amfitir_remember');
		}
		$this->session->unset_userdata('amfitir_loggedin');
		//Destroy the session
		//$this->session->sess_destroy();
		//$this->session->sess_create();
	}
	/**
	* log user out of the system
	*/
	public function logout_admin()
	{
		// remove cookie
		if (get_cookie('amfitir_admin'))
		{
			delete_cookie('amfitir_admin');
		}
		$this->session->unset_userdata('amfitir_admin');
		//Destroy the session
		//$this->session->sess_destroy();
		//$this->session->sess_create();
	}
	
	
	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	
	
} // end library auth