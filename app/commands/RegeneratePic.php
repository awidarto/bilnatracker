<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RegeneratePic extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pic:regenerate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Regenerate object pictures to configurable sizes.';

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


        set_time_limit(0);

        $obj = $this->argument('object');

        if(is_null($obj)){
            $product = new Product();
        }else{
            switch($obj){
                case 'product' :
                            $product = new Product();
                            break;
                case 'page' :
                            $product = new Page();
                            break;
                case 'post' :
                            $product = new Posts();
                            break;
                default :
                            $product = new Product();
                            break;
            }
        }

        $props = $product->orderBy('_id','desc')->get();

        //$seq = new Sequence();

        $sizes = Config::get('picture.sizes');

        $cnt = 1;

        foreach($props as $p){
            echo $cnt."\n";
            $cnt++;
            echo $p->_id."\n";

            if(isset($p->files)){
                $files = $p->files;

                foreach($files as $folder=>$files){

                    $dir = public_path().'/storage/media/'.$folder;

                    if (is_dir($dir) && file_exists($dir)) {
                        if ($dh = opendir($dir)) {
                            while (($file = readdir($dh)) !== false) {
                                if($file != '.' && $file != '..'){
                                    if(!preg_match('/^lrg_|med_|th_|full_|blob/', $file)){
                                        echo $dir.'/'.$file."\n";

                                        $destinationPath = $dir;
                                        $filename = $file;

                                        $urls = array();

                                        foreach($sizes as $k=>$v){
                                            echo $destinationPath.'/'.$v['prefix'].$filename."\n";
                                            if( file_exists($destinationPath.'/'.$v['prefix'].$filename) ){
                                                unlink($destinationPath.'/'.$v['prefix'].$filename);
                                            }else{
                                                echo "file does not exist\n";
                                            }
                                            $thumbnail = Image::make($destinationPath.'/'.$filename)
                                                ->fit($v['width'],$v['height'])
                                                //->insert($sm_wm,0,0, 'bottom-right')
                                                ->save($destinationPath.'/'.$v['prefix'].$filename);
                                                //print_r($thumbnail);

                                        }
                                        /*
                                        $thumbnail = Image::make($destinationPath.'/'.$filename)
                                            ->fit( $sizes['thumbnail']['width'] ,$sizes['thumbnail']['height'])
                                            ->save($destinationPath.'/th_'.$filename);

                                        $medium = Image::make($destinationPath.'/'.$filename)
                                            ->fit( $sizes['medium']['width'] ,$sizes['medium']['height'])
                                            ->save($destinationPath.'/med_'.$filename);

                                        $large = Image::make($destinationPath.'/'.$filename)
                                            ->fit( $sizes['large']['width'] ,$sizes['large']['height'])
                                            ->save($destinationPath.'/lrg_'.$filename);

                                        $full = Image::make($destinationPath.'/'.$filename)
                                            ->fit( $sizes['full']['width'] ,$sizes['full']['height'])
                                            ->save($destinationPath.'/full_'.$filename);
                                        */
                                    }
                                }
                            }
                            closedir($dh);
                        }
                    }
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
			array('object', InputArgument::REQUIRED, 'Parent object of picture to regenerate'),
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
