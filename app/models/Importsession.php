<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Importsession extends Eloquent {

    protected $collection = 'importsessions';
    protected $fillable = array('*');

}