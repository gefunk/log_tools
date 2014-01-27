<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminuserModel extends Base_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'dummy'));
		$this->load->library('bcrypt');
    }
	
	
	/**
	 * verify admin login info vs database table
	 */
	function check_admin_login($identity, $input_password)
	{
		$where_data = array("email" => $identity);
		$query = $this->db->get_where('admin_users', $where_data);
		if($query->num_rows() == 1){
			if($this->bcrypt->verify($input_password,$query->row()->password)){
				return $query->row()->password;
			}
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
	
	
	
}
