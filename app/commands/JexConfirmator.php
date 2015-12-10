<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class JexConfirmator extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'jex:confirm';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Jayon Express Order Confirmator';

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


        date_default_timezone_set('Asia/Jakarta');

        $base_url = 'http://www.jayonexpress.com/jexadmin/api/v1/service/confirm';
        //$base_url = 'http://localhost/jexadmin/public/api/v1/service/awb';
        $logistic_id = '1400000655';
        //$logistic_id = '7735';


        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        $orders = Confirmed::where(function($q) use($logistic_id){
                        $q->where('consignee_id','=',$logistic_id )
                            ->orWhere('consignee_id','=',strval($logistic_id));
                    })->where('sent','=',0)->get();

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

            //$client = new GuzzleClient(['defaults'=>['exceptions'=>false]]);

            try {

                //$response = $client->request('POST', $base_url , array('json'=>$req, 'query'=>array('key'=> $logistic->api_key ) ) );
                    $data_string = json_encode($req);
                    print_r($data_string);

                    $url = $base_url.'?key='.$logistic->api_key;

                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string))
                    );

                    $result = curl_exec($ch);

                    //$awblist = json_decode($response->getBody());
                    $res = json_decode($result,true);

                    $reslog = $res;
                    $reslog['consignee_logistic_id'] = $logistic->logistic_code;
                    $reslog['consignee_olshop_cust'] = $logistic_id;
                    Threeplconfirm::insert($reslog);

                    if(isset($res['result']) && $res['result'] == 'NOK'){

                    }else{

                        $awblist = array();

                        foreach ($res as $r) {
                            $awblist[] = $r['awb'];
                        }

                        print $awblist;

                        $ships = Shipment::whereIn('awb',$awblist)->get();

                        foreach($ships as $sh){
                            $sh->sent = 1;
                            $sh->save();
                        }


                    }


                    //die();

                    //$orderlist = Shipment::whereIn('fulfillment_code', $ffs)->get();
                    /*
                    $orderlist = Confirmed::where('awb',$awblist)->get();

                    foreach($orderlist as $order){

                        $pre = clone $order;

                        $order->sent = 1;
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
                    */


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
