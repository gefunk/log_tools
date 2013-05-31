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
	
	public function isLoggedIn()
	{
		$remember_cookie = get_cookie('amfitir_remember');
		
		error_log("User Cookie: ".$remember_cookie);
		if($this->session->userdata("amfitir_loggedin")){
			return TRUE;
		}else if($remember_cookie){
			// add the hash back to the session
			$this->session->set_userdata('amfitir_loggedin', $remember_cookie['value']);
			return TRUE;
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
		$login_hash = $this->usermodel->check_login($identity, $password, $customer_id);
		log_message('debug', 'REMEMBER: '.$remember);
		$login_result = !empty($login_hash);
		log_message('debug', "login_hash: ".$login_hash." login_result: ".$login_result);
		
		// if login successful and remember is set to true set cookie
		// set cookies SECURE to false unless using HTTPS
		if($login_result){
			if($remember){
				$remember_cookie = array(
				    'name'   => 'amfitir_remember',
				    'value'  => $login_hash,
				    'expire' => '86500', // set to 24 hours
				    'secure' => FALSE
				);
				set_cookie($remember_cookie);
			}
			// set session cookie
			$this->session->set_userdata('amfitir_loggedin', $login_hash);
		}
		return $login_result;
	}
	
	
	
	/**
	* log user out of the system
	*/
	public function logout()
	{
		// remove cookie
		// record log out
		$this->session->unset_userdata('amfitir_loggedin');
		delete_cookie('amfitir_remember');
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
	
	
	
}