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
		$query = array("active" => true, "email" => $identity, "password" => $encrypted_password, 'customer' => intval($customer_id));
		$projection = array("password" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		
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
	function add($identity, $password, $name, $phone, $notes, $customer_id)
	{
		$user_data = array(
			"email" => $identity,
			"password" => $this->encrypt_password($password),
			"customer" => intval($customer_id),
			"name" => $name,
			"phone" => $phone,
			"notes" => $notes,
			"role" => "regular",
			"active" => true,
			"reset_on_signon" => true
		);
		$this->mongo->db->users->insert($user_data);
	}
	
	/**
	 * check if the username is unique
	 * @param $identity the username you are checking
	 * @param $customer_id we are only worried about usernames within a customer group
	 */
	function check_unique_username($identity, $customer_id){
		$query = array("email" => $identity, "customer" => $customer_id);
		$projection = array("email" => 1);
		$cursor = $this->mongo->db->users->find($query, $projection);
		if($cursor->hasNext()){
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * deactivate the user
	 * @param the identity of the user(  email address )
	 * @param the customer for which you want to deactivate
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
		$query = array("customer" => intval($customer_id), "password" => $hash, "active" => TRUE);
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
	 * get the user name based on user hash
	 * @param $hash the login hash for the user
	 */
	function get_user_for_hash($hash){
		$query = array("password" => $hash);
		$projection = array("name"=> 1, "email" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		// if the customer id is equal to the one passed in return true
		if(isset($doc)){
			return $doc;	
		}
		return FALSE;
	}
	
	
	/**
	 * get the role for the user
	 * @param $hash - login hash which is used to verify user
	 */
	function get_role_for_user_by_hash($hash){
		$query = array("password" => $hash);
		$projection = array("role" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		// if the customer id is equal to the one passed in return true
		if(isset($doc)){
			return $doc['role'];	
		}
		return FALSE;
	}
	
	/**
	 * get all users for a customer
	 * @param $customer_id - the customer id that you want the users for
	 */
	function get_users_for_customer($customer_id){
		$query = array("customer" => intval($customer_id));
		$projection = array("active" => 1, "reset" => 1, "role" => 1, "email" => 1);
		return $this->mongo->db->users->find($query, $projection);
	}
	
	
	/**
	 * function used to generate temporary password
	 */
	function generatePassword ($length = 8)
	{
	  // given a string length, returns a random password of that length
	  $password = "";
	  // define possible characters
	  $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  $i = 0;
	  // add random characters to $password until $length is reached
	  while ($i < $length) {
	    // pick a random character from the possible ones
	    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	    // we don't want this character if it's already in the password
	    if (!strstr($password, $char)) {
	      $password .= $char;
	      $i++;
	    }
	  }
	  return $password;
	}
	
	
	/**
	 * Activate or De-activate user
	 * @param identity - the email id of the user
	 * @param $customer_id - the customer id for which the user belongs to 
	 * @param $status - boolean value of status
	 */
	function set_user_status($identity, $customer_id, $status){
		$query = array("email"=>$identity, "customer" => $customer_id);
		$update = array('$set' => array("active" => $status));
		return $this->mongo->db->users->update($query, $update);
	} 
	
	/**
	 * Set the Role for a user 
	 *
	 * @param $identity - the email id of the user
	 * @param $customer_id - the customer the user blongs to
	 * @param $role - the sting role you want to set for this user 'admin' or 'regular'
	 */
	function set_user_role($identity, $customer_id, $role){
		$query = array("email"=>$identity, "customer" => $customer_id);
		$update = array('$set' => array("role" => $role));
		return $this->mongo->db->users->update($query, $update);
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