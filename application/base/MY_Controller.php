<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		
		/**
		* we do this to identify customers
		* by subdomain
		*/
		// only do the subdomain checking for prod and qa
		if (defined('ENVIRONMENT') && (ENVIRONMENT == 'production' || ENVIRONMENT == 'testing')){
			// check for subdomain
			$subdomain = array_shift(explode(".",$_SERVER['HTTP_HOST'])); 
			// check subdomain is not already set to the same, so we don't constantly keep hitting it
			if($this->session->userdata("subdomain") != $subdomain){
				if($subdomain && $subdomain != '' && $subdomain != 'www'){			
					$this->load->model("customermodel");
					$customer = $this->customermodel->get_customer_by_domain($subdomain);
					if($customer != null){
						// forward to customer login page
						$customer_data = array(
							"customer_id" => $customer["id"],
							"customer_name" => $customer["name"]
						);
						// set customer session data
						$this->session->set_userdata($customer_data);
					}
				}
				// set the subdomain so we don't check it all the time
				$this->session->set_userdata("subdomain", $subdomain);
			}
		}
			
		
		
		
		
		
	}
	
}