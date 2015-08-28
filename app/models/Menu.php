<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Menu extends Eloquent {

    protected $collection = 'menus';
    protected $fillable = array('*');

}