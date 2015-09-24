<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Logistic extends Eloquent {

    protected $collection = 'logistics';

/*
    protected $connection = 'mysql';
    protected $table = '';

    public function __construct(){

        $this->table = Config::get('jayon.incoming_delivery_table');

    }
*/

}