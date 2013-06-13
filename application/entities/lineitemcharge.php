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
    public static function initLineItemCharge($id, $application, $charge_code, $effective, $expires, $currency, $amount, $deleted) {
        $obj = new LineItemCharge();
        $obj -> id = $id;
        $obj -> application = $application;
        $obj -> charge_code = $charge_code;
        $obj -> effective = $effective;
        $obj -> expires = $expires;
        $obj -> currency = $currency;
        $obj -> amount = $amount;
        $obj -> deleted = $deleted;
        return $obj;
    }

}
