<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RPXStatusDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'rpx:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';


    private $rpx_status = array(
                'DELIVERED'=>'POD',
                'RETURNED'=>'STAT14',
                'UNDELIVERED'=>array(
                        'DEX 03' =>'Consignee address is incorrect',
                        'DEX 07' =>'Failed Delivery - refused by consignee',
                        'DEX 08' =>'Failed Delivery - Closed Resident',
                        'DEX 17' =>'Hold - Requested for future delivery',
                        'DEX 29' =>'Requested to reroute to another address',
                        'DEX 37' =>'Package damage',
                        'DEX 42' =>'Delayed - Holiday',
                        'DEX 59' =>'Hold - Pickup at RPX by consignee',
                        'DEX 84' =>'Delayed - condition beyond control',
                        'DEX 93' =>'Delayed - customers payment is not ready'
                    )
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

        $wsdl="http://api.rpxholding.com/wsdl/rpxwsdl.php?wsdl";
        //$client = new SoapClient($wsdl);
        $username = 'bilna';
        $password  = 'Bilna2015';


        SoapWrapper::add(function ($service) use ($wsdl, $username, $password) {
            $service
                ->name('trackAWB')
                ->wsdl($wsdl)
                ->trace(true)                                                   // Optional: (parameter: true/false)
                //->header()                                                      // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
                //->customHeader($customHeader)                                   // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class
                //->cookie()                                                      // Optional: (parameters: $name,$value)
                //->location()                                                    // Optional: (parameter: $location)
                //->certificate()                                                 // Optional: (parameter: $certLocation)
                ->cache(WSDL_CACHE_NONE);                                        // Optional: Set the WSDL cache
                //->options(['user' => $username, 'password' => $password, 'format'=>'JSON' ]);   // Optional: Set some extra options
        });

        $logistic_id = 'B234-JKT';

        $delivery_trigger = 'DELIVERED';
        $returned_trigger = 'UNDELIVERED';

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        $orders = Shipment::where('awb','!=','')
                        ->where('bucket','=',Config::get('jayon.bucket_tracker'))
                        ->where('status','!=','delivered')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            foreach($orders as $ord){

                $username = $logistic->api_user;
                $password = $logistic->api_pass;

                $data = [$username, $password, '8979867812376','JSON'];
                //string $user, string $password, string $awb, string $format

                SoapWrapper::service('trackAWB', function ($service) use ($data) {
                    var_dump($service->getFunctions());
                    print($service->call('getTrackingAWB', $data));
                    //print_r($service->call('getTrackingAWB', [$data]));
                });

                $res = json_decode($result, true);

                $reslog = $res;
                $reslog['timestamp'] = new MongoDate();
                $reslog['consignee_logistic_id'] = $logistic->logistic_code;
                $reslog['consignee_olshop_cust'] = $logistic_id;
                Threeplstatuslog::insert($reslog);

                print_r($res);



                if(isset($res['cn_no'])){

                    $pre = clone $ord;

                    $ls = $res['laststatus'];

                    if( $ls['status'] == $delivery_trigger){
                        $ord->status = Config::get('jayon.trans_status_mobile_delivered');
                        $ord->position = 'CUSTOMER';
                    }

                    if($ls['status'] == $returned_trigger){
                        $ord->status = Config::get('jayon.trans_status_mobile_return');
                    }

                    //$ord->district = $ls->district;
                    $ord->logistic_status = $ls['status'];
                    $ord->logistic_status_ts = $ls['time'];
                    $ord->logistic_raw_status = $ls;
                    $ord->save();

                    $ts = new MongoDate();

                    $hdata = array();
                    $hdata['historyTimestamp'] = $ts;
                    $hdata['historyAction'] = 'api_shipment_change_status';
                    $hdata['historySequence'] = 1;
                    $hdata['historyObjectType'] = 'shipment';
                    $hdata['historyObject'] = $ord->toArray();
                    $hdata['actor'] = $this->name;
                    $hdata['actor_id'] = '';

                    History::insert($hdata);

                    $sdata = array();
                    $sdata['timestamp'] = $ts;
                    $sdata['action'] = 'api_shipment_change_status';
                    $sdata['reason'] = 'api_update';
                    $sdata['objectType'] = 'shipment';
                    $sdata['object'] = $ord->toArray();
                    $sdata['preObject'] = $pre->toArray();
                    $sdata['actor'] = $this->name;
                    $sdata['actor_id'] = '';
                    Shipmentlog::insert($sdata);

                    $this->saveStatus($res, $logistic->logistic_code, $logistic_id);

                }


            }

        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('RPXStatusDaemon', 'get' ,$actor,'RPX STATUS PULL'));
		//
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
        //SAP use individual AWB request

        if(is_array($log) && count($log) > 0){
            $l = $log;
            if(isset($l['laststatus']['time'])){
                $l['ts'] = new MongoDate( strtotime($l['laststatus']['time']) );
            }else{
                $l['ts'] = new MongoDate();
            }
            $l['consignee_logistic_id'] = $logistic_name;
            $l['consignee_olshop_cust'] = $logistic_cust_code;

            Threeplstatuses::insert($l);
        }
    }


}
