<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Transaction extends Eloquent {

    protected $collection = 'transactions';
    protected $fillable = array('*');
}