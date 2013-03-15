<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachments extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('admin/customermodel');
	}
	

}