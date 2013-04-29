<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LeadsModel extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function save_contact($name,$email,$phone,$message,$ip)
	{
		$data = array("name" => $name, "email" => $email, "phone" => $phone, "message" => $message, "ip" => $ip);
		$this->db->insert("lead_contacts", $data);
	}
	
	function save_newsletter($email)
	{
		$data = array("email" => $email);
		$this->db->insert("lead_newsletter", $data);
	}

} // end class