<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class DeliveryapiController extends \BaseController {

    public $controller_name = '';

    public $model;

    public $sql_connection;

    public $sql_table_name;

    public function  __construct()
    {
        //$this->model = "Member";
        $this->controller_name = strtolower( str_replace('Controller', '', get_class()) );

        //$this->sql_table_name =  \Config::get('jayon.incoming_delivery_table') ;
        //$this->sql_connection = 'mysql';

        //$this->model = \DB::connection($this->sql_connection)->table($this->sql_table_name);

        $this->model = new \Shipment();

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $key = Input::get('key');
        $deliverydate = Input::get('date');

        /*
                    ->join('members as m','d.merchant_id=m.id','left')
                    ->where('assignment_date',$indate)
                    ->where('device_id',$dev->id)
                    ->and_()
                    ->group_start()
                        ->where('status',$this->config->item('trans_status_admin_courierassigned'))
                        ->or_()
                        ->group_start()
                            ->where('status',$this->config->item('trans_status_new'))
                            ->where('pending_count >', 0)
                        ->group_end()
                    ->group_end()


        */

        $dev = \Device::where('key','=',$key)->first();

        //print_r($dev);

        $txtab = \Config::get('jayon.incoming_delivery_table');

        /*
        $orders = $this->model->where(function($qz) use($key, $deliverydate){
                    $qz->where('pick_up_date', '=', new \MongoDate( strtotime($deliverydate) ) )
                        ->where('device_key', '=', $key);
                })
                ->where(function($qw){

                    $qw->where('bucket','=',\Config::get('jayon.bucket_tracker'))
                        ->orWhere(function($qx){
                                $qx->where('logistic_type','=','internal')
                                    ->where(function($qz){
                                        $qz->where('status','=', \Config::get('jayon.trans_status_admin_courierassigned') )
                                            ->orWhere('status','=', \Config::get('jayon.trans_status_mobile_pickedup') )
                                            ->orWhere('status','=', \Config::get('jayon.trans_status_mobile_enroute') )
                                            ->orWhere(function($qx){
                                                $qx->where('status', \Config::get('jayon.trans_status_new'))
                                                    ->where(\Config::get('jayon.incoming_delivery_table').'.pending_count', '>', 0);
                                            });
                                    });

                        });

                })

                ->orderBy('pick_up_date')
                ->get();
        */

        $orders = $this->model
                    //->where('pick_up_date', '=', new \MongoDate( strtotime($deliverydate) ) )
                    //->where('logistic_type','=','internal')
                    ->where('device_key', '=', $key)
                    /*
                    ->where(function($qz) use($key, $deliverydate){

                        $qz->where('status','=', \Config::get('jayon.trans_status_admin_courierassigned') )
                            ->orWhere('status','=', \Config::get('jayon.trans_status_mobile_pickedup') )
                            ->orWhere('status','=', \Config::get('jayon.trans_status_mobile_enroute') )
                            ->orWhere(function($qx){
                                $qx->where('status', \Config::get('jayon.trans_status_new'))
                                    ->where('pending_count', '>', 0);
                            });

                    })
                    */
                    ->orderBy('pick_up_date')
                    ->get();


        $orders = $orders->toArray();

        //print_r($orders->toArray());

        for($n = 0; $n < count($orders);$n++){
            $or = new \stdClass();
            foreach( $orders[$n] as $k=>$v ){
                if($k != '_id' && $k != 'id'){
                    $nk = $this->underscoreToCamelCase($k);
                }else{
                    $nk = $k;
                }

                $or->$nk = (is_null($v))?'':$v;
            }

            //print_r($or);
            $or->extId = $or->_id;
            unset($or->_id);

            $or->deliveryType = (isset($or->cod) && $or->cod > 0)?'COD':'DO';

            $or->boxList = $this->boxList('delivery_id',$or->deliveryId);
            $or->boxCount = $or->numberOfPackage;

            $or->pickUpDate = date('Y-m-d H:i:s', $or->pickUpDate->sec);
            $or->createdDate = date('Y-m-d H:i:s', $or->createdDate->sec);
            $or->lastUpdate = date('Y-m-d H:i:s', $or->lastUpdate->sec);

            $orders[$n] = $or;
        }


        $actor = $key;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'logged out'));

        return $orders;
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $in = Input::get();
        if(isset($in['key']) && $in['key'] != ''){
            print $in['key'];
        }else{
            print 'no key';
        }
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function underscoreToCamelCase( $string, $first_char_caps = false)
    {

        $strings = explode('_', $string);

        if(count($strings) > 1){
            for($i = 0; $i < count($strings);$i++){
                if($i == 0){
                    if($first_char_caps == true){
                        $strings[$i] = ucwords($strings[$i]);
                    }
                }else{
                    $strings[$i] = ucwords($strings[$i]);
                }
            }

            return implode('', $strings);
        }else{
            return $string;
        }

    }

    public function boxList($field,$val){

        $boxes = \Box::where($field,'=',$val)->get();

        $bx = array();

        foreach($boxes as $b){
            $bx[] = $b->box_id;
        }

        if(count($bx) > 0){
            return implode(',',$bx);
        }else{
            return '1';
        }

    }

}
