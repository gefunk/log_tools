<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	*	Default endpoint to landing page
	*/
	public function index($showalert="")
	{
		
		/*
		* check if a customer is access via their subdomain
		*/
		$subdomain = array_shift(explode(".",$_SERVER['HTTP_HOST'])); 
		if($subdomain && $subdomain != '' && $subdomain != 'www'){
			// only load model when needed
			$this->load->model("customermodel");
			$customer = $this->customermodel->get_customer_by_domain($subdomain);
			if($customer != null){
				// forward to customer login page
				$customer_data = array(
					"customer_id" => $customer["id"],
					"customer_name" => $customer["name"]
				);
				$this->session->set_userdata($customer_data);
				$data["customer_name"] = $customer["name"];
				$this->load->view("signin", $data);
			}else{
				// regular flow, no customer id found
				if(isset($showalert))
					$data['alert'] = $showalert;
				$this->load->view('landing/landing', $data);
			}
		}else{
			// regular flow no customer id found
			if(isset($showalert))
				$data['alert'] = $showalert;
			$this->load->view('landing/landing', $data);
		}
		
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