<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Mongo extends Mongo
{
    var $db;

    function CI_Mongo()
    {   
        // Fetch CodeIgniter instance
        $ci =& get_instance();
        // Load Mongo configuration file
        $ci->load->config('mongodb');

        // Fetch Mongo server and database configuration
        $server = $ci->config->item('mongo_server');
		$username = $ci->config->item('mongo_username');
		$password = $ci->config->item('mongo_password');
        $dbname = $ci->config->item('mongo_dbname');

        // Initialise Mongo
        if ($server)
        {
        	if($username && $password) {
            	parent::__construct("mongodb://$username:$password@$server/$dbname");
			} else {
				parent::__construct("mongodb://$server/$dbname");
			}
        }
        else
        {
            parent::__construct();
        }
        $this->db = $this->$dbname;
    }
}