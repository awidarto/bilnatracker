<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class JexAwbDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'jex:awb';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Jayon Express AWB Retriever Daemon';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        date_default_timezone_set('Asia/Jakarta');
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

        date_default_timezone_set('Asia/Jakarta');

        $base_url = 'http://www.jayonexpress.com/jexadmin/api/v1/service/awb';
        //$base_url = 'http://localhost/jexadmin/public/api/v1/service/awb';
        $logistic_id = '7735';

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

		$orders = Shipment::where('awb','=','')
                        ->where('logistic_type','=','external')
                        ->where('status','=', Config::get('jayon.trans_status_admin_dated') )
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            /*
            foreach($orders as $ord){
                $req[] = array('order_id'=>$ord->no_sales_order, 'ff_id'=>$ord->consignee_olshop_orderid);
            }
            */

            $req = $orders->toArray();

            //print json_encode($req);

            //die();

            $client = new GuzzleClient(['defaults'=>['exceptions'=>false]]);

            try {

                $response = $client->request('POST', $base_url , array('json'=>$req, 'query'=>array('key'=> $logistic->api_key ) ) );

                //if($response->isSuccessful()){

                    $awblist = json_decode($response->getBody());

                    $awbs = array();
                    $ffs = array();
                    foreach ($awblist as $awb) {
                        $ffs[] = $awb->ff_id;
                        $awbs[$awb->ff_id] = $awb->awb;
                    }

                    $orderlist = Shipment::whereIn('fulfillment_code', $ffs)->get();

                    foreach($orderlist as $order){

                        $pre = clone $order;

                        $order->awb = $awbs[$order->fulfillment_code];
                        //$order->bucket = Config::get('jayon.bucket_tracker');
                        $order->position = '3PL';
                        $order->uploaded = 1;
                        $order->save();

                        $ts = new MongoDate();

                        $hdata = array();
                        $hdata['historyTimestamp'] = $ts;
                        $hdata['historyAction'] = 'api_shipment_change_awb';
                        $hdata['historySequence'] = 1;
                        $hdata['historyObjectType'] = 'shipment';
                        $hdata['historyObject'] = $order->toArray();
                        $hdata['actor'] = $this->name;
                        $hdata['actor_id'] = '';

                        History::insert($hdata);

                        $sdata = array();
                        $sdata['timestamp'] = $ts;
                        $sdata['action'] = 'api_shipment_change_awb';
                        $sdata['reason'] = 'api_update';
                        $sdata['objectType'] = 'shipment';
                        $sdata['object'] = $order->toArray();
                        $sdata['preObject'] = $pre->toArray();
                        $sdata['actor'] = $this->name;
                        $sdata['actor_id'] = '';
                        Shipmentlog::insert($sdata);

                    }


                //}else{
                    print $response->getBody();
                //}


            } catch (Exception $e) {

                print $e;
            }



        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('JexAwbDaemon', 'get' ,$actor,'JEX PUSH DATA AWB PULL'));

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

}
