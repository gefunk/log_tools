<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracts {
	
	/*
	* Standard way to get a url for a contract asset
	* @param customer_id the customer we are uploading the contract for
	* @param the contract id of the contract we are looking for
	*/
	function get_remote_url_for_contract($customer_id, $contract_number)
	{
		return "customer-".$customer_id."/"."contract-".$contract_number."/";
	}

}