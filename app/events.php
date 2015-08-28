<?php


Event::listen('log.a',function($class, $method, $actor, $result){
    $data = array(
            'timestamp'=>new MongoDate(),
            'class'=>$class,
            'method'=>$method,
            'actor'=>$actor,
            'result'=>$result
        );

    Activelog::insert($data);

    return true;
});

Event::listen('log.api',function($class, $method, $actor, $result){
    $data = array(
            'timestamp'=>new MongoDate(),
            'class'=>$class,
            'method'=>$method,
            'actor'=>$actor,
            'result'=>$result
        );

    Apilog::insert($data);

    return true;
});

Event::listen('cleanup',function(){

    $last = new MongoDate( time() - Config::get('ia.reserveTimeOut') );

    $props = Property::where('locked','=',1)->where('reservedAt','<', $last )->get();

    foreach($props as $property){
            $property->propertyStatus = $property->propertyLastStatus;
            $property->reservedBy = '';
            $property->reservedAt = '';
            $property->lock = 0;
            $property->save();
    }

    return true;
});