<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @Entity
 * @Table(name="line_items")
 * Representation of Line Item table
 */
class LineItemEntity {

    public $id;
    public $origin;
    public $origin_type;
    public $destination;
    public $destination_type;
    public $cargo;
    public $effective;
    public $expires;
    public $currency;
    public $service;
    public $deleted;
    /**
     * container and their prices
     */
    public $containers;
    public $surcharges;
    public $tariffs;
    public $contract;
    
    
    
    public static function initLineItem($origin, $origin_type, $destination, $destination_type, $cargo, $effective, $expires, $currency, $service, $contract) {
        $obj = new LineItemEntity();
        $obj -> origin = $origin;
        $obj -> origin_type = $origin_type;
        $obj -> destination = $destination;
        $obj -> destination_type = $destination_type;
        $obj -> cargo = $cargo;
        $obj -> effective = $effective;
        $obj -> expires = $expires;
        $obj -> currency = $currency;
        $obj -> service = $service;
        $obj -> contract = $contract;
        return $obj;
    }

    /* http://stackoverflow.com/questions/2169448/why-cant-i-overload-constructors-in-php
     * factory  method to initialize new Line Item with params
     */
    public static function initLineItemFromDb($id, $origin, $origin_type, $destination, $destination_type, $cargo, $effective, $expires, $currency, $service, $deleted, $contract) {
        $obj = new LineItemEntity();
        $obj -> id = $id;
        $obj -> origin = $origin;
        $obj -> origin_type = $origin_type;
        $obj -> destination = $destination;
        $obj -> destination_type = $destination_type;
        $obj -> cargo = $cargo;
        $obj -> effective = $effective;
        $obj -> expires = $expires;
        $obj -> currency = $currency;
        $obj -> service = $service;
        $obj -> deleted = $deleted;
        $obj -> contract = $contract;
        return $obj;
    }

}
