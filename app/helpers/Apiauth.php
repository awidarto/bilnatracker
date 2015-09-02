<?php

class Apiauth {

    public static $location;
    public static $rack;

    public function __construct()
    {

    }

    public static function user($key){

        $user = User::where('sessionKey', $key)->first();
        if($user){
            return $user;
        }else{
            App::abort(403);
        }

    }

}
