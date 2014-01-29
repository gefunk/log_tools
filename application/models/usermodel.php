<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
		$this->load->library('bcrypt');
    }

	/**
	* verify login vs info in db
	* @param $identity the username to check against
	* @param $password the password to check against
	* @param $customer_id the customer id which this user belongs to
	*/
	function check_login($identity, $input_password, $customer_id)
	{
		$query = array("active" => true, "email" => $identity, "password" => $encrypted_password, 'customer' => $customer_id);
		$projection = array("password" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		/**
		 * verify the password with the hash
		 */  
		if($this->bcrypt->verify($input_password,$doc['password'])){
			return $doc['password'];
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
			"password" => $this->bcrypt->hash($password),
			"customer" => $customer_id,
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
	function change_password($new_password, $customer_id, $identity=NULL, $user_id=NULL){
		$query = NULL;
		if(isset($user_id)){
			$query = array("_id"=>new MongoId($user_id));
		}else{
			$query = array("email"=>$identity, "customer" => $customer_id);	
		}
		$update = array('$set' => array("password" => $this->bcrypt->hash($new_password)));
		return $this->mongo->db->users->update($query, $update);
	}
	
	function change_password_on_next_signin($customer_id=NULL, $identity=NULL, $user_id=NULL){
		$query = NULL;
		if(isset($user_id)){
			$query = array("_id"=>new MongoId($user_id));
		}else{
			$query = array("email"=>$identity, "customer" => $customer_id);	
		}
		$update = array('$set' => array("reset_on_signon" => true));
		return $this->mongo->db->users->update($query, $update);
	}
	
	/**
	*
	* @param $hash the hash stored in the cookie
	* @param $customer_id to check whether this is the hash stored for this customer
	*/
	function is_customer_valid_for_login_hash($hash, $customer_id)
	{
		$query = array("customer" => $customer_id, "password" => $hash, "active" => TRUE);
		$projection = array("customer" => 1);
		$doc = $this->mongo->db->users->findOne($query, $projection);
		// if the customer id is equal to the one passed in return true
		if($doc['customer'] == $customer_id){
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * get a user by user id
	 * @param $user_id - user id for the user you are trying to get
	 */
	function get_by_id($user_id){
		$query = array("_id" => new MongoId($user_id));
		$doc = $this->mongo->db->users->findOne($query);
		return $this->convert_mongo_result_to_object($doc);
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
		$query = array("customer" => $customer_id);
		$projection = array("active" => 1, "reset" => 1, "role" => 1, "email" => 1);
		return $this->convert_mongo_result_to_object($this->mongo->db->users->find($query, $projection)->sort(array("email"=>1)));
	}
	
	

	
	
	/**
	 * Activate or De-activate user
	 * @param identity - the email id of the user
	 * @param $customer_id - the customer id for which the user belongs to 
	 * @param $status - boolean value of status
	 */
	function set_user_status($status, $identity=NULL, $customer_id=NULL,$user_id=NULL){
		
		$query = NULL;
		if(isset($user_id))
			$query = array("_id"=> new MongoId($user_id));
		else{
			$query = array("email"=>$identity, "customer" => $customer_id);	
		}
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
	function set_user_role($role, $identity=null, $customer_id=null, $user_id=null){
		$query = NULL;	
		if(isset($user_id))
			$query = array("_id"=> new MongoId($user_id));
		else{
			$query = array("email"=>$identity, "customer" => $customer_id);	
		}
		$update = array('$set' => array("role" => $role));
		return $this->mongo->db->users->update($query, $update);
	}
	
	
	
}