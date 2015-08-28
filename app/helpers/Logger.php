<?php

class Logger{
    public function __construct(){

    }

    public static function access()
    {
        $access = new Accesslog();
        $httpobj = array_merge($_SERVER, $_GET );

        foreach ($httpobj as $key => $value) {
            $access->{$key} = $value;
        }

        $access->save();
    }
}