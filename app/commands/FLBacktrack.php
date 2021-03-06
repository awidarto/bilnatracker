<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FLBacktrack extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fl:backtrack';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Backtracker for FL';

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
        $count = Shipment::where('consignee_olshop_cust','=','1400000655')
                            ->where('logistic_status' ,'regexp', '/^DELIVERED/i')
                            ->where('status','!=','delivered')
                            ->count();
        print $count;


        $orders = Shipment::where('consignee_olshop_cust','=','1400000655')
                            ->where('logistic_status' ,'regexp', '/^DELIVERED/i')
                            ->where('status','!=','delivered')
                            ->get();

        foreach($orders as $ord){
            //print_r($ord->toArray());

            $lstat = $ord->logistic_raw_status;

            //print_r($lstat);
            $ord->status = 'delivered';
            $ord->delivered_time = $lstat['delivered_date'].' '.$lstat['delivered_time'];

            print $ord->delivered_time.' '.$ord->status."\r\n";

            //$ord->save();

        }


        $fl_status = Shipment::distinct('logistic_status')->where('logistic','=','FL')->get();

        print_r($fl_status->toArray());

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

}
