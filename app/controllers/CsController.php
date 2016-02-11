<?php

class CsController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Shipment();
        //$this->model = DB::collection('documents');

    }

    public function getIndex()
    {

        $this->heads = array(
            array('Delivery Date',array('search'=>false,'sort'=>true,'datetimerange'=>true)),
            array('Order ID / Shipping Address',array('search'=>false,'sort'=>false)),
            array('City',array('search'=>false,'sort'=>true)),
            array('Status',array('search'=>false,'sort'=>true)),
            array('Action',array('search'=>false, 'attr'=>array( 'style'=>'min-width:50px;width:50px;' ),'sort'=>true)),
        );

        $heads_two = array(

            array('#',array('search'=>false,'sort'=>false)),
            array('Timestamp',array('search'=>false,'sort'=>true,'datetimerange'=>true)),
            array('Order ID',array('search'=>false,'sort'=>false)),
            array('FF ID',array('search'=>false,'sort'=>false)),
            array('Courier',array('search'=>false,'sort'=>false)),
            array('Device',array('search'=>false,'sort'=>false)),
            array('Updated By',array('search'=>false,'sort'=>false)),
            array('Status',array('search'=>false,'sort'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->can_add = false;

        $this->place_action = 'none';

        $this->is_additional_action = false;

        $this->additional_table_param = array(
                'title_one'=>'SHIPMENT ORDER',
                'title_two'=>'Timeline',
                'ajax_url_one'=>URL::to('cs'),
                'ajax_url_two'=>URL::to('cs/timeline'),
                'secondary_heads'=>$heads_two,
                'table_search_1'=>true,
                'table_search_2'=>false,
                'additional_filter_two'=>View::make('cs.addfilter2')->render(),
                'before_table_layout_2'=>'<h3 style="margin-top:10px !important;" >Detail Status Order</h3><div id="last-order-status"></div>'

            );

        $this->js_table_event = View::make('cs.jstabevent')->render();

        $this->js_additional_param = "aoData.push( { 'name':'orderSearch', 'value': $('#order-search').val() },
                                        { 'name':'orderId', 'value': $('#order-id').val() },
                                        { 'name':'orderFf', 'value': $('#order-ff').val() }
                                         );";

        $this->additional_filter = View::make('cs.addfilter')->render();

        $this->show_select = false;

        $this->title = 'CS Dashboard';

        $this->table_view = 'tables.cs';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('pick_up_date',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('no_sales_order',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true, 'callback'=>'shipmentOrder' ,'multi'=>array('no_sales_order','consignee_olshop_addr'), 'rel'=>'OR' )),
            array('consignee_olshop_city',array('kind'=>'text' , 'query'=>'like', 'pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','callback'=>'statusList','query'=>'like','pos'=>'both','show'=>true, 'multi'=>array('status','warehouse_status','pickup_status'), 'multirel'=>'OR'  )),
            array('pick_up_date',array('kind'=>'text','query'=>'like','pos'=>'both','callback'=>'statusButton','show'=>true)),
        );

        $this->place_action = 'none';

        $this->def_order_by = 'pick_up_date';

        $this->def_order_dir = 'desc';

        $this->show_select = false;

        return parent::postIndex();
    }


    public function postTimeline()
    {

        $this->fields = array(
            array('timestamp',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('object.no_sales_order',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('object.fulfillment_code',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('object.courier_name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('object.device_name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('actor',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('object.status',array('kind'=>'text','query'=>'like','pos'=>'both', 'callback'=>'objStatusList' ,'show'=>true)),
        );

        $this->place_action = 'none';

        $this->def_order_by = 'timestamp';

        $this->def_order_dir = 'desc';

        $this->model = new Shipmentlog();

        $this->show_select = false;

        return parent::postIndex();
    }

    public function postLast(){

        $in = Input::get();
        $orderid = trim($in['orderId']);
        $orderff = trim($in['orderFf']);
        $delivery_id = trim($in['delivery_id']);

        if(isset($in['delivery_id'])){
            $order = Shipment::where('fulfillment_code','=',$orderff)
                            ->where('no_sales_order','=',$orderid)
                            ->where('delivery_id','=',$delivery_id)
                            ->orderBy('pick_up_date','desc')
                            ->first();
        }else{
            $order = Shipment::where('fulfillment_code','=',$orderff)
                            ->where('no_sales_order','=',$orderid)
                            ->orderBy('pick_up_date','desc')
                            ->first();
        }

        $statuses = array();

        if($order){

            if($order->logistic_type == 'external'){

                $conf = Config::get('cs.'.$order->consignee_olshop_cust);
                if(!isset($conf['status'])){
                    $conf = Config::get('cs.default');
                }

                $mdl = Threeplstatuses::where($conf['awb'],'=', $order->awb);

                foreach($conf['group'] as $g){
                    $mdl = $mdl->groupBy($g);
                }

                $mdl = $mdl->orderBy($conf['order'],'desc');

                $statuses = $mdl->take(10)
                                ->skip(0)
                                ->get($conf['get']);

                $statuses = $statuses->toArray();

                //print_r($statuses);

            }

            $order->picList = $this->picList($order);
        }





        return View::make('cs.lastdetail')
                    ->with('order',$order)
                    ->with('status',$statuses)
                    ->render();
    }

    public function SQL_additional_query($model)
    {

        $in = Input::get();

        if($model instanceOf Shipment){
            $search = trim($in['orderSearch']);

            if( $search == ''){
                $model = $model->where('no_sales_order','=','noid');
                return $model;
            }

            $search_array = explode(',',$search);

            foreach($search_array as $s){
                $s = trim($s);
                $model = $model->orWhere(function($q) use($s){
                    $q->where('no_sales_order','=',$s)
                        ->orWhere('no_sales_order','regexp','/'.$s.'/i')
                        ->orWhere('consignee_olshop_orderid','regexp','/'.$s.'/i')
                        ->orWhere('consignee_olshop_orderid','=',$s)
                        ->orWhere('awb','regexp','/'.$s.'/i')
                        ->orWhere('awb','=',$s)
                        ->orWhere('consignee_olshop_addr','regexp','/'.$s.'/i');
                });
            }

            $model = $model->orderBy('delivered_time','desc')
                        ->orderBy('no_sales_order','desc')
                        ->orderBy('pick_up_date','desc');

        }elseif($model instanceOf Shipmentlog){

            $orderid = trim($in['orderId']);
            $orderff = trim($in['orderFf']);

            $model = $model->where('object.fulfillment_code','=',$orderff)
                            ->where('object.no_sales_order','=',$orderid)
                            //->groupBy('actor')
                            ->orderBy('timestamp','desc');

        }

        return $model;

    }

    public function rows_post_process($rows, $aux = null){

        if($this->model instanceOf Shipmentlog){
            $status = '';

            $status_idx = 7;

            $rows2 = array();
            if(count($rows) > 0){

                for($i = 0; $i < count($rows); $i++){
                    //print $rows[$i][$status_idx];
                    if($rows[$i][$status_idx] != $status){
                        $rows2[] = $rows[$i];
                    }
                    $status = $rows[$i][$status_idx];
                }
            }

            return $rows2;

        }



        //print_r($rows);






        return $rows;

    }


    public function statusButton($data)
    {
        return '<div style="display:inline-block;" class="order-detail action" data-deliveryid="'.$data['delivery_id'].'" data-order="'.$data['no_sales_order'].'" data-ff="'.$data['fulfillment_code'].'" >View Detail <i class="fa fa-chevron-right pull-right order-detail action" data-deliveryid="'.$data['delivery_id'].'" data-order="'.$data['no_sales_order'].'" data-ff="'.$data['fulfillment_code'].'" ></i></div>';
    }

    public function shipmentOrder($data)
    {
        return '<h3>'.$data['no_sales_order'].'</h3><b>'.$data['consignee_olshop_name'].'</b><br />'.$data['consignee_olshop_addr'];
    }

    public function statusList($data)
    {
        $slist = array(
            Prefs::colorizestatus($data['status'],'delivery'),
            //Prefs::colorizestatus($data['courier_status'],'courier'),
            //Prefs::colorizestatus($data['pickup_status'],'pickup'),
            //Prefs::colorizestatus($data['warehouse_status'],'warehouse')
        );


        //return Prefs::colorizestatus($data['status'],'delivery');

        return implode('<br />', $slist);
        //return '<span class="orange white-text">'.$data['status'].'</span><br /><span class="brown">'.$data['pickup_status'].'</span><br /><span class="green">'.$data['courier_status'].'</span><br /><span class="maroon">'.$data['warehouse_status'].'</span>';
    }

    public function objStatusList($data)
    {
        $slist = array(
            Prefs::colorizestatus($data['object.status'],'delivery'),
            //Prefs::colorizestatus($data['object.courier_status'],'courier'),
            //Prefs::colorizestatus($data['pickup_status'],'pickup'),
            //Prefs::colorizestatus($data['object.warehouse_status'],'warehouse')
        );


        //return Prefs::colorizestatus($data['status'],'delivery');

        return implode('<br />', $slist);
        //return '<span class="orange white-text">'.$data['status'].'</span><br /><span class="brown">'.$data['pickup_status'].'</span><br /><span class="green">'.$data['courier_status'].'</span><br /><span class="maroon">'.$data['warehouse_status'].'</span>';
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
        $data['productDetail']['files'] = $files;

        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        $defaults = array();

        $unitdata = array_merge(array('id'=>$id),$data);

        $this->updateStock($unitdata);

        unset($data['outlets']);
        unset($data['outletNames']);
        unset($data['addQty']);
        unset($data['adjustQty']);

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
            'category' => 'required',
            'itemDescription' => 'required',
            'priceRegular' => 'required',
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'SKU' => 'required',
            'category' => 'required',
            'itemDescription' => 'required',
            'priceRegular' => 'required',
        );

        return parent::postEdit($id,$data);
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
        $edit = '<a href="'.URL::to('products/edit/'.$data['_id']).'"><i class="fa fa-edit"></i> Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['_id']).'" target="new"><i class="fa fa-download"></i> Download</a>';
        $print = '<a href="'.URL::to('brochure/print/'.$data['_id']).'" target="new"><i class="fa fa-print"></i> Print</a>';
        $upload = '<span class="upload" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Upload Picture</span>';

        $actions = $edit.'<br />'.$upload.'<br />'.$delete;
        $actions = '';
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

    public function eventResult($data)
    {
        if(json_decode($data['result'])){
            return 'more';
        }else{
            return $data['result'];
        }
    }

    public function buttonStatus($data)
    {
        if($data['approvalStatus'] == 'verified'){
            return '<span class="btn btn-success">'.$data['approvalStatus'].'</span>';
        }else{
            return '<span class="btn btn-info">'.$data['approvalStatus'].'</span>';
        }
    }

    public function assetName($data)
    {
        $asset = Asset::find($data['assetId']);

        if($asset){
            return '<a href="'.URL::to('asset/detail/'.$data['assetId']).'" >'.$asset->SKU.'</a>';
        }else{
            return '-';
        }

    }

    public function picList($data)
    {
        $data = $data->toArray();

        $pics = Uploaded::where('parent_id','=', $data['delivery_id'] )->get();

        $glinks = '';

        $thumbnail_url = '';

        if($pics){
            if(count($pics) > 0){
                $display = '<ul class="pic_list">';
                foreach($pics as $g){
                    $thumbnail_url = $g->thumbnail_url;
                    $display .= '<li>';
                    $display .= HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id']));
                    $display .= '</li>';
                    $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g->name.'" value="'.$g->full_url.'" />';
                }

                //$display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;
                $display .= '</ul>';

                $display .= $glinks;

                return $display;
            }else{
                return 'No Picture';
            }
        }else{
            return 'No Picture';
        }
    }


    public function namePic($data)
    {
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        //$data = $data->toArray();

        //print_r($data);

        //exit();

        if(isset($data['productDetail']['files']) && count($data['productDetail']['files'])){
            $glinks = '';

            $gdata = $data['productDetail']['files'][$data['productDetail']['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['productDetail']['files'] as $g){
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
        $code = $data['unitId'];
        $display = HTML::image(URL::to('barcode/'.$code), $data['SKU'], array('id' => $data['_id'], 'style'=>'width:100px;height:auto;' ));
        $display = '<a href="'.URL::to('barcode/dl/'.$code).'">'.$display.'</a>';
        return $display.'<br />'.$data['SKU'];
    }

    public function shortunit($data){
        return substr($data['unitId'], -10);
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productDetail']['productName']);
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
