<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class FlapiController extends \BaseController {

        private $fl_status = array(
            'AGREED DELIVERY',
            'ARRIVED AT SORT FACILITY',
            'BAD ADDRESS'=>array(
                                'UNKNOWN COMPANY',
                                'INCORRECT CITY',
                                'INCORRECT STREET',
                                'UNKNOWN PERSON',
                                'INCORRECT BUILDING NUMBER'
                            ),
            'CLOSED ON ARRIVAL'=>array(
                                'AFTER BUSINESS HOURS',
                                'CONSIGNEE HOLIDAY',
                                'PRE BUSINESS HOURS',
                                'LUNCH BREAK',
                                'NOT OPEN'
                            ),
            'CONSIGNEE COLLECTION',
            'CUSTOMER MOVED'=>array(
                            'CONSIGNEE MOVED',
                            'COMPANY MOVED',
                            'GUESS CHECKED OUT'
                        ),
            'DEPARTED FROM SORT FACILITY',
            'ON FORWARDING AREA  AGENT',
            'NOT DELIVERED'=>array(
                                'ACCESS FLOODED',
                                'TRAFFIC JAM',
                                'STRIKE',
                                'ROUTE MISSORT',
                                'VEHICLE PROBLEM',
                                'OUT OF AREA',
                                'FORCE MAJEURE'
                            ),
            'NOT HOME',
            'DELIVERED',
            'PICK UP'=>array(
                            'SHIPMENT PICKED UP',
                            'NOT READY',
                            'CLOSED',
                            'PICKED UP BY OTHER COURIER'
                        ),
            'REFUSE'=>array(
                        'DELIVERY DAMAGE',
                        'DELIVERY CANCEL'
                    ),
            'RETURN'=>array(
                        'SHIPPER REQUEST',
                        'DANGEROUS GOODS IDENTIFIED',
                        'RETURN TO CDC',
                        'CONSIGNEE REQUEST'
                        ),
            'SHIPMENT ACCEPTANCE'=>array(
                                    'FACILITY POINT',
                                    'AGENT POINT'
                                    ),
            'SCHEDULLED FOR MOVEMENT',
            'WITH DELIVERY COURIER',
            'WAITING INFO'
        );


/*
{
    "hawb":"Your Hawb number",
    "consignee": {
        "cn_name": "Recipient name",
        "address": "Recipient address",
        "distric": "Recipient distric",
        "city": "Recipient city",
        "province": "Recipient province",
        "country": "Recipient country",
        "phone": "Recipient phone"
    },
    "order": {
        "orderid": "Order number",
        "volumetric": "20x45x23",
        "actweight":"0",
        "pieces": "1",
        "items": [
                    {
                        "itemname": "the name of items1"
        },
        {
            "itemname": "the name of items2"
                    }
              ],
        "goodsval": "20000",
        "assoption": "NO",
        "cod":"0",
        "pubrate":"0",
        "pickupdate":"2014-01-01",
        "service":"REG"
    },
    "shipper": {
        "merchant": "merchant name",
        "merchant_address": "Merchant or shipper address",
        "merchant_distric": "Merchant or shipper distric",
        "merchant_city": "Merchant or shipper city",
        "merchant_province": "Merchant or shipper province",
        "merchant_country": "Merchant or shipper country",
        "merchant_phone": "80001000",
        "merchant_contact": "Merchant contact name"
    },
    "remark":"Shipment remark or addtional notes"
}
*/


    public $controller_name = '';

    public $model;

    public $sql_connection;

    public $sql_table_name;

    public $name = "FL Daemon";

    public function  __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

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
        date_default_timezone_set('Asia/Jakarta');

        $key = Input::get('key');
        $order_id = Input::get('orderid');
        $ff_id = Input::get('ffid');

        $is_dev = Input::get('dev');

        if(is_null($is_dev) || $is_dev == ''){
            $is_dev = 0;
        }

        if( is_null($key) || $key == ''){
            $actor = 'no id : no name';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty key'));

            return \Response::json(array('status'=>'ERR:EMPTYKEY', 'timestamp'=>time(), 'message'=>'Empty Key' ));
        }

        $logistic = \Logistic::where('api_key','=',$key)->first();


        $logistic_id = $logistic->consignee_olshop_cust;

        $orders = \Shipment::where('awb','!=','')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->where(function($q){
                            $q->where('status','=', \Config::get('jayon.trans_status_admin_dated') )
                                ->orWhere('status','=', \Config::get('jayon.trans_status_confirmed'));
                        })
                        ->where(function($q){
                            $q->where('uploaded','!=',1)
                                ->orWhere('uploaded','exists',false);
                        })
                        ->get();

        $orderres = clone $orders;

        $orders = $orders->toArray();

        $orderlist = array();

        for($n = 0; $n < count($orders);$n++){

            $o = $orders[$n];

            $entry = array();

            $entry['hawb'] = $o['awb'];

            $entry['remark'] = '-';

            $consignee = array(
                'cn_name'=> $o['consignee_olshop_name'],
                'address'=> str_replace(array("\r","\n"), ' ', $o['consignee_olshop_addr'] ),
                'distric'=> $o['district'] ,
                'city'=> $o['consignee_olshop_city'] ,
                'province'=> $o['consignee_olshop_region'] ,
                'country'=> $o['consignee_olshop_region'] ,
                'phone'=> $o['consignee_olshop_phone']
            );

            $insurance = 'NO';

            if(isset($o['consignee_olshop_inst_amt'])){
                if( $o['consignee_olshop_inst_amt'] == '' || is_null($o['consignee_olshop_inst_amt']) ){
                    $insurance = 'NO';
                }else{
                    $insurance = 'YES';
                }
            }

            //if($o['pick_up_date'] instanceOf MongoDate){
                $pickupdate = date('Y-m-d',$o['pick_up_date']->sec);
            //}else{
            //    $pickupdate = $o['pick_up_date'];
            //}

            $o['cod'] = (isset($o['cod']))?$o['cod']:0;

            $order = array(
                'orderid'=> $o['no_sales_order'].'-'.$o['fulfillment_code'],
                //'volumetric'=> $o['w_v'],
                'actweight'=> '0',
                'pieces'=> $o['number_of_package'],
                'items'=> array(
                        'itemname'=> $o['consignee_olshop_desc']
                    ),
                'goodsval'=> $o['cod'],
                'assoption'=> $insurance,
                'cod'=>($o['cod'] == 0)?'0':'1',
                'pubrate'=>'0',
                'pickupdate'=> $pickupdate,
                'service'=>$o['consignee_olshop_service']

            );

            $shipper = array(
                'merchant'=> 'Bilna.com',
                'merchant_address'=> 'Kawasan Pergudangan PT. WIDYA SAKTI KUSUMA Jl. Raya Bekasi KM 28 ( Jl. Wahab Affan ) Pondok Ungu, Medan Satria, Bekasi 17132',
                'merchant_distric'=> 'Medan Satria',
                'merchant_city'=> 'Bekasi',
                'merchant_province'=> 'Jawa Barat',
                'merchant_country'=> 'Indonesia',
                'merchant_phone'=> '02129022132',
                'merchant_contact'=> 'Bilna CS'
            );

            $entry['consignee'] = $consignee;

            $entry['order'] = $order;

            $entry['shipper'] = $shipper;

            $orderlist[] = $entry;
        }

        if($is_dev != 1){
            foreach($orderres as $ord){
                $ord->uploaded = 1;
                $ord->save();
            }
        }

        $reslog = $orderlist;
        $reslog['timestamp'] = new \MongoDate();
        $reslog['consignee_logistic_id'] = $logistic->logistic_code;
        $reslog['consignee_olshop_cust'] = $logistic->consignee_olshop_cust;
        \Threepluploadlog::insert($reslog);

        $actor = $key;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'FL PULL DATA'));

        return $orderlist;
        //
    }

    public function postStatus()
    {

        $delivery_trigger = 'DELIVERED';
        $returned_trigger = $this->fl_status['RETURN'];
        $undelivered_trigger = $this->fl_status['NOT DELIVERED'];


        $key = \Input::get('key');

        //$user = \Apiauth::user($key);

        if( is_null($key) || $key == ''){
            $actor = 'no id : no name';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty key'));

            return \Response::json(array('status'=>'ERR:EMPTYKEY', 'timestamp'=>time(), 'message'=>'Empty Key' ));
        }

        $logistic = \Logistic::where('api_key','=',$key)->first();

        $json = \Input::json();


        $reslog = \Input::all();

        $slog = $json;

        $reslog['timestamp'] = new \MongoDate();
        $reslog['consignee_logistic_id'] = $logistic->logistic_code;
        $reslog['consignee_olshop_cust'] = $logistic->consignee_olshop_cust;
        \Threeplstatuslog::insert($reslog);


        $this->saveStatus($slog,$logistic->consignee_olshop_cust,$logistic->logistic_code);

        $batch = \Input::get('batch');

        $awbarray = array();
        $awbs = array();

        $statusarray = array();

        $inawbstatus = array();


        foreach($json as $j){
            $tawb = trim($j['awb']);
            $awbarray[] = $tawb;
            $awbs[$tawb] = $j;
            $inawbstatus[$tawb] = 'NOT FOUND';
        }
        /*
        $reslog = $json;
        $reslog['consignee_logistic_id'] = $logistic->logistic_code;
        $reslog['consignee_olshop_cust'] = $logistic->consignee_olshop_cust;
        \Threeplstatuslog::insert($reslog);
        */

        $result = array();

        $orderlist = \Shipment::whereIn('awb', $awbarray)->get();


        if($orderlist){

            foreach($orderlist as $order){

                $pre = clone $order;

                $lst = trim($awbs[$order->awb]['last_status']);

                if( $lst == $delivery_trigger){
                    $order->status = \Config::get('jayon.trans_status_mobile_delivered');
                    $order->position = 'CUSTOMER';
                }

                if( in_array( $lst , $returned_trigger) || $lst == 'RETURN' ){
                    $order->status = \Config::get('jayon.trans_status_mobile_return');
                }

                if( in_array( $lst , $undelivered_trigger) || $lst == 'NOT DELIVERED' ){
                    $order->status = \Config::get('jayon.trans_status_mobile_return');
                }

                $lts = (isset($awbs[$order->awb]['timestamp']) && $awbs[$order->awb]['timestamp'] != '' )? $awbs[$order->awb]['timestamp'] :$awbs[$order->awb]['delivered_date'].' '.$awbs[$order->awb]['delivered_time'];

                $order->logistic_status = $awbs[$order->awb]['last_status'];
                $order->logistic_status_ts = $lts;
                $order->logistic_raw_status = $awbs[$order->awb];

                $saved = $order->save();

                if($saved){
                    $inawbstatus[$order->awb] = 'STATUS UPDATED';
                }

                $ts = new \MongoDate();

                $hdata = array();
                $hdata['historyTimestamp'] = $ts;
                $hdata['historyAction'] = 'api_shipment_change_status';
                $hdata['historySequence'] = 1;
                $hdata['historyObjectType'] = 'shipment';
                $hdata['historyObject'] = $order->toArray();
                $hdata['actor'] = $this->name;
                $hdata['actor_id'] = '';

                //\History::insert($hdata);

                $sdata = array();
                $sdata['timestamp'] = $ts;
                $sdata['action'] = 'api_shipment_change_status';
                $sdata['reason'] = 'api_update';
                $sdata['objectType'] = 'shipment';
                $sdata['object'] = $order;
                $sdata['preObject'] = $pre;
                $sdata['actor'] = $this->name;
                $sdata['actor_id'] = '';
                \Shipmentlog::insert($sdata);

            }

            $actor = 'FL : STATUS PUSH';

            foreach($inawbstatus as $k=>$v){
                $statusarray[] = array('AWB'=> $k, 'status'=> $v);
            }

            if(count($statusarray) > 0){
                \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'FL status update'));
                return \Response::json(array('status'=>'OK', 'timestamp'=>time(), 'message'=>'FL Status Update', 'statusarray'=>$statusarray ));
            }else{

                \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty order list'));
                return \Response::json(array('status'=>'ERR:EMPTYORDER', 'timestamp'=>time(), 'message'=>'Empty Order List' ));

            }


        }else{

            $actor = 'FL : STATUS PUSH';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty order list'));

            return \Response::json(array('status'=>'ERR:EMPTYORDER', 'timestamp'=>time(), 'message'=>'Empty Order List' ));

        }




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

    public function boxList($field,$val, $device_key ,$obj = false){

        $boxes = \Box::where($field,'=',$val)
                        //->where('deliveryStatus','!=','delivered')
                        //->where('deliveryStatus','!=','returned')
                        ->get();

        $bx = array();

        if($obj == true){

            $boxes = $boxes->toArray();

            for($n = 0; $n < count($boxes);$n++){


                $ob = new \stdClass();

                foreach( $boxes[$n] as $k=>$v ){
                    if($k != '_id' && $k != 'id'){
                        $nk = $this->underscoreToCamelCase($k);
                    }else{
                        $nk = $k;
                    }

                    $ob->$nk = (is_null($v))?'':$v;
                }

                //print_r($ob);
                $ob->extId = $ob->_id;
                unset($ob->_id);

                $ob->status = $this->lastBoxStatus($device_key, $ob->deliveryId, $ob->fulfillmentCode ,$ob->boxId);

                $boxes[$n] = $ob;
            }

            return $boxes;

        }else{
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

    public function lastBoxStatus($device_key, $delivery_id, $fulfillment_code ,$box_id){
        $last = \Boxstatus::where('deliveryId','=',$delivery_id)
                                ->where('deviceKey','=',$device_key)
                                ->where('appname','=',\Config::get('jex.tracker_app'))
                                //->where('fulfillmentCode'.'=',$fulfillment_code)
                                ->where('boxId','=',strval($box_id))
                                ->orderBy('mtimestamp', 'desc')
                                ->first();
        //print_r($last);

        if($last){
            return $last->status;
        }else{
            return 'out';
        }
    }

    private function saveStatus($log, $logistic_name, $logistic_cust_code)
    {
        $log = $log->all();

        if(is_array($log) && count($log) > 0){
            foreach($log as $l){

                if(isset($l['timestamp'])){
                    $l['ts'] = new \MongoDate( strtotime($l['timestamp']) );
                }else{
                    $l['ts'] = new \MongoDate();
                }

                $l['consignee_logistic_id'] = $logistic_name;
                $l['consignee_olshop_cust'] = $logistic_cust_code;

                \Threeplstatuses::insert($l);
            }
        }
    }




}
