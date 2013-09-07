<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* library to handle authentication
*/

class Amfitirlog
{
    
    public function __construct()
    {
        // log model
        $this->load->model('logmodel');
    }
    
    /**
     * Log a new contract line item was added
     * @param $line_item_id the id of the item that was created
	 * @param $user the user who made the change
	 * @param $conract_id of the contract that was affected
	 * @param $rate_search_ids the rate search elements which were added as a result
     */
    public function log_new_contract_line_item($line_item_id, $user, $contract_id, $rate_search_ids)
    {
        $this->logmodel->new_contract_line_item($user, $contract_id, $line_item_id, $rate_search_ids);   
    }
    
    /**
     * Log that a line item was deleted
     * @param $line_item_id the line item that was deleted
     */
    public function log_delete_contract_line_item($line_item_id)
    {
        // TODO: need to get user id from session, when Admin session is implemented
        $user = 1;
        $this->logmodel->insert_log_contract($user, $line_item_id, NULL, LOG_EVENT_DELETE, LOG_OBJECT_LINE_ITEM);   
    }


    /**
     * Log that a line item was updated
     * @param $line_item was updated
     */
    public function log_update_contract_line_item($line_item_id, $old_line_item)
    {
        // TODO: need to get user id from session, when Admin session is implemented
        $user = 1;
        $this->logmodel->insert_log_contract($user, $line_item_id, $old_line_item, LOG_EVENT_UPDATE, LOG_OBJECT_LINE_ITEM);   
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
}