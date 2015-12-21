<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class E21ExpressStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'e21express:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

    private $e21status = array(
            'DELIVERED'=>array('RECEIVED'),
            'RETURNED'=>array('RETURNED'),
            'UNDELIVERED'=>array('MISS-DELIVERY','LOSS')

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

        $logistic_id = 'B234-JKT';

        $delivery_trigger = $this->e21status['DELIVERED'];
        $returned_trigger = $this->e21status['RETURNED'];
        $undelivered_trigger = $this->e21status['UNDELIVERED'];

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        $token = '';

        $token_file = public_path().'/storage/21oauth.key';

        if(file_exists($token_file)){
            $token = file_get_contents(public_path().'/storage/21oauth.key');
        }

        if($token == ''){
            $token = $this->getToken($logistic);
        }else{
            $token = json_decode($token);
            if(isset($token->access_token)){
                $token = $token->access_token;
            }
        }

        $orders = Shipment::where('awb','!=','')
                        ->where('bucket','=',Config::get('jayon.bucket_tracker'))
                        ->where('status','!=','delivered')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            foreach($orders as $ord){

                $result = $this->sendRequest($ord->awb,$logistic,$token);

                $res = json_decode($result, true);

                if(isset($res['code']) && ($res['code'] == '0' || $res['code'] == 0) )
                {
                    print 'reset token'."\r\n";

                    $token = $this->getToken($logistic);

                    $res = $this->sendRequest($ord->awb,$logistic,$token);

                    $res = json_decode($result, true);

                }



                $reslog = $res;
                $reslog['timestamp'] = new MongoDate();
                $reslog['consignee_logistic_id'] = $logistic->logistic_code;
                $reslog['consignee_olshop_cust'] = $logistic_id;
                Threeplstatuslog::insert($reslog);

                print_r($res);

                if(isset($res['shipment'])){

                    $ord = Shipment::where('awb','=',$res['shipment']['code'])
                                ->orderBy('created_at','desc')
                                ->first();

                    if($ord){
                        $pre = clone $ord;

                        $laststat = $res['shipment']['statuses'];

                        $laststat = array_pop($laststat);

                        $lst = trim($laststat['status']);


                        if( in_array( $lst , $delivery_trigger) || $lst == 'RECEIVED' ){
                            $ord->status = \Config::get('jayon.trans_status_mobile_delivered');
                            $ord->position = 'CUSTOMER';
                        }

                        if( in_array( $lst , $returned_trigger) || $lst == 'RETURN' ){
                            $ord->status = \Config::get('jayon.trans_status_mobile_return');
                        }

                        if( in_array( $lst , $undelivered_trigger) || $lst == 'NOT DELIVERED' ){
                            $ord->status = \Config::get('jayon.trans_status_mobile_return');
                        }

                        //$ord->district = $ls->district;
                        $ord->logistic_status = $laststat['status'];
                        $ord->logistic_status_ts = $laststat['datetime'];
                        $ord->logistic_raw_status = $laststat;
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


            }

        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('E21StatusDaemon', 'get' ,$actor,'E21 STATUS PULL'));
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


    private function getToken($logistic){

        print 'requesting token'."\r\n";

        $base_oauth = 'http://119.110.72.234/api/oauth/access_token';

        $token_file = public_path().'/storage/21oauth.key';

        $formdata = array(
            'grant_type'=>'password',
            'client_id'=>$logistic->api_key,
            'client_secret'=>$logistic->api_key_secret,
            'username'=>$logistic->api_user,
            'password'=>$logistic->api_pass
        );

        print_r($formdata);

        die();
        $choauth = curl_init();

        curl_setopt($choauth, CURLOPT_URL, $base_oauth);
        curl_setopt($choauth, CURLOPT_POST, 1);
        curl_setopt($choauth, CURLOPT_POSTFIELDS, http_build_query($formdata));
        curl_setopt($choauth, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($choauth, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($choauth);

        file_put_contents($token_file, $result);

        $result = json_decode($result);

        print $result;

        if(isset($result->access_token)){
            return $result->access_token;
        }else{
            return $result;
        }

    }

    private function sendRequest($awb,$logistic, $token)
    {
        print 'sending request'."\r\n";

        $base_url = 'http://119.110.72.234/api/v1/shipment/';

        $url = $base_url.$awb.'?access_token='.$token;

        $username = $logistic->api_user;
        $password = $logistic->api_pass;

        print $url."\r\n";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $result = curl_exec($ch);

        if(!$result){
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        }

        $status_code = curl_getinfo($ch);   //get status code

        curl_close ($ch);

        return $result;
    }

    private function saveStatus($log, $logistic_name, $logistic_cust_code)
    {
        //SAP use individual AWB request
        $statuses = $log['shipment']['statuses'];

        if(is_array($statuses)){
            foreach ($statuses as $stat) {
                $st = Threeplstatuses::where('consignee_olshop_cust','=',$logistic_cust_code)
                        ->where('awb','=',$log['shipment']['code'])
                        ->where('datetime',$stat['datetime'])
                        ->first();

                if($st){

                }else{
                    if(isset($stat['datetime'])){
                        $stat['ts'] = new MongoDate( strtotime($stat['datetime']) );
                    }else{
                        $stat['ts'] = new MongoDate();
                    }
                    $stat['raw'] = 0;
                    $stat['awb'] = $log['shipment']['code'];
                    $stat['consignee_logistic_id'] = $logistic_name;
                    $stat['consignee_olshop_cust'] = $logistic_cust_code;
                    Threeplstatuses::insert($stat);
                }
            }
        }

        $stat = $log['shipment'];

        if(isset($stat['datetime'])){
            $stat['ts'] = new MongoDate( strtotime($stat['datetime']) );
        }else{
            $stat['ts'] = new MongoDate();
        }

        //$stat['raw'] = 1;
        //$stat['awb'] = $log['shipment']['code'];
        //$stat['consignee_logistic_id'] = $logistic_name;
        //$stat['consignee_olshop_cust'] = $logistic_cust_code;
        //Threeplstatuses::insert($stat);

        //$stat['timestamp'] = new \MongoDate();
        //Threeplstatuslog::insert($stat);

    }

}
