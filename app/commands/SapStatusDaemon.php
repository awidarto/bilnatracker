<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SapStatusDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sap:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'SAP Status Retriever Daemon';

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

        //$base_url = 'http://localhost/jexadmin/public/api/v1/service/status';
        $base_url = 'http://182.23.64.151/serverapi.sap/api/tracking/list/id/';
        $logistic_id = 'CGKN00284';

        $delivery_trigger = 'delivered';
        $returned_trigger = 'returned';

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
                //$req[] = array('awb'=>$ord->awb);
                //$client = new GuzzleClient();

                $url = $base_url.$ord->awb;

                print $url;

                //$request = $client->get($url, array());

                //$request->setAuth('sapclientapi', 'SAPCLIENTAPI_2014');

                //$response = $request->send();

                //print $response->getBody();


                        $ch = curl_init($url);

                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                        curl_setopt($ch, CURLOPT_USERPWD, "5490188:5351");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        /*
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );*/

                        $result = curl_exec($ch);

                        $res = json_decode($result, true);

            }

            /*
            $awblist = json_decode($response->getBody());


            $awbs = array();
            $ffs = array();
            foreach ($awblist as $awb) {
                $awbarray[] = trim($awb->awb);
                $awbs[$awb->awb] = $awb;
            }

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

                $order->district = $awbs[$order->awb]->district;
                $order->logistic_status = $awbs[$order->awb]->status;
                $order->logistic_status_ts = $awbs[$order->awb]->timestamp;
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

                History::insert($hdata);

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

            */

        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('SapStatusDaemon', 'get' ,$actor,'SAP STATUS PULL'));
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

}
