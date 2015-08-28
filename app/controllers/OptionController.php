<?php

class OptionController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //Breadcrumb::addBreadcrumb('Home',URL::to('/'));
        //Breadcrumb::addBreadcrumb('Option', URL::to('option'));
        //Breadcrumb::setSeperator('');

        $this->model = new Option();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {
        Options::refresh();

        $this->heads = array(
            array('Option',array('search'=>true,'sort'=>true ,'attr'=>array('class'=>'span2'))),
            array('Var Name',array('search'=>true,'sort'=>true ,'attr'=>array('class'=>'span2'))),
            array('Value',array('search'=>true,'sort'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Option';

        //$this->crumb = Breadcrumb::generate();

        $this->place_action = 'first';

        $this->can_add = false;

        $this->modal_sets = View::make( strtolower( $this->controller_name ).'.modal')->render();

        $this->js_table_event = View::make(strtolower($this->controller_name).'.tableevent')->render();

        Breadcrumbs::addCrumb('System',URL::to( strtolower($this->controller_name) ));

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('label',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('varname',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('value',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
        );

        $this->place_action = 'first';

        return parent::postIndex();
    }

    public function getAdd(){
        //Breadcrumb::addBreadcrumb('New Payable', 'add');
        //$this->crumb = Breadcrumb::generate();

        return parent::getAdd();
    }

    public function getEdit($id){
        //Breadcrumb::addBreadcrumb('Update Document', 'edit');
        //$this->crumb = Breadcrumb::generate();

        return parent::getEdit($id);
    }

    public function beforeUpdateForm($population)
    {
        return $population;
    }


    public function postDlxl()
    {

        $this->heads = null;

        $this->fields = array(
                array('SKU',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
                array('itemDescription',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
                array('series',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('itemGroup',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('L',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('W',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('H',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('D',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('colour',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('material',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
                array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true))
        );

        return parent::postDlxl();
    }

    public function getImport(){

        $this->importkey = 'SKU';

        return parent::getImport();
    }

    public function postUploadimport()
    {
        $this->importkey = 'SKU';

        return parent::postUploadimport();
    }

    public function beforeImportCommit($data)
    {
        $defaults = array();

        $files = array();

        // set new sequential ID


        $data['priceRegular'] = new MongoInt32($data['priceRegular']);

        $data['thumbnail_url'] = array();
        $data['large_url'] = array();
        $data['medium_url'] = array();
        $data['full_url'] = array();
        $data['delete_type'] = array();
        $data['delete_url'] = array();
        $data['filename'] = array();
        $data['filesize'] = array();
        $data['temp_dir'] = array();
        $data['filetype'] = array();
        $data['fileurl'] = array();
        $data['file_id'] = array();
        $data['caption'] = array();

        $data['defaultpic'] = '';
        $data['brchead'] = '';
        $data['brc1'] = '';
        $data['brc2'] = '';
        $data['brc3'] = '';


        $data['defaultpictures'] = array();
        $data['files'] = array();

        return $data;
    }


    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-trash"></i> Delete</span>';
        $edit = '<a href="'.URL::to('option/edit/'.$data['_id']).'"><i class="fa fa-edit"></i> Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['_id']).'" target="new"><i class="fa fa-download"></i> Download</a>';
        $print = '<a href="'.URL::to('brochure/print/'.$data['_id']).'" target="new"><i class="fa fa-print"></i> Print</a>';
        $upload = '<span class="upload" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Upload File</span>';

        $actions = $edit;
        return $actions;
    }

    public function extractCategory()
    {
        $category = Product::distinct('category')->get()->toArray();
        $cats = array(''=>'All');

        //print_r($category);
        foreach($category as $cat){
            $cats[$cat[0]] = $cat[0];
        }

        return $cats;
    }

    public function splitTag($data){
        $tags = explode(',',$data['docTag']);
        if(is_array($tags) && count($tags) > 0 && $data['docTag'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docTag'];
        }
    }

    public function splitShare($data){
        $tags = explode(',',$data['docShare']);
        if(is_array($tags) && count($tags) > 0 && $data['docShare'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docShare'];
        }
    }

    public function namePic($data)
    {
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        if(isset($data['files']) && count($data['files'])){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = false;

            if(preg_match('/image/', $gdata['filetype'])){
                $thumbnail_url = $gdata['thumbnail_url'];
            }else{
                $thumbnail_url = URL::to('images/no-pic.jpg');
            }

            foreach($data['files'] as $g){
                if(preg_match('/image/', $g['filetype'])){
                    $g['caption'] = ($g['caption'] == '')?'':$g['caption'];
                    $g['full_url'] = isset($g['full_url'])?$g['full_url']:$g['fileurl'];
                    $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['full_url'].'" >';
                }
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;

            return $display;
        }else{
            return $data['SKU'];
        }
    }

    public function dispBar($data)

    {
        $display = HTML::image(URL::to('barcode/'.$data['SKU']), $data['SKU'], array('id' => $data['_id'], 'style'=>'width:100px;height:auto;' ));
        $display = '<a href="'.URL::to('barcode/dl/'.$data['SKU']).'">'.$display.'</a>';
        return $display.'<br />'.$data['SKU'];
    }


    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
