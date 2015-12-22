<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class JexStatusDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'jex:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Jayon Express Status Retriever Daemon';

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
        $base_url = 'http://www.jayonexpress.com/jexadmin/api/v1/service/status';
        $logistic_id = '7735';
        $delivery_trigger = 'delivered';
        $returned_trigger = 'returned';
        $canceled_trigger = 'canceled';

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
                $req[] = array('awb'=>$ord->awb);
            }

            $req[] = array('awb'=>'007735-16-122015-00155749');

            $client = new GuzzleClient();

            $response = $client->request('POST', $base_url , array('json'=>$req, 'query'=>array('key'=> $logistic->api_key ) ) );

            //print( $response->getBody() );



            $awblist = json_decode($response->getBody());


            $reslog = $awblist;
            $reslog['consignee_logistic_id'] = $logistic->logistic_code;
            $reslog['consignee_olshop_cust'] = $logistic_id;
            Threeplstatuslog::insert($reslog);

            $this->saveStatus($awblist,$logistic_id,$logistic->logistic_code);


            $awbs = array();
            $ffs = array();
            foreach ($awblist as $awb) {
                $awbarray[] = trim($awb->awb);
                $awbs[$awb->awb] = $awb;
            }

            print_r($awbs);

            $orderlist = Shipment::whereIn('awb', $awbarray)->get();

            foreach($orderlist as $order){

                $pre = clone $order;

                if( $awbs[$order->awb]->status == $delivery_trigger){
                    $order->status = Config::get('jayon.trans_status_mobile_delivered');
                    $order->delivered_time = $awbs[$order->awb]->delivery_time;
                    $order->position = 'CUSTOMER';
                }

                if($awbs[$order->awb]->status == $returned_trigger){
                    $order->status = Config::get('jayon.trans_status_mobile_return');
                }

                if($awbs[$order->awb]->status == $canceled_trigger){
                    $order->status = Config::get('jayon.trans_status_canceled');
                }

                $order->district = $awbs[$order->awb]->district;
                $order->logistic_status = $awbs[$order->awb]->status;
                $order->logistic_status_ts = $awbs[$order->awb]->timestamp;
                $order->logistic_raw_status = $awbs[$order->awb];
                $order->logistic_delivered_time = $awbs[$order->awb]->delivery_time;
                $order->logistic_pickup_time = $awbs[$order->awb]->pickup_time;
                $order->logistic_last_note = $awbs[$order->awb]->note;


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

        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('JexStatusDaemon', 'get' ,$actor,'JEX STATUS PULL'));

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('e', InputArgument::OPTIONAL, 'An example argument.'),
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


                $l->ts = new MongoDate( strtotime($l->timestamp) );
                $l->consignee_logistic_id = $logistic_name;
                $l->consignee_olshop_cust = $logistic_cust_code;

                $al = array();
                foreach($l as $k=>$l){
                    $al[$k] = $l;
                }

                Threeplstatuses::insert($al);


                print_r($l->pod);

                /*
                foreach($l->pod as $p){
                    $p->ts = new MongoDate( strtotime($l->timestamp) );
                    $p->consignee_logistic_id = $logistic_name;
                    $p->consignee_olshop_cust = $logistic_cust_code;
                    $p->awb = $l->awb;

                    $pl = array();
                    foreach ($p as $pk=>$pv){
                        $pl[$pk] = $pv;
                    }

                    Threeplpictures::insert($pl);
                }
                */

            }
        }
    }

}
