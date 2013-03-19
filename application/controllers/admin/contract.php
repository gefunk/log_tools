<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	public function add()
	{
		$this->load->helper('form');
		$this->load->model('customermodel');
		$this->load->model('carriermodel');
		
		$data['scripts'] = array('admin/contract/add.js');
		$data['customers'] = $this->customermodel->get_customers();
		$data['carriers'] = $this->carriermodel->get_carriers();

		$this->load->view('admin/header');
		$this->load->view('admin/contract/add', $data);
		$this->load->view('admin/footer');		
	}
	

}

