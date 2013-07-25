<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Admin_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//if user doesnt have the session redirect them out NOW!
		if(!$this->auth->isAdminLoggedIn()){
			// redirect them to login
			redirect("admin/login", "refresh");
		}	
	}
	
}