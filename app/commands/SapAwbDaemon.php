<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use SoapBox\Formatter\Formatter;
use Nathanmac\Utilities\Parser\Parser;

class SapAwbDaemon extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sap:awb';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'SAP AWB Create & Retrieve Daemon';

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
        $base_url = 'http://103.14.21.47:8579/sp_sap2.gvm';
        //$base_url = 'https://103.14.21.47:8879/sp_sap.gvm';
        //$base_url = 'http://localhost/jexadmin/public/api/v1/service/awb';
        $logistic_id = 'CGKN00284';

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        $orders = Shipment::where('awb','=','')
                        ->where('logistic_type','=','external')
                        ->where('status','=', Config::get('jayon.trans_status_admin_dated') )
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();

            foreach($orders as $ord){
                $req = array(
                        'orderid'=>$ord->consignee_olshop_orderid,
                        'awb'=>0,
                        'invoice'=>$ord->no_sales_order,
                        'address'=> str_replace(array("\r","\n"), ' ', $ord->consignee_olshop_addr ),
                        'name'=>$ord->consignee_olshop_name,
                        'province'=>($ord->consignee_olshop_province == '')?'NA':$ord->consignee_olshop_province,
                        'cod'=>$ord->cod,
                        'volumes'=>1,
                        'chargeable'=>$ord->cod,
                        'actualweight'=>$ord->w_v,
                        'volumeweight'=>$ord->w_v,
                        'partner'=>'Bilna',
                        'city'=>$ord->consignee_olshop_city,
                        'userid'=>5490188,
                        'passwd'=>5351
                    );

                $formatter = Formatter::make($req, Formatter::ARR);

                try {

                    //$response = $client->request('POST', $base_url , array('json'=>$req, 'query'=>array('key'=> $logistic->api_key ) ) );

                        //$data_string = $formatter->toXml();

                        //$data_string = str_replace(array('<xml>','</xml>'), array('<sap>','</sap>'), $data_string);

                        //print $data_string;

                        $data_string = json_encode($req);

                        $url = $base_url;
                        //.'?key='.$logistic->api_key;

                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                        curl_setopt($ch, CURLOPT_USERPWD, "5490188:5351");
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($data_string))
                        );

                        $result = curl_exec($ch);

                        //$awblist = json_decode($response->getBody());

                        //$parser = new Parser();

                        //$res = $parser->xml($result);

                        $res = json_decode($result);

                        print_r($res);

                        if(isset($res['status']) && strval($res['status']) == '00'){
                            $pre = clone $ord;

                            if(isset($res['awb']) && $res['awb'] != ''){
                                $ord->awb = (isset($res['awb']))?$res['awb']:'';
                                //$ord->awb = $awbs[$ord->fulfillment_code];
                                //$ord->bucket = Config::get('jayon.bucket_tracker');
                                //$ord->position = '3PL';
                                $ord->uploaded = 1;
                                $ord->save();

                                $ts = new MongoDate();

                                $hdata = array();
                                $hdata['historyTimestamp'] = $ts;
                                $hdata['historyAction'] = 'api_shipment_change_awb';
                                $hdata['historySequence'] = 1;
                                $hdata['historyObjectType'] = 'shipment';
                                $hdata['historyObject'] = $ord->toArray();
                                $hdata['actor'] = $this->name;
                                $hdata['actor_id'] = '';

                                History::insert($hdata);

                                $sdata = array();
                                $sdata['timestamp'] = $ts;
                                $sdata['action'] = 'api_shipment_change_awb';
                                $sdata['reason'] = 'api_update';
                                $sdata['objectType'] = 'shipment';
                                $sdata['object'] = $ord->toArray();
                                $sdata['preObject'] = $pre->toArray();
                                $sdata['actor'] = $this->name;
                                $sdata['actor_id'] = '';
                                Shipmentlog::insert($sdata);

                            }

                        }

                        //$awblist = json_decode($result);

                        //print $result;

                        //die();
                        /*
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
                        */


                } catch (Exception $e) {

                    print $e;
                }




            }// end order loop


            //$req = $orders->toArray();

            //print json_encode($req);

            //die();

            //$client = new GuzzleClient(['defaults'=>['exceptions'=>false]]);




        }else{
            print 'Empty order list'."\r\n";
        }

        $actor = $this->name;
        Event::fire('log.api',array('JexAwbDaemon', 'get' ,$actor,'SAP PUSH DATA AWB PULL'));
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
