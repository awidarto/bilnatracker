<?php

class Wupload {

    public static $element_id = 'fileupload';
    public static $label = 'Upload Files';
    public static $title = 'Select File';
    public static $url = 'upload';
    public static $multi = true;
    public static $prefix = '';
    public static $singlefile = true;

    public static $ns = '';
    public static $parent_id = '';
    public static $parent_class = '';

    public static $editreplacer = '';
    public static $newreplacer = '';


    public function __construct()
    {

    }

    public function newreplacer($ar)
    {
        self::$newreplacer = $ar;
        return new self;
    }

    public function editreplacer($ar)
    {
        self::$editreplacer = $ar;
        return new self;
    }

    public static function id($id)
    {
        self::$element_id = $id;
        return new self;
    }

    public static function multi($flag = true)
    {
        self::$multi = $flag;
        return new self;
    }

    public static function singlefile($flag = true)
    {
        self::$singlefile = $flag;
        return new self;
    }

    public function prefix($prefix)
    {
        self::$prefix = $prefix;
        return new self;
    }

    public function url($url)
    {
        self::$url = $url;
        return new self;
    }

    public function title($title)
    {
        self::$title = $title;
        return new self;
    }

    public function label($label)
    {
        self::$label = $label;
        return new self;
    }

    public function parentclass($parent_class)
    {
        self::$parent_class = $parent_class;
        return new self;
    }

    public function parentid($parent_id)
    {
        self::$parent_id = $parent_id;
        return new self;
    }

    public function ns($ns)
    {
        self::$ns = $ns;
        return new self;
    }

    public function make($formdata = null)
    {
        return View::make('wupload.form')
            ->with('label',self::$label)
            ->with('title',self::$title)
            ->with('url',self::$url)
            ->with('multi',self::$multi)
            ->with('singlefile',self::$singlefile)
            ->with('editreplacer',self::$editreplacer)
            ->with('newreplacer',self::$newreplacer)
            ->with('prefix',self::$prefix)
            ->with('element_id', self::$element_id )
            ->with('parent_id',self::$parent_id)
            ->with('parent_class',self::$parent_class)
            ->with('ns',self::$ns)
            ->with('formdata',$formdata);
    }
}
