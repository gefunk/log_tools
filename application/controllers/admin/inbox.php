<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends MY_Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
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
	
	public function document($doc_id){
		
		// get the document from the db
		$document = $this->attachmentmodel->get_document($doc_id);
		
		$data['doc_id'] = $doc_id;
		$data['total_pages'] = count($document->pages);
		
		
		$header_data['title'] = "View Document";
		$header_data['page_css'] = array("app/documents/thumbnail.css", "app/documents/overlay.css");
		// pass javascript to footer
		$footer_data["scripts"] = array("admin/contract/document/docreader.js", "admin/inbox/document.js");
		
		$this->load->view('admin/header', $header_data);
		$this->load->view('admin/inbox/document', $data);
		$this->load->view('admin/footer', $footer_data);
		
	}
	

	
}

