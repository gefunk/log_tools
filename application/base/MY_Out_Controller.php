<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Out_Controller extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		//if the user has the session, redirect them in NOW.
		if($this->auth->isLoggedIn()){
			redirect("main", "refresh");
		}
		
	}
	
}