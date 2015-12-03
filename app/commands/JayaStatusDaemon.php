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
        $base_url = 'http://j-express.id/serverapi.jet/api/tracking/tracking-list.php';
        $logistic_id = 'CGKN00027';
        $delivery_trigger = 'KIRIMAN DITERIMA OLEH';
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
                $req[] = array('order_id'=>$ord->no_sales_order.'-'.$ord->consignee_olshop_orderid,'awb'=>$ord->awb);
            }

            $client = new GuzzleClient();

            $data_string = json_encode($req);

            print $data_string;

            //$request->setHeader("Accept" , "application/json");
            /*
            $response = $client->request('POST', $base_url , array('json'=>$req,
                'query'=>array('key'=> $logistic->api_key ),
                'auth' => array($logistic->api_user, $logistic->api_pass, 'Basic'),
                'headers' => array('Accept' => 'application/json') ) );

            $awblist = json_decode($response->getBody());
            */

                $postArr    = array('awbs'=>$data_string);

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

                Logger::api($this->name ,$data_string, $result);

                print $result;

            $awbs = array();
            $ffs = array();
            foreach ($awblist as $awb) {
                if( !is_null($awb->cn_no) && $awb->status != 'AWB TIDAK DITEMUKAN'){
                    $awbarray[] = trim($awb->cn_no);
                    $awbs[$awb->awb] = $awb;
                }
            }

            if(count($awbs) > 0){

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

            }


        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('JayaStatusDaemon', 'get' ,$actor,'JAYA STATUS PULL'));

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
