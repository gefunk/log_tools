<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @Entity
 * @Table(name="line_items")
 * Representation of Line Item table
 */
class LineItemCharge {

    public $id;
	public $line_item_id;
    public $application;
    public $charge_code;
    public $effective;
    public $expires;
    public $currency;
    public $amount;
    public $deleted;

    /* http://stackoverflow.com/questions/2169448/why-cant-i-overload-constructors-in-php
     * factory  method to initialize new Line Item with params
     */
    public static function initLineItemCharge($line_item_id, $application, $charge_code, $effective, $expires, $currency, $amount) {
        $obj = new LineItemCharge();
		$obj->line_item_id = $line_item_id;
        $obj -> application = $application;
        $obj -> charge_code = $charge_code;
        $obj -> effective = $effective;
        $obj -> expires = $expires;
        $obj -> currency = $currency;
        $obj -> amount = $amount;
        return $obj;
    }
	
	public static function initLineItemFromDb($id, $line_item_id, $application, $charge_code, $effective, $expires, $currency, $amount, $deleted){
		$obj = LineItemCharge::initLineItemCharge($line_item_id, $application, $charge_code, $effective, $expires, $currency, $amount);
		$obj -> id = $id;
		$obj -> deleted = $deleted;
		return $obj;
	}

}
