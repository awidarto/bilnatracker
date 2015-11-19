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
        $base_url = 'http://www.jayonexpress.com/jexadmin/api/v1/service/status';
        $logistic_id = '7735';

        $logistic = Logistic::where('consignee_olshop_cust','=',$logistic_id)->first();

        $orders = Shipment::where('awb','!=','')
                        ->where('status','!=','delivered')
                        ->where('logistic_type','=','external')
                        ->where('consignee_olshop_cust','=',$logistic_id)
                        ->get();

        if($orders && count($orders->toArray()) > 0){
            $req = array();
            foreach($orders as $ord){
                $req[] = array('awb'=>$ord->awb);
            }

            $client = new GuzzleClient();

            $response = $client->request('POST', $base_url , array('json'=>$req, 'query'=>array('key'=> $logistic->api_key ) ) );

            print( $response->getBody() );
        }else{
            print 'Empty order list'."\r\n";
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
