<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_In_Controller {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
	}

	public function index(){
		$this->auth->logout();
		$this->session->set_flashdata('messages', "<p>You have been logged out, Thanks for using Amfitir</p>");
		$this->load->view("signin");
	}
	
} // end logout
