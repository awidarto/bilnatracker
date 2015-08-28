<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Testmodel extends Eloquent {


    protected $collection = 'testmodel';

    protected $fillable = array('*');
}