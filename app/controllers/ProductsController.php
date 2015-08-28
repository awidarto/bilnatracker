<?php

class ProductsController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Product();
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
            //array('Photos',array('search'=>false,'sort'=>false)),
            array('SKU',array('search'=>true,'sort'=>true)),
            //array('Code',array('search'=>true,'sort'=>true, 'attr'=>array('class'=>'span2'))),
            array('Picture',array('search'=>true,'sort'=>true ,'attr'=>array('class'=>'span2'))),
            array('Description',array('search'=>true,'sort'=>true)),
            array('Series',array('search'=>true,'sort'=>true)),
            array('Price',array('search'=>true,'sort'=>true)),
            array('Disc. Price',array('search'=>true,'sort'=>true)),
            array('Category',array('search'=>true,'sort'=>true,'select'=>Prefs::ExtractProductCategory() )),
            /*
            array('Length / Panjang',array('search'=>true,'sort'=>true)),
            array('Width / Lebar',array('search'=>true,'sort'=>true)),
            array('Height / Tinggi',array('search'=>true,'sort'=>false)),
            array('Diameter',array('search'=>true,'sort'=>false)),
            array('Size Description',array('search'=>true,'sort'=>true)),
            */
            array('Color',array('search'=>true,'sort'=>true)),
            array('Material',array('search'=>true,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Products';

        $this->place_action = 'first';

        $this->additional_filter = View::make('products.addfilter')->render();

        $this->js_additional_param = "aoData.push( { 'name':'categoryFilter', 'value': $('#assigned-product-filter').val() } );";

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            //array('SKU',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'namePic','show'=>true)),
            array('SKU',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            //array('SKU',array('kind'=>'text','callback'=>'dispBar', 'query'=>'like','pos'=>'both','show'=>true)),
            array('SKU',array('kind'=>'text', 'callback'=>'namePic', 'query'=>'like','pos'=>'both','show'=>true)),
            array('itemDescription',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('series',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('priceRegular',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('priceDiscount',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            /*
            array('L',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('W',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('H',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('D',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('sizeDescription',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            */
            array('colour',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('material',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitTag')),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        $categoryFilter = Input::get('categoryFilter');

        if($categoryFilter != ''){
            $this->additional_query = array('category'=>$categoryFilter);
        }

        $this->place_action = 'first';

        return parent::postIndex();
    }

    public function beforeSave($data)
    {
        $defaults = array();

        $files = array();

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];

            for($i = 0 ; $i < count($data['thumbnail_url']);$i++ ){

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                    $defaults['full_url'] = $data['full_url'][$i];
                }

                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];
            }
        }else{
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
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        $cats = Prefs::getProductCategory()->ProductCatToSelection('slug', 'title', false);
        $data['category'] = $cats[$data['categoryLink']];

        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        $defaults = array();

        $files = array();

        if( isset($data['file_id']) && count($data['file_id'])){

            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];


            for($i = 0 ; $i < count($data['file_id']); $i++ ){


                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                $files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                    $defaults['full_url'] = $data['full_url'][$i];
                }

                if($data['brchead'] == $data['file_id'][$i]){
                    $defaults['brchead'] = $data['large_url'][$i];
                }

                if($data['brc1'] == $data['file_id'][$i]){
                    $defaults['brc1'] = $data['large_url'][$i];
                }

                if($data['brc2'] == $data['file_id'][$i]){
                    $defaults['brc2'] = $data['large_url'][$i];
                }

                if($data['brc3'] == $data['file_id'][$i]){
                    $defaults['brc3'] = $data['large_url'][$i];
                }


            }

        }else{

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
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;


        $cats = Prefs::getProductCategory()->ProductCatToSelection('slug', 'title', false);
        $data['category'] = $cats[$data['categoryLink']];


        $unitdata = array_merge(array('id'=>$id),$data);

        //$this->updateStock($unitdata);

        Commerce::updateStock($unitdata);

        unset($data['outlets']);
        unset($data['outletNames']);
        unset($data['addQty']);
        unset($data['adjustQty']);

        return $data;
    }

    public function beforeUpdateForm($population)
    {
        //print_r($population);
        //exit();

        foreach( Prefs::getOutlet()->OutletToArray() as $o){

            $av = Stockunit::where('outletId', $o->_id )
                    ->where('productId', new MongoId($population['_id']) )
                    ->where('status','available')
                    ->count();

            $hd = Stockunit::where('outletId', $o->_id)
                    ->where('productId',new MongoId($population['_id']))
                    ->where('status','hold')
                    ->count();

            $rsv = Stockunit::where('outletId', $o->_id)
                    ->where('productId',new MongoId($population['_id']))
                    ->where('status','reserved')
                    ->count();

            $sld = Stockunit::where('outletId', $o->_id)
                    ->where('productId',new MongoId($population['_id']))
                    ->where('status','sold')
                    ->count();

            $population['stocks'][$o->_id]['available'] = $av;
            $population['stocks'][$o->_id]['hold'] = $hd;
            $population['stocks'][$o->_id]['reserved'] = $rsv;
            $population['stocks'][$o->_id]['sold'] = $sld;
        }

        if( !isset($population['full_url']))
        {
            $population['full_url'] = $population['large_url'];
        }
        return $population;
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'SKU' => 'required',
            'categoryLink' => 'required',
            'itemDescription' => 'required',
            'priceRegular' => 'required',
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'SKU' => 'required',
            'categoryLink' => 'required',
            'itemDescription' => 'required',
            'priceRegular' => 'required',
        );

        return parent::postEdit($id,$data);
    }

    public function postDlxl()
    {

        $this->heads = null;

        $this->fields = array(
                array('SKU',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
                array('itemDescription',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
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
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-times-circle"></i> Delete</span>';
        $edit = '<a href="'.URL::to('products/edit/'.$data['_id']).'"><i class="fa fa-edit"></i> Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['_id']).'" target="new"><i class="fa fa-download"></i> Download</a>';
        $print = '<a href="'.URL::to('brochure/print/'.$data['_id']).'" target="new"><i class="fa fa-print"></i> Print</a>';
        $upload = '<span class="upload" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Upload Picture</span>';
        $inv = '<span class="upinv" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Update Inventory</span>';

        $actions = $edit.'<br />'.$upload.'<br />'.$inv.'<br />'.$delete;
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
        $tags = explode(',',$data['tags']);
        if(is_array($tags) && count($tags) > 0 && $data['tags'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['tags'];
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

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $g['caption'] = ($g['caption'] == '')?$data['propertyId']:$data['propertyId'].' : '.$g['caption'];
                $g['full_url'] = isset($g['full_url'])?$g['full_url']:$g['fileurl'];
                $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['full_url'].'" >';
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;
            return $display;
        }else{
            return $data['SKU'];
        }
    }

    public function dispBar($data)

    {
        $display = HTML::image(URL::to('barcode/'.urlencode($data['SKU'])), $data['SKU'], array('id' => $data['_id'], 'style'=>'width:100px;height:auto;' ));
        $display = '<a href="'.URL::to('barcode/dl/'.urlencode($data['SKU'])).'">'.$display.'</a>';
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

    public function updateStock($data){

        //print_r($data);

        $outlets = $data['outlets'];
        $outletNames = $data['outletNames'];
        $addQty = $data['addQty'];
        $adjustQty = $data['adjustQty'];

        unset($data['outlets']);
        unset($data['outletNames']);
        unset($data['addQty']);
        unset($data['adjustQty']);

        for( $i = 0; $i < count($outlets); $i++)
        {

            $su = array(
                    'outletId'=>$outlets[$i],
                    'outletName'=>$outletNames[$i],
                    'productId'=>$data['id'],
                    'SKU'=>$data['SKU'],
                    'productDetail'=>$data,
                    'status'=>'available',
                    'createdDate'=>new MongoDate(),
                    'lastUpdate'=>new MongoDate()
                );

            if($addQty[$i] > 0){
                for($a = 0; $a < $addQty[$i]; $a++){
                    $su['_id'] = str_random(40);
                    Stockunit::insert($su);
                }
            }

            if($adjustQty[$i] > 0){
                $td = Stockunit::where('outletId',$outlets[$i])
                    ->where('productId',$data['id'])
                    ->where('SKU', $data['SKU'])
                    ->where('status','available')
                    ->orderBy('createdDate', 'asc')
                    ->take($adjustQty[$i])
                    ->get();

                foreach($td as $d){
                    $d->status = 'deleted';
                    $d->lastUpdate = new MongoDate();
                    $d->save();
                }
            }
        }


    }

}
