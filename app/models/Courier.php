<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Courier extends Eloquent {

    protected $collection = 'couriers';

/*
    protected $connection = 'mysql';
    protected $table = '';

    public function __construct(){

        $this->table = Config::get('jayon.incoming_delivery_table');

    }
*/

}