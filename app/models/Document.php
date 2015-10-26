<?php

use Jenssegers\Mongodb\Model as Eloquent;

class Document extends Eloquent {

    //protected $connection = 'mysql';
    //protected $table = 'devices';
    protected $collection = 'documents';

}