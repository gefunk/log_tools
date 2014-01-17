<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("attachments/attachmentmodel");
	}
	
	public function index(){
		$header_data['title'] = "Inbox - Files";
		$footer_data["scripts"] = array( "admin/inbox/upload.js","admin/inbox/view.js");
		$data['docs'] = $this->attachmentmodel->get_inbox_docs();
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/inbox/view', $data);
		$this->load->view('admin/footer', $footer_data);
	}
	
	
	
	
}

