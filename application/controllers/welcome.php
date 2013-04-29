<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	*	Default endpoint to landing page
	*/
	public function index($showalert="")
	{
		if(isset($showalert))
			$data['alert'] = $showalert;
		$this->load->view('landing/landing', $data);
	}
	
	public function contact()
	{
		$this->load->view('landing/contact');
	}
	
	public function save_contact()
	{
		$this->load->model("leadsmodel");
		$name = $this->input->post("name");
		$email = $this->input->post("email");
		$phone = $this->input->post("phone");
		$message = $this->input->post("message");		
		$ipaddr = $this->input->ip_address();
		$this->leadsmodel->save_contact($name,$email,$phone,$message,$ipaddr);
		redirect('welcome/index/contact', 'location');
	}
	
	public function save_newsletter()
	{
		$this->load->model("leadsmodel");
		$email = $this->input->post("email");
		$this->leadsmodel->save_newsletter($email);
		redirect('welcome/index/newsletter', 'location');
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */