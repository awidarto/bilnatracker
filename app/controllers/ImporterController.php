<?php

class ImporterController extends AdminController {

    public $controller_name;

    public $form_framework = 'TwitterBootstrap';

    public $upload_dir;

    public $input_name;

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));
        $this->title = 'Data Import';

        $this->model = new Asset();
        //$this->model = DB::collection('documents');

    }

    public function getIndex()
    {
        $controller_name = strtolower($this->controller_name);

        $this->title = ($this->title == '')?Str::plural($this->controller_name):Str::plural($this->title);

        Breadcrumbs::addCrumb($this->title,URL::to($controller_name));

        return View::make('importer.importinput')
            ->with('title', 'Assets Data')
            //->with('input_name',$this->input_name)
            ->with('importkey', $this->importkey)
            ->with('back',strtolower($this->controller_name))
            ->with('submit',strtolower($this->controller_name).'/uploadimport');
    }

    public function postUploadimport()
    {
        $locationId = Input::get('locationId');

        $locationName = $this->locationName($locationId);

        $file = Input::file('inputfile');

        $headindex = Input::get('headindex');

        $firstdata = Input::get('firstdata');

        $importkey = (!is_null($this->importkey))?Input::get('importkey'):$this->importkey;

        //$importkey = $this->importkey;

        $rstring = str_random(15);

        $destinationPath = realpath('storage/upload').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);

        $fileitems = array();

        if($uploadSuccess){

            $xlsfile = realpath('storage/upload').'/'.$rstring.'/'.$filename;

            //$imp = Excel::load($xlsfile)->toArray();

            $imp = array();

            Excel::load($xlsfile,function($reader) use (&$imp){
                $imp = $reader->get();
            })->get();

            $imported = array();
            foreach($imp as $sheet){

                $rackName = trim($sheet->getTitle());
                $rackId = $this->rackId($rackName, $locationId, $locationName);



                print $rackId.' - '.$rackName.' - '.$locationId.' - '.$locationName."\r\n";

                $i = 0;
                $heads = array();
                foreach($sheet as $rows){
                    if($i == $headindex){
                        $heads = array();
                        foreach($rows as $r){
                            $heads[] = $r;
                        }
                    }

                    if($i >= $firstdata){

                        $row = array();

                        $y = 0;
                        foreach($rows as $r){
                            $row[ $heads[$y] ] = $r;
                            $y++;
                        }

                        $add = array();
                        $add['locationName'] = $locationName;
                        $add['locationId'] = $locationId;
                        $add['rackId'] = $rackId;

                        if(isset($row['SKU']) && $row['SKU'] == ''){
                            $row['SKU'] = strtoupper(str_random(8)) ;
                        }

                        $row = array_merge($add,$row);

                        $imported[] = $row;
                    }

                    $i++;
                }

            }

            $hadd = array(
                        'locationName',
                        'locationId',
                        'rackId'
                    );

            $heads = array_merge($hadd,$heads);

            //print_r($heads);
            //print_r($imported);

            //die();

            $sessobj = new Importsession();

            $sessobj->heads = $heads;
            $sessobj->isHead = 1;
            $sessobj->sessId = $rstring;
            $sessobj->save();

            foreach($imported as $import){

                $import['isHead'] = 0;
                $import['sessId'] = $rstring;

                Importsession::insert($import);

            }

        }

        $this->backlink = strtolower($this->controller_name);

        $commit_url = $this->backlink.'/commit/'.$rstring;

        return Redirect::to($commit_url);

    }

    public function getCommit($sessid)
    {
        $heads = Importsession::where('sessId','=',$sessid)
            ->where('isHead','=',1)
            ->first();

        $heads = $heads['heads'];

        $imports = Importsession::where('sessId','=',$sessid)
            ->where('isHead','=',0)
            ->get();

        $headselect = array();

        foreach ($heads as $h) {
            $headselect[$h] = $h;
        }

        $vl = $this->validateData($imports->toArray());

        $dbval = $vl['db'];
        $inval = $vl['in'];

        $title = $this->controller_name;

        $submit = strtolower($this->controller_name).'/commit/'.$sessid;

        $controller_name = strtolower($this->controller_name);

        $this->title = ($this->title == '')?Str::plural($this->controller_name):Str::plural($this->title);

        Breadcrumbs::addCrumb($this->title,URL::to($controller_name));

        Breadcrumbs::addCrumb('Import '.$this->title,URL::to($controller_name.'/import'));

        Breadcrumbs::addCrumb('Preview',URL::to($controller_name.'/import'));

        return View::make('importer.commitselect')
            ->with('title',$title)
            ->with('submit',$submit)
            ->with('headselect',$headselect)
            ->with('heads',$heads)
            ->with('dbval',$dbval)
            ->with('inval',$inval)
            ->with('back',$controller_name.'/import')
            ->with('imports',$imports);
    }

    public function postCommit($sessid)
    {
        $in = Input::get();

        $importkey = $in['edit_key'];

        $selector = $in['selector'];

        $edit_selector = isset($in['edit_selector'])?$in['edit_selector']:array();

        foreach($selector as $selected){
            $rowitem = Importsession::find($selected)->toArray();

            $do_edit = in_array($selected, $edit_selector);

            if($importkey != '' && !is_null($importkey) && isset($rowitem[$importkey]) && $do_edit ){
                $obj = $this->model
                    ->where($importkey, 'exists', true)
                    ->where($importkey, '=', $rowitem[$importkey])->first();

                if( isset($rowitem['powerStatus']) ){
                    $rowitem['powerStatus'] = ( strtolower($rowitem['powerStatus']) == 'yes' || strtolower($rowitem['powerStatus']) == 'y' || intval($rowitem['powerStatus']) == 1 )?'yes':'no';
                }

                if( isset($rowitem['labelStatus']) ){
                    $rowitem['labelStatus'] = ( strtolower($rowitem['labelStatus']) == 'yes' || strtolower($rowitem['labelStatus']) == 'y' || intval($rowitem['labelStatus']) == 1 )?'yes':'no';
                }

                if( isset($rowitem['virtualStatus']) ){
                    $rowitem['virtualStatus'] = ( strtolower($rowitem['virtualStatus']) == 'yes' || strtolower($rowitem['virtualStatus']) == 'y' || intval($rowitem['virtualStatus']) == 1 )?'yes':'no';
                }


                if($obj){

                    foreach($rowitem as $k=>$v){
                        if($v != ''){
                            $obj->{$k} = $v;
                        }
                    }

                    $obj->save();
                }else{

                    unset($rowitem['_id']);
                    $rowitem['createdDate'] = new MongoDate();
                    $rowitem['lastUpdate'] = new MongoDate();

                    $rowitem = $this->beforeImportCommit($rowitem);

                    $this->model->insert($rowitem);
                }


            }else{

                unset($rowitem['_id']);
                $rowitem['createdDate'] = new MongoDate();
                $rowitem['lastUpdate'] = new MongoDate();

                $rowitem = $this->beforeImportCommit($rowitem);

                $this->model->insert($rowitem);

            }


        }

        $this->backlink = strtolower($this->controller_name);

        return Redirect::to($this->backlink);

    }

    public function validateData($items)
    {
        $assetnames = array();
        foreach($items as $item){
            $assetnames[] = strval($item['SKU']);
        }

        $namecount = array_count_values($assetnames);

        $dbval = array();
        foreach($items as $item){
            $dbval[$item['SKU']] = Asset::where('SKU',$item['SKU'])->count();
        }

        return array( 'in' => $namecount, 'db'=>$dbval );

    }

    public function beforeImportCommit($rowitem)
    {
        return $rowitem;
    }

    public function postExtract()
    {
        $heads = Input::get('ext');

        unset($heads[0]);
        unset($heads[1]);

        file_put_contents(realpath($this->upload_dir).'/heads.json', json_encode($heads));

        return Response::json(array('status'=>'OK'));
    }

    private function locationName($locationId){
        $loc = Assetlocation::find($locationId);
        if($loc){
            return $loc->name;
        }else{
            return '';
        }
    }

    private function rackId($rackName, $locationId, $locationName){
        $rack = Rack::where('SKU',$rackName)
            //->where('locationId',$locationId)
            ->first();

        if($rack){
            return $rack->_id;
        }else{
            $rackdata = array (
                    'SKU' => $rackName,
                    'createdDate' => new MongoDate(),
                    'defaultpic' => '',
                    'defaultpictures' => array(),
                    'files' => array (),
                    'itemDescription' => 'New Rack - '.$rackName,
                    'lastUpdate' => new MongoDate(),
                    'locationId' => $locationId,
                    'locationName' => $locationName,
                    'status' => 'active',
                    'tagArray' => array (),
                    'tags' => ''
                );

            $rackId = Rack::insertGetId($rackdata);

            return $rackId;
        }
    }

    public function missingMethod($param = array())
    {
        //print_r($param);
    }

}