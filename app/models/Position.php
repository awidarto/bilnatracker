<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Position extends Eloquent {

    protected $collection = 'dispositions';

/*
    protected $connection = 'mysql';
    protected $table = '';

    public function __construct(){

        $this->table = Config::get('jayon.incoming_delivery_table');

    }
*/

}