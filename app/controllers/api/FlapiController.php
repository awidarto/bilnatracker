<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class FlapiController extends \BaseController {


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

        if( is_null($key) || $key == ''){
            $actor = 'no id : no name';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty key'));

            return \Response::json(array('status'=>'ERR:EMPTYKEY', 'timestamp'=>time(), 'message'=>'Empty Key' ));
        }

        $logistic = \Logistic::where('api_key','=',$key)->first();


        $logistic_id = $logistic->consignee_olshop_cust;

        $orders = \Shipment::where('awb','!=','')
                        ->where('bucket','=',\Config::get('jayon.bucket_tracker'))
                        ->where('status','!=','delivered')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        $orders = $orders->toArray();

        //print_r($orders);


        $orderlist = array();

        for($n = 0; $n < count($orders);$n++){

            $o = $orders[$n];

            $entry = array();

            $entry['hawb'] = $o['awb'];

            $entry['remark'] = '-';

            $consignee = array(
                'cn_name'=> $o['consignee_olshop_name'],
                'address'=> $o['consignee_olshop_addr'] ,
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
                'merchant_address'=> 'Kawasan Pergudangan
PT. WIDYA SAKTI KUSUMA
Jl. Raya Bekasi KM 28
( Jl. Wahab Affan ) Pondok Ungu,
Medan Satria, Bekasi 17132',
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

/*
array (
  '_id' => new MongoId("564c5dedccae5b110f0041f7"),
  'awb' => '007735-17-112015-00146067',
  'bucket' => 'tracker',
  'cod' => 127000,
  'consignee_olshop_addr' => 'bagus sulaiman
jl.bakti rt.004 rw.008 no.10b cililitan kramatjati 13640
Jakarta Timur JK 13640
Indonesia',
  'consignee_olshop_city' => 'Jakarta Timur',
  'consignee_olshop_cust' => '7735',
  'consignee_olshop_desc' => 'Susu dan Perlengkapan Bayi',
  'consignee_olshop_name' => '106191 bagus sulaiman',
  'consignee_olshop_orderid' => '334975',
  'consignee_olshop_phone' => '81317857612',
  'consignee_olshop_region' => 'JK',
  'consignee_olshop_service' => 'COD',
  'consignee_olshop_zip' => '13640',
  'contact' => '106191 bagus sulaiman',
  'courier_id' => '',
  'courier_name' => '',
  'courier_status' => 'at_initial_node',
  'createdDate' => new MongoDate(1447845357, 699000),
  'created_at' => '2015-11-18 18:15:20',
  'delivery_id' => '18-112015-JEJVV',
  'delivery_type' => 'REG',
  'device_id' => '',
  'device_key' => '',
  'device_name' => '',
  'district' => '',
  'email' => 'bagus_sulaiman@india.com',
  'fulfillment_code' => '334975',
  'lastUpdate' => new MongoDate(1447845357, 699000),
  'logistic' => 'JEX',
  'logistic_raw_status' =>
  array (
    'awb' => '007735-17-112015-00146067',
    'timestamp' => '2015-11-19 11:18:23',
    'pending' => '0',
    'status' => 'delivered',
    'note' => 'iwan 12.05',
  ),
  'logistic_status' => 'delivered',
  'logistic_status_ts' => '2015-11-19 11:18:23',
  'logistic_type' => 'external',
  'no_sales_order' => '100364363',
  'number_of_package' => '1',
  'order_id' => '100364363',
  'pending_count' => new MongoInt64(0),
  'pick_up_date' => new MongoDate(1447952400, 0),
  'pickup_status' => 'to_be_picked_up',
  'position' => 'CUSTOMER',
  'status' => 'delivered',
  'trip' => new MongoInt64(1),
  'updated_at' => new MongoDate(1447931897, 898000),
  'w_v' => '0.9',
  'warehouse_status' => 'at_initial_node',
)

*/


            $orderlist[] = $entry;
        }


        $actor = $key;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'logged out'));

        return $orderlist;
        //
    }

    public function postStatus()
    {

        $key = \Input::get('key');

        //$user = \Apiauth::user($key);

        if( is_null($key) || $key == ''){
            $actor = 'no id : no name';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'empty key'));

            return \Response::json(array('status'=>'ERR:EMPTYKEY', 'timestamp'=>time(), 'message'=>'Empty Key' ));
        }

        $logistic = \Logistic::where('api_key','=',$key)->first();

        $json = \Input::all();

        $batch = \Input::get('batch');

        $result = array();



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

}
