<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class JayaStatusDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'jaya:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Jaya Express Shipment Status Daemon.';

    private $jaya_status = array(
            'ENTRI DATA PENGIRIMAN',
            'KEBERANGKATAN KOTA ASAL',
            'TIBA DIKOTA TRANSIT',
            'BERANGKAT DARI KOTA TRANSIT',
            'TIBA DIKOTA TUJUAN',
            'DALAM PENGANTARAN',
            'KIRIMAN DITERIMA OLEH',
            'KEMBALI KE KOTA ASAL'
        );


	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
    public function fire()
    {

        $logistic_id = 'CGKN00027';

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        if(Prefs::isRunning($this->name)){
            $l = array();
            $l['ts'] = new MongoDate();
            $l['consignee_logistic_id'] = $logistic->logistic_code;
            $l['consignee_olshop_cust'] = $logistic_id;
            Threeplstatuserror::insert($l);
            die('process already running');
        }

        $orders = Shipment::where('awb','!=','')
                        ->where('bucket','=',Config::get('jayon.bucket_tracker'))
                        ->where('status','!=','delivered')
                        ->where('status','!=','canceled')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        $res = array();

        print 'count '.count($orders->toArray());

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            foreach($orders as $ord){
                //$req[] = array('order_id'=>$ord->no_sales_order.'-'.$ord->consignee_olshop_orderid,'awb'=>$ord->awb);
                $req[] = array('order_id'=>$ord->consignee_olshop_orderid,
                                'awb'=>$ord->consignee_olshop_orderid);
            }

            $client = new GuzzleClient();


            //TO DO : Send data in chunk

            $reqchunks = array_chunk($req, 100);


            foreach($reqchunks as $rq){
                $this->sendData($rq,$client,$logistic, $logistic_id );
            }

        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('JayaStatusDaemon', 'get' ,$actor,'JAYA STATUS PULL'));

    }


    public function sendData($req, $client, $logistic , $logistic_id)
    {

        $delivery_trigger = 'KIRIMAN DITERIMA OLEH';
        $returned_trigger = 'KEMBALI KE KOTA ASAL';

        $data_string = json_encode($req);

        //print $data_string;

        //$request->setHeader("Accept" , "application/json");
        /*
        $response = $client->request('POST', $base_url , array('json'=>$req,
            'query'=>array('key'=> $logistic->api_key ),
            'auth' => array($logistic->api_user, $logistic->api_pass, 'Basic'),
            'headers' => array('Accept' => 'application/json') ) );

        $awblist = json_decode($response->getBody());
        */
        $base_url = 'http://j-express.id/serverapi.jet/api/tracking/tracking-list.php';

        $postArr = array('awbs'=>$data_string);

        $url = "http://j-express.id/serverapi.jet/api/tracking/tracking-list.php";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArr));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);

                    /*
                    $ch = curl_init($base_url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                    );

                    $result = curl_exec($ch);
                    print $result;

                    die();
                    */

        $awblist = json_decode($result);

        $res[] = $awblist;

        Logger::api($this->name ,$data_string, $awblist);

        $slog = json_decode($result,true);

        $this->saveStatus($slog, $logistic->logistic_code, $logistic_id);

        $awbs = array();
        $ffs = array();

        print_r($awblist);

        $reslog = $res;
        $reslog['timestamp'] = new MongoDate();
        $reslog['consignee_logistic_id'] = $logistic->logistic_code;
        $reslog['consignee_olshop_cust'] = $logistic_id;
        Threeplstatuslog::insert($reslog);

        try{

            if( is_array($awblist) ){

                foreach ($awblist as $awb) {
                    if( !is_null($awb->cn_no) && $awb->status != 'AWB TIDAK DITEMUKAN'){
                        $awbarray[] = trim($awb->cn_no);
                        $awbs[$awb->cn_no] = $awb;
                    }
                }

                if(count($awbs) > 0){

                    //print_r($awbs);

                    $orderlist = Shipment::whereIn('awb', $awbarray)->get();


                    foreach($orderlist as $order){

                        $pre = clone $order;

                        if( $awbs[$order->awb]->status == $delivery_trigger){
                            $order->status = Config::get('jayon.trans_status_mobile_delivered');
                            $order->position = 'CUSTOMER';
                        }

                        if($awbs[$order->awb]->status == $returned_trigger){
                            $order->status = Config::get('jayon.trans_status_mobile_return');
                        }

                        //$order->district = $awbs[$order->awb]->district;
                        $order->logistic_status = $awbs[$order->awb]->status;
                        $order->logistic_status_ts = $awbs[$order->awb]->time;
                        $order->logistic_raw_status = $awbs[$order->awb];
                        $order->save();

                        $ts = new MongoDate();

                        $hdata = array();
                        $hdata['historyTimestamp'] = $ts;
                        $hdata['historyAction'] = 'api_shipment_change_status';
                        $hdata['historySequence'] = 1;
                        $hdata['historyObjectType'] = 'shipment';
                        $hdata['historyObject'] = $order->toArray();
                        $hdata['actor'] = $this->name;
                        $hdata['actor_id'] = '';

                        //History::insert($hdata);

                        $sdata = array();
                        $sdata['timestamp'] = $ts;
                        $sdata['action'] = 'api_shipment_change_status';
                        $sdata['reason'] = 'api_update';
                        $sdata['objectType'] = 'shipment';
                        $sdata['object'] = $order->toArray();
                        $sdata['preObject'] = $pre->toArray();
                        $sdata['actor'] = $this->name;
                        $sdata['actor_id'] = '';
                        Shipmentlog::insert($sdata);

                    }

                }

            }else{
                $l = array();
                $l['data'] = $awblist;
                $l['ts'] = new MongoDate();
                $l['consignee_logistic_id'] = $logistic->logistic_code;
                $l['consignee_olshop_cust'] = $logistic_id;
                Threeplstatuserror::insert($l);
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }



    }


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

    private function saveStatus($log, $logistic_name, $logistic_cust_code)
    {
        if(is_array($log) && count($log) > 0){
            foreach($log as $l){
                if(isset($l['time'])){
                    $l['ts'] = new MongoDate( strtotime($l['time']) );
                }else{
                    $l['ts'] = new MongoDate();
                }
                $l['consignee_logistic_id'] = $logistic_name;
                $l['consignee_olshop_cust'] = $logistic_cust_code;
                if($l['status'] == 'AWB TIDAK DITEMUKAN'){
                    Threeplstatuserror::insert($l);
                }else{
                    Threeplstatuses::insert($l);
                }
            }
        }
    }

}
