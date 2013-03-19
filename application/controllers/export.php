<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->model('exportcodes');
	}

	public function index()
	{
		$this->load->view('export/codes');
	}
	
	public function search($query)
	{	
		//$this->output->enable_profiler(TRUE);
		if(isset($query)){
			$terms = explode(" ",rawurldecode($query));
			echo json_encode($this->exportcodes->search($terms));
		}
	}
	
	public function test_explode($desc){
		if(isset($desc)){
			$terms = explode(" ",rawurldecode($desc));
			echo $this->exportcodes->search($terms);
		}
			
	}
	
	public function another_function(){
		echo "Hello";
	}
}

