<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Backtrack extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'backtrack:order';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
        $dbox = Boxstatus::where('appname','=',Config::get('jex.tracker_app'))
                            ->where('deliveryStatus','=', Config::get('jayon.trans_status_mobile_delivered') )
                            ->get();

        if($dbox){
            foreach($dbox as $dbx){
                print $dbx->boxId;

                $box = Box::where('delivery_id','=',$dbx->deliveryId)->first();

                if($box){
                    $box->deliveryStatus = $dbx->deliveryStatus;
                    $box->save();
                }
            }
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
