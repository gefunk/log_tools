<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error fade in"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
		
	}

	public function add()
	{
		$this->form_validation->set_rules('contract_number', 'Contract Number', 'required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');

		// validate
		if ($this->form_validation->run() == FALSE){
			$data['scripts'] = array('admin/contract/add.js');
			
			$this->load->model('customermodel');
			$this->load->model('carriermodel');
			
			$data['customers'] = $this->customermodel->get_customers();
			$data['carriers'] = $this->carriermodel->get_carriers();
			
			$this->load->view('admin/header');
			$this->load->view('admin/contract/add', $data);
			$this->load->view('admin/footer');		
		}else{
			echo "Success";
		}
		
	}
	

}

