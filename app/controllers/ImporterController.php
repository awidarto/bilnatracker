<?php

class ImporterController extends BaseController {

    public $controller_name;

    public $form_framework = 'TwitterBootstrap';

    public $upload_dir;

    public $input_name;

    public function __construct()
    {

        date_default_timezone_set('Asia/Jakarta');

        Former::framework($this->form_framework);

        //$this->beforeFilter('auth', array('on'=>'get', 'only'=>array('getIndex','getAdd','getEdit') ));

        $this->backlink = strtolower($this->controller_name);

    }

    public function getIndex()
    {
        return View::make(strtolower($this->controller_name).'.input')
            ->with('title',$this->title)
            ->with('input_name',$this->input_name)
            ->with('submit',strtolower($this->controller_name).'/upload');
    }

    public function getPreview($input = null)
    {
        $sheets = file_get_contents(realpath($this->upload_dir).'/'.$input.'.json');

        $sheets = json_decode($sheets,true);

        return View::make('tables.importpreview')->with('sheets',$sheets)
            ->with('extract',strtolower($this->controller_name).'/extract');

    }

    public function postUpload()
    {

        $file = Input::file($this->input_name);

        $rstring = str_random(15);

        $destinationPath = realpath($this->upload_dir).'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $sheets = Excel::load($destinationPath.'/'.$filename)->calculate()->toArray();

        $newsheets = array();
        foreach($sheets as $name=>$sheet){
            $newrows = array();
            foreach ($sheet as $row) {
                if(implode('',$row) != '' ){
                    $rstr = str_random(5);
                    $newrows[$rstr] = $row;
                }
            }

            $newsheets[$name] = $newrows;
        }

        file_put_contents(realpath($this->upload_dir).'/'.$rstring.'.json', json_encode($newsheets));

        return Redirect::to(strtolower($this->controller_name).'/preview/'.$rstring);

    }

    public function postExtract()
    {
        $heads = Input::get('ext');

        unset($heads[0]);
        unset($heads[1]);

        file_put_contents(realpath($this->upload_dir).'/heads.json', json_encode($heads));

        return Response::json(array('status'=>'OK'));
    }

    public function missingMethod($param = array())
    {
        //print_r($param);
    }

}