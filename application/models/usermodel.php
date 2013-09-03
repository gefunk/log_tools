<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
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
		log_message("debug", "User Model: ".$identity." Password: ".$encrypted_password." Customer ID: ".$customer_id);
		$query = array("email" => $identity, "password" => $encrypted_password, 'customer' => intval($customer_id));
		$projection = array("password" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		
		log_message("debug", "Doc: ".$doc);
		
		/**
		 * return the encrypted password 
		 */
		if($doc['password'] == $encrypted_password){
			return $encrypted_password;
		}
		
		return FALSE;
	}
	
	/**
	 * verify admin login info vs database table
	 */
	function check_admin_login($identity, $password)
	{
		$encrypted_password = $this->encrypt_password($password);
		$where_data = array("email" => $identity, 'password' => $encrypted_password);
		$query = $this->db->get_where('admin_users', $where_data);
		if($query->num_rows() == 1){
			return $encrypted_password;
		}	
		return FALSE;
	}
	
	
	
	/**
	 * Add a new User
	 */
	function add($identity, $password, $customer_id)
	{
		$user_data = array(
			"email" => $identity,
			"password" => $this->encrypt_password($password),
			"customer" => $customer_id,
			"active" => true,
			"reset_on_signon" => true
		);
		$this->mongo->db->users->insert($user_data);
		
	}
	
	/**
	 * deactivate the user
	 */
	function deactivate($identity, $customer_id){
		$query = array("email"=>$identity, "customer" => $customer_id);
		$update = array('$set' => array("active" => false));
		return $this->mongo->db->users->update($query, $update);
	}
	
	
	/**
	 * reset a user's password
	 */
	function reset_password($identity, $customer_id, $new_password){
		$query = array("email"=>$identity, "customer" => $customer_id);
		$update = array('$set' => array("password" => $this->encrypt_password($new_password)));
		return $this->mongo->db->users->update($query, $update);
	}
	
	/**
	*
	* @param $hash the hash stored in the cookie
	* @param $customer_id to check whether this is the hash stored for this customer
	*/
	function is_customer_valid_for_login_hash($hash, $customer_id)
	{
		$query = array("customer" => intval($customer_id), "password" => $hash);
		$projection = array("customer" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		// if the customer id is equal to the one passed in return true
		if($doc['customer'] == $customer_id){
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	* check the admin login status
	* @param $hash the hash stored in the cookie
	*/
	function is_admin_valid_for_login_hash($hash)
	{
		$this->db->select("id")->from("admin_users")->where("password", $hash);
		$query = $this->db->get();
		// should only be one result for a hash
		if($query->num_rows() == 1){
			return TRUE;
		}
		return FALSE;
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