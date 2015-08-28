<?php
use Jenssegers\Mongodb\Model as Eloquent;

class MessageQueue extends Eloquent {

    protected $collection = 'mq';

}