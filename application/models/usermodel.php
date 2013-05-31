<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/**
	* verify login vs info in db
	* @param $identity the username to check against
	* @param $password the password to check against
	* @param $customer_id the customer id which this user belongs to
	*/
	function check_login($identity, $password, $customer_id)
	{
		$encrypted_password = $this->encrypt_password($password);
		$where_data = array("email" => $identity, 'password' => $encrypted_password, 'customer' => $customer_id);
		$query = $this->db->get_where('users', $where_data);
		if($query->num_rows() == 1){
			return $encrypted_password;
		}
		
		return FALSE;
	}
	
	function add($identity, $password, $customer_id, $additional_data)
	{
		$user_data = array(
						"email" => $identity,
						"password" => $this->encrypt_password($password),
						"customer" => $customer_id
						);
		$this->db->insert('users', $user_data);
		
	}
	
	/**
	* takes a normal string password and encrypts
	* @return string encrypted password
	*/
	protected function encrypt_password($password){
		$salt = sha1(md5($password));
		$password = md5($password.$salt);
		return $password;
	}
	
	
}