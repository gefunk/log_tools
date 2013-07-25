<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Out_Controller extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		//if the user has the session, redirect them in NOW.
		if($this->auth->isLoggedIn()){
			if (defined('ENVIRONMENT') && ENVIRONMENT == 'development'){
				// in dev it has to land on the main page
				$redirect = "main";
			}else{
				// in production redirect them to the root
				$redirect = "";
			}	
			redirect($redirect, "refresh");
		}
		
	}
	
}