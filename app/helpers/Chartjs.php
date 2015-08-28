<?php

class Chartjs{

    public static $data = array();
    public static $label = array();
    public static $type = 'bar';
    public static $chartid = 'chart01';
    public static $options = array('responsive'=>'true');

    public function __construct()
    {

    }

    public function addDataArray($dataarray){
        self::$data[] = $dataarray;
        return new self;
    }

    public function setLabel($label){
        self::$label = $label;
        return new self;
    }

    public function setType($type){
        self::$type = $type;
        return new self;
    }

    public function id($id){
        self::$chartid = $id;
        return new self;
    }

    public function setOptions($options){
        self::$options = $options;
        return new self;
    }

    public function make($formdata = null)
    {
        return View::make('chartjs.chart')
            ->with('chartid',self::$chartid)
            ->with('options',self::$options)
            ->with('label',self::$label)
            ->with('data',self::$data)
            ->with('type',self::$type);
    }



}