<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @Entity
 * @Table(name="line_item_containers")
 */
class LineContainer {

    public $id;
    public $line_item_id;
    public $container_type;
    public $price;
    public $deleted;

    /* http://stackoverflow.com/questions/2169448/why-cant-i-overload-constructors-in-php
     * factory  method to initialize new Line Item with params
     */
    public static function initLineContainer($line_item_id, $container_type, $price) {
        $obj = new LineContainer();
        $obj -> line_item_id = $line_item_id;
        $obj -> container_type = $container_type;
        $obj -> price = $price;
        return $obj;
    }

    /**
     * when retrieving from the db will have id and deleted values available
     */
    public static function initLineContainerFromDB($id, $line_item_id, $container_type, $price, $deleted) {
        $obj = LineContainer::initLineContainer($line_item_id, $container_type, $price);
        $obj -> id = $id;
        $obj -> deleted = $deleted;
        return $obj;
    }
    

}
