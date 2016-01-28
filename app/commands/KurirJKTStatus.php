<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class KurirJKTStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kurirjkt:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Kurir Jakarta Shipment Status Checker';

    /*
        0 Menunggu konfirmasi
        1 Pending
        2 Terjadwal
        3 Terkirim
        4 Batal
        5 Dalam pengiriman
    */

    private $kjktstatus = array(
            'DELIVERED'=>array(3),
            'RETURNED'=>array(4),
            'UNDELIVERED'=>array(6)
        );

    private $kjktstatusdesc = array(
            'Menunggu konfirmasi',
            'Pending',
            'Terjadwal',
            'Terkirim',
            'Batal',
            'Dalam pengiriman',
            'Undelivered'
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

        //$result = $this->sendRequest('8680',null);

        //print_r(json_decode($result,true));


        $logistic_id = '3758';

        $delivery_trigger = $this->kjktstatus['DELIVERED'];
        $returned_trigger = $this->kjktstatus['RETURNED'];
        $undelivered_trigger = $this->kjktstatus['UNDELIVERED'];

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        if($logistic){

        }else{
            die('logistic data not found');
        }

        $orders = Shipment::where('awb','!=','')
                        ->where('bucket','=',Config::get('jayon.bucket_tracker'))
                        ->where(function($sq){
                            $sq->where('status','!=','delivered')
                                ->where('status','!=','undelivered')
                                ->where('status','!=','returned');
                        })
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            foreach($orders as $ord){


                $result = $this->sendRequest($ord->awb,$logistic);

                $res = json_decode($result, true);

                $reslog = $res;
                $reslog['timestamp'] = new MongoDate();
                $reslog['consignee_logistic_id'] = $logistic->logistic_code;
                $reslog['consignee_olshop_cust'] = $logistic_id;
                Threeplstatuslog::insert($reslog);

                print_r($res);
/*
Array
(
    [result_code] => 1
    [result_description] => success
    [data] => Array
        (
            [order_no] => 2240
            [order_date] => 2015-02-27 09:19:37
            [service_name] => SAME DAY SERVICE
            [status_code] => 3
            [status_description] => Terkirim
            [pickup_date] => 2015-02-27 00:00:00
            [delivered_date] => 2015-02-27 12:45:00
            [pickup_name] => Ibu Mei
            [pickup_addess] => RS Siloam Hospital Kebon Jeruk, Lt. 1 Medical Checkup, Jl. Raya Perjuangan Kav. 8 Kebon Jeruk  Jakarta Barat DKI Jakarta  No. Ponsel: 085713331787 No. Kantor/Rumah: 02153695676
            [destionation_name] => Ibu Stiyati
            [destionation_addess] => AIA Financial Menara Falma Lt. 18, Jl. HR Rasuna Said Blok X-2 Kav. 6   Jakarta Selatan DKI Jakarta  No. Ponsel:  No. Kantor/Rumah:
            [recipient_name] => bayu
            [kurir_name] => Fachrul
            [kurir_longitude] =>
            [kurir_latitude] =>
            [last_update] => 2015-02-27 12:45:00
        )

)
*/

                if(isset($res['result_code']) && $res['result_code'] == 1){

                    $ord = Shipment::where('awb','=',$res['data']['order_no'])
                                ->orderBy('created_at','desc')
                                ->first();

                    if($ord){
                        $pre = clone $ord;

                        $laststat = $res['data'];

                        $lst = intval( trim($laststat['status_code']) );


                        if( in_array( $lst , $delivery_trigger) ){
                            $ord->status = \Config::get('jayon.trans_status_mobile_delivered');
                            $ord->position = 'CUSTOMER';
                        }

                        if( in_array( $lst , $returned_trigger) ){
                            $ord->status = \Config::get('jayon.trans_status_mobile_return');
                        }

                        /*
                        if( in_array( $lst , $undelivered_trigger) ){
                            $ord->status = \Config::get('jayon.trans_status_undelivered');
                        }
                        */

                        //$ord->district = $ls->district;
                        $ord->logistic_status = $laststat['status_description'];
                        $ord->logistic_status_ts = date('Y-m-d H:i:s',time());
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

                        //History::insert($hdata);

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
        Event::fire('log.api',array('KurirJKTStatusDaemon', 'get' ,$actor,'KurirJKT STATUS PULL'));


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

    private function sendRequest($awb,$logistic)
    {
        print 'sending request'."\r\n";

        $base_url = 'http://kurirjakarta.com/api/tracking';

        $awb = trim($awb);

        $postArr = array('NomorOrder'=>$awb);

        //$username = $logistic->api_user;
        //$password = $logistic->api_pass;

        $ch = curl_init();

        $username = "bilna";
        $password = "aKj3Lo8F";

        $header[] = "Username:".$username;
        $header[] = "Password:".$password;

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $base_url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArr));

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

/*
Array
(
    [result_code] => 1
    [result_description] => success
    [data] => Array
        (
            [order_no] => 2240
            [order_date] => 2015-02-27 09:19:37
            [service_name] => SAME DAY SERVICE
            [status_code] => 3
            [status_description] => Terkirim
            [pickup_date] => 2015-02-27 00:00:00
            [delivered_date] => 2015-02-27 12:45:00
            [pickup_name] => Ibu Mei
            [pickup_addess] => RS Siloam Hospital Kebon Jeruk, Lt. 1 Medical Checkup, Jl. Raya Perjuangan Kav. 8 Kebon Jeruk  Jakarta Barat DKI Jakarta  No. Ponsel: 085713331787 No. Kantor/Rumah: 02153695676
            [destionation_name] => Ibu Stiyati
            [destionation_addess] => AIA Financial Menara Falma Lt. 18, Jl. HR Rasuna Said Blok X-2 Kav. 6   Jakarta Selatan DKI Jakarta  No. Ponsel:  No. Kantor/Rumah:
            [recipient_name] => bayu
            [kurir_name] => Fachrul
            [kurir_longitude] =>
            [kurir_latitude] =>
            [last_update] => 2015-02-27 12:45:00
        )

)
*/

            $status = $log['data'];
            $st = Threeplstatuses::where('consignee_olshop_cust','=',$logistic_cust_code)
                    ->where('awb','=',$status['order_no'])
                    ->where('datetime',$status['last_update'])
                    ->first();

            if($st){

            }else{

                if(isset($status['last_update'])){
                    $status['ts'] = new MongoDate( strtotime( $status['last_update'] ) );
                }else{
                    $status['ts'] = new MongoDate();
                }
                $status['raw'] = 0;
                $status['awb'] = $status['order_no'];
                $status['consignee_logistic_id'] = $logistic_name;
                $status['consignee_olshop_cust'] = $logistic_cust_code;
                Threeplstatuses::insert($status);
            }

    }


}
