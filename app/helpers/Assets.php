<?php

class Assets {

    public static $location;
    public static $rack;

    public function __construct()
    {

    }

    public static function getType($filter = null){
        if(is_null($filter)){
            $c = Assettype::get();
        }else{
            $c = Assettype::whereRaw($filter)->get();
        }

        self::$rack = $c;
        return new self;
    }

    public function TypeToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'Select Type');
        }else{
            $ret = array();
        }

        foreach (self::$rack as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function TypeToArray()
    {
        return self::$rack;
    }

    public static function getRack($filter = null){
        if(is_null($filter)){
            $c = Rack::get();
        }else{
            $c = Rack::whereRaw($filter)->get();
        }

        self::$rack = $c;
        return new self;
    }

    public function RackToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'Select Rack');
        }else{
            $ret = array();
        }

        foreach (self::$rack as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function RackToArray()
    {
        return self::$rack;
    }

    public static function getRackDetail($id){

        $c = Rack::find($id);

        return $c;
    }

    public static function getLocationDetail($id){

        $c = Assetlocation::find($id);

        return $c;
    }

    public static function getLocation($filter = null){
        if(is_null($filter)){
            $c = Assetlocation::get();
        }else{
            $c = Assetlocation::whereRaw($filter)->get();
        }

        self::$location = $c;
        return new self;
    }

    public function LocationToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'Select Location');
        }else{
            $ret = array();
        }

        foreach (self::$location as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function LocationToArray()
    {
        return self::$location;
    }

    public static function yearSelection(){
        $ya = array();
        for( $i = 1970; $i < 2050; $i++ ){
            $ya[$i] = $i;
        }
        return $ya;
    }

    public static function GetBatchId($SKU, $year, $month){

        $seq = DB::collection('batchnumbers')->raw();

        $new_id = $seq->findAndModify(
                array(
                    'SKU'=>$SKU,
                    'year'=>$year,
                    'month'=>$month
                    ),
                array('$inc'=>array('sequence'=>1)),
                null,
                array(
                    'new' => true,
                    'upsert'=>true
                )
            );


        $batchid = $year.$month.str_pad($new_id['sequence'], 4, '0', STR_PAD_LEFT);

        return $batchid;

    }

    public static function ExtractProductCategory($selection = true)
    {
        $category = Product::distinct('category')->get()->toArray();
        if($selection){
            $cats = array(''=>'All');
        }else{
            $cats = array();
        }

        //print_r($category);
        foreach($category as $cat){
            $cats[$cat[0]] = $cat[0];
        }

        return $cats;
    }

    public static function createApprovalRequest($status, $assettype, $assetid, $requestedto = 'any' ){
        //status : new or update
        //assettype : any which one of registered asset Type
        $data = array(
            'requestDate' => new MongoDate(),
            'actor'=> Auth::user()->_id,
            'actorName'=> Auth::user()->fullname,
            'status'=>$status,
            'assetType'=>$assettype,
            'assetId'=>$assetid,
            'requestedTo'=>$requestedto,
            'approvalStatus'=>'pending'
        );
        return Approval::insertGetId($data);
    }

    public static function getApprovalStatus($id)
    {
        $apv = Approval::find($id);

        return (isset($apv->approvalStatus))?$apv->approvalStatus:'';
    }




}
