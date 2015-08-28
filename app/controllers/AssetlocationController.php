<?php

class AssetlocationController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));
        $this->title = 'Location';

        $this->model = new Assetlocation();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $this->heads = array(
            array('Name',array('search'=>false,'sort'=>false)),
            array('Code',array('search'=>false,'sort'=>false)),
            array('Venue',array('search'=>true,'sort'=>true)),
            array('Address',array('search'=>true,'sort'=>true)),
            array('Category',array('search'=>true,'sort'=>true,'select'=>Config::get('asset.location_category') )),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'daterange'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'daterange'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Location';
        Breadcrumbs::addCrumb('Assets',URL::to( strtolower($this->controller_name) ));

        $this->additional_filter = View::make('assetlocation.addfilter')->render();

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('_id',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'dispBar','attr'=>array('class'=>'expander'),'show'=>true)),
            array('venue',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('address',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function beforeSave($data)
    {

        return $data;
    }

    public function beforeUpdate($id,$data)
    {

        return $data;
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'name' => 'required',
            'venue' => 'required',
            'address' => 'required',
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'name' => 'required',
            'venue' => 'required',
            'address' => 'required',
        );

        return parent::postEdit($id,$data);
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('assetlocation/edit/'.$data['_id']).'"><i class="fa fa-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
        return $actions;
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

    public function dispBar($data)

    {
        $display = HTML::image(URL::to('qr/'.urlencode(base64_encode($data['_id']))), $data['_id'], array('id' => $data['_id'], 'style'=>'width:100px;height:auto;' ));
        //$display = '<a href="'.URL::to('barcode/dl/'.urlencode($data['SKU'])).'">'.$display.'</a>';
        return $display.'<br />'.$data['SKU'];
    }


    public function namePic($data)
    {
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        if(isset($data['files']) && count($data['files'])){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['fileurl'].'" >';
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','id' => $data['_id'])).$glinks;
            return $display;
        }else{
            return $name;
        }
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

    public function getPrintlabel($sessionname, $printparam, $format = 'html' )
    {
        $pr = explode(':',$printparam);

        $columns = $pr[0];
        $resolution = $pr[1];
        $cell_width = $pr[2];
        $cell_height = $pr[3];
        $margin_right = $pr[4];
        $margin_bottom = $pr[5];
        $font_size = $pr[6];
        $code_type = $pr[7];
        $left_offset = $pr[8];
        $top_offset = $pr[9];

        $session = Printsession::find($sessionname)->toArray();
        $labels = Assetlocation::whereIn('_id', $session)->get()->toArray();

        $skus = array();
        foreach($labels as $l){
            $skus[] = $l['_id'];
        }

        $skus = array_unique($skus);

        $products = Assetlocation::whereIn('_id',$skus)->get()->toArray();

        $plist = array();
        foreach($products as $product){
            $plist[$product['_id']] = $product;
        }

        return View::make('assetlocation.printlabel')
            ->with('columns',$columns)
            ->with('resolution',$resolution)
            ->with('cell_width',$cell_width)
            ->with('cell_height',$cell_height)
            ->with('margin_right',$margin_right)
            ->with('margin_bottom',$margin_bottom)
            ->with('font_size',$font_size)
            ->with('code_type',$code_type)
            ->with('left_offset', $left_offset)
            ->with('top_offset', $top_offset)
            ->with('products',$plist)
            ->with('labels', $labels);
    }


    public function getViewpics($id)
    {

    }

    public function postDlxl()
    {
        $this->heads = null;

        $this->fields = array(
            array('name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('slug',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('venue',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('address',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('phone',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('latitude',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('longitude',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('description',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true))
        );

        return parent::postDlxl();
    }

    public function getImport(){

        $this->importkey = 'name';

        return parent::getImport();
    }

    public function postUploadimport()
    {
        $this->importkey = 'name';

        return parent::postUploadimport();
    }


}
