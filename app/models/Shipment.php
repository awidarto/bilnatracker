<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Shipment extends Eloquent {

    protected $collection = 'shipments';

/*
    protected $connection = 'mysql';
    protected $table = '';

    public function __construct(){

        $this->table = Config::get('jayon.incoming_delivery_table');

    }
*/

}