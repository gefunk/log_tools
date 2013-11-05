<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_In_Controller extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		//if user doesnt have the session redirect them out NOW!
		if(!$this->auth->isLoggedIn()){
			// redirect them to the login controller
			redirect("login", "refresh");
		}	
	}
	
}