<?php

class IncomingController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Shipment();
        //$this->model = DB::collection('documents');
        $this->title = 'Incoming Order';

    }

    public function getDetail($id)
    {
        $_id = new MongoId($id);
        $history = History::where('historyObject._id',$_id)->where('historyObjectType','asset')
                        ->orderBy('historyTimestamp','desc')
                        ->orderBy('historySequence','desc')
                        ->get();
        $diffs = array();

        foreach($history as $h){
            $h->date = date( 'Y-m-d H:i:s', $h->historyTimestamp->sec );
            $diffs[$h->date][$h->historySequence] = $h->historyObject;
        }

        $history = History::where('historyObject._id',$_id)->where('historyObjectType','asset')
                        ->where('historySequence',0)
                        ->orderBy('historyTimestamp','desc')
                        ->get();

        $tab_data = array();
        foreach($history as $h){
                $apv_status = Assets::getApprovalStatus($h->approvalTicket);
                if($apv_status == 'pending'){
                    $bt_apv = '<span class="btn btn-info change-approval '.$h->approvalTicket.'" data-id="'.$h->approvalTicket.'" >'.$apv_status.'</span>';
                }else if($apv_status == 'verified'){
                    $bt_apv = '<span class="btn btn-success" >'.$apv_status.'</span>';
                }else{
                    $bt_apv = '';
                }

                $d = date( 'Y-m-d H:i:s', $h->historyTimestamp->sec );
                $tab_data[] = array(
                    $d,
                    $h->historyAction,
                    $h->historyObject['itemDescription'],
                    ($h->historyAction == 'new')?'NA':$this->objdiff( $diffs[$d] ),
                    $bt_apv
                );
        }

        $header = array(
            'Modified',
            'Event',
            'Name',
            'Diff',
            'Approval'
            );

        $attr = array('class'=>'table', 'id'=>'transTab', 'style'=>'width:100%;', 'border'=>'0');
        $t = new HtmlTable($tab_data, $attr, $header);
        $itemtable = $t->build();

        $asset = Shipment::find($id);

        Breadcrumbs::addCrumb('Ad Assets',URL::to( strtolower($this->controller_name) ));
        Breadcrumbs::addCrumb('Detail',URL::to( strtolower($this->controller_name).'/detail/'.$asset->_id ));
        Breadcrumbs::addCrumb($asset->SKU,URL::to( strtolower($this->controller_name) ));

        return View::make('history.table')
                    ->with('a',$asset)
                    ->with('title','Asset Detail '.$asset->itemDescription )
                    ->with('table',$itemtable);
    }

    public function getIndex()
    {


        $this->heads = Config::get('jex.default_heads');

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Incoming Order';

        $this->place_action = 'first';

        $this->show_select = true;

        Breadcrumbs::addCrumb('Shipment Order',URL::to( strtolower($this->controller_name) ));

        $this->additional_filter = View::make(strtolower($this->controller_name).'.addfilter')
                                        ->with('submit_url','gl')
                                        ->with('ajaxawbdlxl','incoming/awbdlxl')
                                        ->with('importawburl','incoming/importawb')
                                        ->render();

        //$this->js_additional_param = "aoData.push( { 'name':'acc-period-to', 'value': $('#acc-period-to').val() }, { 'name':'acc-period-from', 'value': $('#acc-period-from').val() }, { 'name':'acc-code-from', 'value': $('#acc-code-from').val() }, { 'name':'acc-code-to', 'value': $('#acc-code-to').val() }, { 'name':'acc-company', 'value': $('#acc-company').val() } );";

        $this->product_info_url = strtolower($this->controller_name).'/info';

        $this->can_add = false;

        /*
        $this->column_styles = '{ "sClass": "column-amt", "aTargets": [ 8 ] },
                    { "sClass": "column-amt", "aTargets": [ 9 ] },
                    { "sClass": "column-amt", "aTargets": [ 10 ] }';
        */

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = Config::get('jex.default_fields');

        /*
        $categoryFilter = Input::get('categoryFilter');
        if($categoryFilter != ''){
            $this->additional_query = array('shopcategoryLink'=>$categoryFilter, 'group_id'=>4);
        }
        */

        $db = Config::get('jayon.main_db');

        $this->def_order_by = 'ordertime';
        $this->def_order_dir = 'desc';
        $this->place_action = 'first';
        $this->show_select = true;

        $this->sql_key = 'delivery_id';
        $this->sql_table_name = Config::get('jayon.incoming_delivery_table');
        $this->sql_connection = 'mysql';

        return parent::tableResponder();
    }

    public function getStatic()
    {

        $this->heads = Config::get('jex.default_heads');

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Incoming Order';


        Breadcrumbs::addCrumb('Cost Report',URL::to( strtolower($this->controller_name) ));

        //$this->additional_filter = View::make(strtolower($this->controller_name).'.addfilter')->with('submit_url','gl/static')->render();

        //$this->js_additional_param = "aoData.push( { 'name':'acc-period-to', 'value': $('#acc-period-to').val() }, { 'name':'acc-period-from', 'value': $('#acc-period-from').val() }, { 'name':'acc-code-from', 'value': $('#acc-code-from').val() }, { 'name':'acc-code-to', 'value': $('#acc-code-to').val() }, { 'name':'acc-company', 'value': $('#acc-company').val() } );";

        $this->product_info_url = strtolower($this->controller_name).'/info';

        $this->printlink = strtolower($this->controller_name).'/print';

        //table generator part

        $this->fields = Config::get('jex.default_fields');

        $db = Config::get('jayon.main_db');

        $this->def_order_by = 'ordertime';
        $this->def_order_dir = 'desc';
        $this->place_action = 'none';
        $this->show_select = false;

        $this->sql_key = 'delivery_id';
        $this->sql_table_name = Config::get('jayon.incoming_delivery_table');
        $this->sql_connection = 'mysql';

        $this->responder_type = 's';

        return parent::printGenerator();
    }

    public function getPrint()
    {

        $this->fields = Config::get('jex.default_heads');

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Incoming Order';

        Breadcrumbs::addCrumb('Cost Report',URL::to( strtolower($this->controller_name) ));

        //$this->additional_filter = View::make(strtolower($this->controller_name).'.addfilter')->with('submit_url','gl/static')->render();

        //$this->js_additional_param = "aoData.push( { 'name':'acc-period-to', 'value': $('#acc-period-to').val() }, { 'name':'acc-period-from', 'value': $('#acc-period-from').val() }, { 'name':'acc-code-from', 'value': $('#acc-code-from').val() }, { 'name':'acc-code-to', 'value': $('#acc-code-to').val() }, { 'name':'acc-company', 'value': $('#acc-company').val() } );";

        $this->product_info_url = strtolower($this->controller_name).'/info';

        $this->printlink = strtolower($this->controller_name).'/print';

        //table generator part

        $this->fields = Config::get('jex.default_fields');

        $db = Config::get('jayon.main_db');

        $this->def_order_by = 'ordertime';
        $this->def_order_dir = 'desc';
        $this->place_action = 'none';
        $this->show_select = false;

        $this->sql_key = 'delivery_id';
        $this->sql_table_name = Config::get('jayon.incoming_delivery_table');
        $this->sql_connection = 'mysql';

        $this->responder_type = 's';

        return parent::printPage();
    }

    public function SQL_make_join($model)
    {
        //$model->with('coa');

        //PERIOD',TRANS_DATETIME,VCHR_NUM,ACC_DESCR,DESCRIPTN',TREFERENCE',CONV_CODE,AMOUNT',AMOUNT',DESCRIPTN'
        /*
        $model = $model->select('j10_a_salfldg.*','j10_acnt.DESCR as ACC_DESCR')
            ->leftJoin('j10_acnt', 'j10_a_salfldg.ACCNT_CODE', '=', 'j10_acnt.ACNT_CODE' );
            */
        return $model;
    }

    public function SQL_additional_query($model)
    {
        $in = Input::get();

        $period_from = Input::get('acc-period-from');
        $period_to = Input::get('acc-period-to');

        $db = Config::get('lundin.main_db');

        $company = Input::get('acc-company');

        $company = strtolower($company);

        $txtab = Config::get('jayon.incoming_delivery_table');

        $model = $model->where(function($query){

                    $query->where('bucket','=',Config::get('jayon.bucket_incoming'))
                    ->where(function($qs){
                        $qs->where(function($q){
                                $q->where('pending_count','=',0)
                                    ->where('status','=', Config::get('jayon.trans_status_new') );
                            })
                            ->orWhere('status','=', Config::get('jayon.trans_status_confirmed') )
                            ->orWhere('status','=', Config::get('jayon.trans_status_tobeconfirmed') )
                            ->where('status','not regexp','/*assigned/');
                    });

            })
            ->orderBy('created_at','desc');

            /*
            ->where($this->config->item('incoming_delivery_table').'.pending_count < ',1)
            ->where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_new'))
            ->or_where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_confirmed'))
            ->or_where($this->config->item('incoming_delivery_table').'.status',$this->config->item('trans_status_tobeconfirmed'))
            ->not_like($this->config->item('incoming_delivery_table').'.status','assigned','before')
            */

        //print_r($in);


        //$model = $model->where('group_id', '=', 4);

        return $model;

    }

    public function SQL_before_paging($model)
    {
        /*
        $m_original_amount = clone($model);
        $m_base_amount = clone($model);

        $aux['total_data_base'] = $m_base_amount->sum('OTHER_AMT');
        $aux['total_data_converted'] = $m_original_amount->sum('AMOUNT');
        */
        //$this->aux_data = $aux;

        $aux = array();
        return $aux;
        //print_r($this->aux_data);

    }

    public function rows_post_process($rows, $aux = null){

        //print_r($this->aux_data);
        /*
        $total_base = 0;
        $total_converted = 0;
        $end = 0;

        $br = array_fill(0, $this->column_count(), '');


        $nrows = array();

        $subhead1 = '';
        $subhead2 = '';
        $subhead3 = '';

        $seq = 0;

        $subamount1 = 0;
        $subamount2 = 0;

        if(count($rows) > 0){

            for($i = 0; $i < count($rows);$i++){

                //print_r($rows[$i]['extra']);

                if($subhead1 == '' || $subhead1 != $rows[$i][1] || $subhead2 != $rows[$i][4] ){

                    $headline = $br;
                    if($subhead1 != $rows[$i][1]){
                        $headline[1] = '<b>'.$rows[$i]['extra']['PERIOD'].'</b>';
                    }else{
                        $headline[1] = '';
                    }

                    $headline[4] = '<b>'.$rows[$i]['extra']['ACCNT_CODE'].'</b>';
                    $headline['extra']['rowclass'] = 'row-underline';

                    if($subhead1 != ''){
                        $amtline = $br;
                        $amtline[8] = '<b>'.Ks::idr($subamount1).'</b>';
                        $amtline[10] = '<b>'.Ks::idr($subamount2).'</b>';
                        $amtline['extra']['rowclass'] = 'row-doubleunderline row-overline';

                        $nrows[] = $amtline;
                        $subamount1 = 0;
                        $subamount2 = 0;
                    }

                    $subamount1 += $rows[$i]['extra']['OTHER_AMT'];
                    $subamount2 += $rows[$i]['extra']['AMOUNT'];

                    $nrows[] = $headline;

                    $seq = 1;
                    $rows[$i][0] = $seq;

                    $rows[$i][8] = ($rows[$i]['extra']['CONV_CODE'] == 'IDR')?Ks::idr($rows[$i][8]):'';
                    $rows[$i][9] = ($rows[$i]['extra']['CONV_CODE'] == 'IDR')?Ks::dec2($rows[$i][9]):'';
                    $rows[$i][10] = Ks::usd($rows[$i][10]);

                    $nrows[] = $rows[$i];
                }else{
                    $seq++;
                    $rows[$i][0] = $seq;

                    $rows[$i][8] = ($rows[$i]['extra']['CONV_CODE'] == 'IDR')?Ks::idr($rows[$i][8]):'';
                    $rows[$i][9] = ($rows[$i]['extra']['CONV_CODE'] == 'IDR')?Ks::dec2($rows[$i][9]):'';
                    $rows[$i][10] = Ks::usd($rows[$i][10]);

                    $nrows[] = $rows[$i];


                }

                $total_base += doubleval( $rows[$i][8] );
                $total_converted += doubleval($rows[$i][10]);
                $end = $i;

                $subhead1 = $rows[$i][1];
                $subhead2 = $rows[$i][4];
            }

            // show total Page
            if($this->column_count() > 0){

                $tb = $br;
                $tb[1] = 'Total Page';
                $tb[8] = Ks::idr($total_base);
                $tb[10] = Ks::usd($total_converted);

                $nrows[] = $tb;

                if(!is_null($this->aux_data)){
                    $td = $br;
                    $td[1] = 'Total';
                    $td[8] = Ks::idr($aux['total_data_base']);
                    $td[10] = Ks::usd($aux['total_data_converted']);
                    $nrows[] = $td;
                }

            }

            return $nrows;

        }else{

            return $rows;

        }
        */

        // show total queried

        return $rows;

    }


    public function beforeSave($data)
    {

        if( isset($data['file_id']) && count($data['file_id'])){

            $mediaindex = 0;

            for($i = 0 ; $i < count($data['thumbnail_url']);$i++ ){

                $index = $mediaindex;

                $data['files'][ $data['file_id'][$i] ]['ns'] = $data['ns'][$i];
                $data['files'][ $data['file_id'][$i] ]['role'] = $data['role'][$i];
                $data['files'][ $data['file_id'][$i] ]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['large_url'] = $data['large_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['medium_url'] = $data['medium_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['full_url'] = $data['full_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['delete_type'] = $data['delete_type'][$i];
                $data['files'][ $data['file_id'][$i] ]['delete_url'] = $data['delete_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['filename'] = $data['filename'][$i];
                $data['files'][ $data['file_id'][$i] ]['filesize'] = $data['filesize'][$i];
                $data['files'][ $data['file_id'][$i] ]['temp_dir'] = $data['temp_dir'][$i];
                $data['files'][ $data['file_id'][$i] ]['filetype'] = $data['filetype'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_image'] = $data['is_image'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_audio'] = $data['is_audio'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_video'] = $data['is_video'][$i];
                $data['files'][ $data['file_id'][$i] ]['fileurl'] = $data['fileurl'][$i];
                $data['files'][ $data['file_id'][$i] ]['file_id'] = $data['file_id'][$i];
                $data['files'][ $data['file_id'][$i] ]['sequence'] = $mediaindex;

                $mediaindex++;

                $data['defaultpic'] = $data['file_id'][0];
                $data['defaultpictures'] = $data['files'][$data['file_id'][0]];

            }

        }else{

            $data['defaultpic'] = '';
            $data['defaultpictures'] = '';
        }

        $cats = Prefs::getShopCategory()->shopcatToSelection('slug', 'name', false);
        $data['shopcategory'] = $cats[$data['shopcategoryLink']];

            $data['shortcode'] = str_random(5);

        return $data;
    }

    public function beforeUpdate($id,$data)
    {

        if( isset($data['file_id']) && count($data['file_id'])){

            $mediaindex = 0;

            for($i = 0 ; $i < count($data['thumbnail_url']);$i++ ){

                $index = $mediaindex;

                $data['files'][ $data['file_id'][$i] ]['ns'] = $data['ns'][$i];
                $data['files'][ $data['file_id'][$i] ]['role'] = $data['role'][$i];
                $data['files'][ $data['file_id'][$i] ]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['large_url'] = $data['large_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['medium_url'] = $data['medium_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['full_url'] = $data['full_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['delete_type'] = $data['delete_type'][$i];
                $data['files'][ $data['file_id'][$i] ]['delete_url'] = $data['delete_url'][$i];
                $data['files'][ $data['file_id'][$i] ]['filename'] = $data['filename'][$i];
                $data['files'][ $data['file_id'][$i] ]['filesize'] = $data['filesize'][$i];
                $data['files'][ $data['file_id'][$i] ]['temp_dir'] = $data['temp_dir'][$i];
                $data['files'][ $data['file_id'][$i] ]['filetype'] = $data['filetype'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_image'] = $data['is_image'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_audio'] = $data['is_audio'][$i];
                $data['files'][ $data['file_id'][$i] ]['is_video'] = $data['is_video'][$i];
                $data['files'][ $data['file_id'][$i] ]['fileurl'] = $data['fileurl'][$i];
                $data['files'][ $data['file_id'][$i] ]['file_id'] = $data['file_id'][$i];
                $data['files'][ $data['file_id'][$i] ]['sequence'] = $mediaindex;

                $mediaindex++;

                $data['defaultpic'] = $data['file_id'][0];
                $data['defaultpictures'] = $data['files'][$data['file_id'][0]];

            }

        }else{

            $data['defaultpic'] = '';
            $data['defaultpictures'] = '';
        }

        if(!isset($data['shortcode']) || $data['shortcode'] == ''){
            $data['shortcode'] = str_random(5);
        }

        $cats = Prefs::getShopCategory()->shopcatToSelection('slug', 'name', false);
        $data['shopcategory'] = $cats[$data['shopcategoryLink']];


        return $data;
    }

    public function beforeUpdateForm($population)
    {
        //print_r($population);
        //exit();

        return $population;
    }

    public function afterSave($data)
    {

        $hdata = array();
        $hdata['historyTimestamp'] = new MongoDate();
        $hdata['historyAction'] = 'new';
        $hdata['historySequence'] = 0;
        $hdata['historyObjectType'] = 'asset';
        $hdata['historyObject'] = $data;
        History::insert($hdata);

        return $data;
    }

    public function afterUpdate($id,$data = null)
    {
        $data['_id'] = new MongoId($id);


        $hdata = array();
        $hdata['historyTimestamp'] = new MongoDate();
        $hdata['historyAction'] = 'update';
        $hdata['historySequence'] = 1;
        $hdata['historyObjectType'] = 'asset';
        $hdata['historyObject'] = $data;
        History::insert($hdata);


        return $id;
    }


    public function postAdd($data = null)
    {
        $this->validator = array(
            'shopDescription' => 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'shopDescription' => 'required'
        );

        //exit();

        return parent::postEdit($id,$data);
    }

    public function postDlxl()
    {

        $this->heads = Config::get('jex.default_export_heads');

        $this->fields = Config::get('jex.default_export_fields');

        $db = Config::get('jayon.main_db');

        $this->def_order_by = 'ordertime';
        $this->def_order_dir = 'desc';
        $this->place_action = 'first';
        $this->show_select = true;

        $this->sql_key = 'delivery_id';
        $this->sql_table_name = Config::get('jayon.incoming_delivery_table');
        $this->sql_connection = 'mysql';

        return parent::postDlxl();
    }


    public function postAwbdlxl()
    {

        $this->heads = null;
        $this->fields = Config::get('jex.default_fields');

        $this->export_output_fields = Config::get('jex.default_awb_fields');

        $db = Config::get('jayon.main_db');

        $this->def_order_by = 'pick_up_date';
        $this->def_order_dir = 'desc';
        $this->place_action = 'first';
        $this->show_select = true;

        $this->sql_key = 'delivery_id';
        $this->sql_table_name = Config::get('jayon.incoming_delivery_table');
        $this->sql_connection = 'mysql';

        return parent::postDlxl();
    }

    public function getImportawb(){

        $this->importkey = '_id';

        //$this->import_aux_form = View::make(strtolower($this->controller_name).'.importauxform')->render();

        return parent::getImport();
    }


    public function getImport(){

        $this->importkey = '_id';

        $this->import_aux_form = View::make(strtolower($this->controller_name).'.importauxform')->render();

        return parent::getImport();
    }

    public function postUploadimport()
    {
        $this->importkey = 'consignee_olshop_orderid';

        return parent::postUploadimport();
    }

    public function processImportAuxForm()
    {

        return array('position'=>Input::get('position') );
    }

    public function prepImportItem($field, $v){

        return $v;
    }

    public function beforeImportCommit($data)
    {
        date_default_timezone_set('Asia/Jakarta');

        /*
        unset($data['createdDate']);
        unset($data['lastUpdate']);

        $data['created'] = $data['created_at'];

        unset($data['created_at']);
        unset($data['updated_at']);
        */

        $trav = $this->traverseFields(Config::get('jex.default_export_fields'));

        foreach ($data as $key=>$value){
            if(array_key_exists($key, $trav)){
                if($trav[$key]['kind'] == 'text'){
                    $data[$key] = strval($value);
                }


                if($trav[$key]['kind'] == 'daterange' ||
                    $trav[$key]['kind'] == 'datetimerange'||
                    $trav[$key]['kind'] == 'date'||
                    $trav[$key]['kind'] == 'datetime'

                    ){

                    if($key != 'createdDate' && $key != 'lastUpdate'){
                        $data[$key] = new MongoDate( strtotime($data[$key]) );
                    }

                }

            }
        }
        /*
        $data['CONSIGNEE_OLSHOP_CUST'] = strval($data['CONSIGNEE_OLSHOP_CUST']);
        $data['CONSIGNEE_OLSHOP_ORDERID'] = strval($data['CONSIGNEE_OLSHOP_ORDERID']);
        $data['CONSIGNEE_OLSHOP_PHONE'] = strval($data['CONSIGNEE_OLSHOP_PHONE']);
        $data['CONSIGNEE_OLSHOP_ZIP'] = strval($data['CONSIGNEE_OLSHOP_ZIP']);
        $data['NO_SALES_ORDER'] = strval($data['NO_SALES_ORDER']);
        */

        //$data['PICK_UP_DATE'] = new MongoDate( strtotime($data['PICK_UP_DATE']) );

        if(isset($data['consignee_olshop_cust'])){
            $logistic = Logistic::where('consignee_olshop_cust', '=', $data['consignee_olshop_cust'])->first();

            if($logistic){
                $data['logistic'] = $logistic->logistic_code;
                $data['logistic_type'] = $logistic->type;
            }else{
                $data['logistic'] = 'BRIDER001';
                $data['logistic_type'] = 'internal';
            }

        }

        $data['delivery_type'] = (isset($data['delivery_type']))?$data['delivery_type']:'REG';

        $data['trip'] = (isset($data['trip']))?intval($data['trip']):1;

        if(isset($data['cod'])){
            if($data['cod'] == '' || is_null($data['cod'])){
                //print 'cod is null';
                $data['cod'] = 0;
            }
            $data['cod'] = doubleval($data['cod']);
        }

        $data['bucket'] = Config::get('jayon.bucket_incoming');

        $data['delivery_id'] = Prefs::getDeliveryId();
        $data['order_id'] = $data['no_sales_order'];
        $data['fulfillment_code'] = $data['consignee_olshop_orderid'];

        $this->updateBox($data['delivery_id'], $data['order_id'], $data['fulfillment_code'], $data['number_of_package']);

        $data['status'] = Config::get('jayon.trans_status_confirmed');
        $data['logistic_status'] = '';
        $data['pending_count'] = 0;
        $data['courier_status'] = Config::get('jayon.trans_cr_atmerchant');
        $data['warehouse_status'] = Config::get('jayon.trans_wh_atmerchant');
        $data['pickup_status'] = Config::get('jayon.trans_status_tobepickup');

        $data['device_key'] = '';
        $data['device_name'] = '';
        $data['device_id'] = '';

        $data['courier_name'] = '';
        $data['courier_id'] = '';

        unset($data['volume']);
        unset($data['sessId']);
        unset($data['isHead']);

        return $data;
    }

    public function makeActions($data)
    {
        /*
        if(!is_array($data)){
            $d = array();
            foreach( $data as $k->$v ){
                $d[$k]=>$v;
            }
            $data = $d;
        }

        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-times-circle"></i> Delete</span>';
        $edit = '<a href="'.URL::to('advertiser/edit/'.$data['_id']).'"><i class="fa fa-edit"></i> Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['_id']).'" target="new"><i class="fa fa-download"></i> Download</a>';
        $print = '<a href="'.URL::to('brochure/print/'.$data['_id']).'" target="new"><i class="fa fa-print"></i> Print</a>';
        $upload = '<span class="upload" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Upload Picture</span>';
        $inv = '<span class="upinv" id="'.$data['_id'].'" rel="'.$data['SKU'].'" ><i class="fa fa-upload"></i> Update Inventory</span>';
        $stat = '<a href="'.URL::to('stats/merchant/'.$data['id']).'"><i class="fa fa-line-chart"></i> Stats</a>';

        $history = '<a href="'.URL::to('advertiser/history/'.$data['_id']).'"><i class="fa fa-clock-o"></i> History</a>';

        $actions = $stat.'<br />'.$edit.'<br />'.$delete;
        */
        $delete = '<span class="del action" id="'.$data['delivery_id'].'" >Delete</span>';
        $edit = '<a href="'.URL::to('advertiser/edit/'.$data['delivery_id']).'">Update</a>';
        $dl = '<a href="'.URL::to('brochure/dl/'.$data['delivery_id']).'" target="new">Download</a>';

        $actions = View::make('shared.action')
                        ->with('actions',array($dl))
                        ->render();
        return $actions;
    }

    public function accountDesc($data)
    {

        return $data['ACCNT_CODE'];
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

    public function locationName($data){
        if(isset($data['locationId']) && $data['locationId'] != ''){
            $loc = Assets::getLocationDetail($data['locationId']);
            return $loc->name;
        }else{
            return '';
        }

    }

    public function merchantInfo($data)
    {
        return $data['merchant_name'].'<hr />'.$data['app_name'];
    }

    public function catName($data)
    {
        return $data['shopcategory'];
    }

    public function rackName($data){
        if(isset($data['rackId']) && $data['rackId'] != ''){
            $loc = Assets::getRackDetail($data['rackId']);
            if($loc){
                return $loc->SKU;
            }else{
                return '';
            }
        }else{
            return '';
        }

    }

    public function postSynclegacy(){

        set_time_limit(0);

        $mymerchant = Merchant::where('group_id',4)->get();

        $count = 0;

        foreach($mymerchant->toArray() as $m){

            $member = Member::where('legacyId',$m['id'])->first();

            if($member){

            }else{
                $member = new Member();
            }

            foreach ($m as $k=>$v) {
                $member->{$k} = $v;
            }

            if(!isset($member->status)){
                $member->status = 'inactive';
            }

            if(!isset($member->url)){
                $member->url = '';
            }

            $member->legacyId = new MongoInt32($m['id']);

            $member->roleId = Prefs::getRoleId('Merchant');

            $member->unset('id');

            $member->save();

            $count++;
        }

        return Response::json( array('result'=>'OK', 'count'=>$count ) );

    }

    public function statNumbers($data){
        $datemonth = date('M Y',time());
        $firstday = Carbon::parse('first day of '.$datemonth);
        $lastday = Carbon::parse('last day of '.$datemonth)->addHours(23)->addMinutes(59)->addSeconds(59);

        $qval = array('$gte'=>new MongoDate(strtotime($firstday->toDateTimeString())),'$lte'=>new MongoDate( strtotime($lastday->toDateTimeString()) ));

        $qc = array();

        $qc['adId'] = $data['_id'];

        $qc['clickedAt'] = $qval;

        $qv = array();

        $qv['adId'] = $data['_id'];

        $qv['viewedAt'] = $qval;

        $clicks = Clicklog::whereRaw($qc)->count();

        $views = Viewlog::whereRaw($qv)->count();

        return $clicks.' clicks<br />'.$views.' views';
    }

    public function namePic($data)
    {
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        $ps = Config::get('picture.sizes');


        if(isset($data['files']) && count($data['files'])){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $g['caption'] = ( isset($g['caption']) && $g['caption'] != '')?$g['caption']:$data['SKU'];
                $g['full_url'] = isset($g['full_url'])?$g['full_url']:$g['fileurl'];
                foreach($ps as $k=>$s){
                    if(isset($g[$k.'_url'])){
                        $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$k.'" value="'.$g[$k.'_url'].'" />';
                    }
                }
            }
            if(isset($data['useImage']) && $data['useImage'] == 'linked'){
                $thumbnail_url = $data['extImageURL'];
                $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;
            }else{
                $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','style'=>'cursor:pointer;','id' => $data['_id'])).$glinks;
            }
            return $display;
        }else{
            return $data['SKU'];
        }
    }

    public function puDisp($data){
        return $data['pickup_person'].'<br />'.$data['pickup_dev_id'];
    }

    public function dispFBar($data)

    {
        $display = HTML::image(URL::to('qr/'.urlencode(base64_encode($data['delivery_id'].'|'.$data['merchant_trans_id'].'|'.$data['fulfillment_code'].'|box:1' ))), $data['merchant_trans_id'], array('id' => $data['delivery_id'], 'style'=>'width:100px;height:auto;' ));
        //$display = '<a href="'.URL::to('barcode/dl/'.urlencode($data['SKU'])).'">'.$display.'</a>';
        return $display.'<br />'. '<a href="'.URL::to('incoming/detail/'.$data['delivery_id']).'" >'.$data['fulfillment_code'].' ('.$data['box_count'].' box)</a>';
    }

    public function dispBar($data)

    {
        $display = HTML::image(URL::to('qr/'.urlencode(base64_encode($data['delivery_id'].'|'.$data['merchant_trans_id'].'|'.$data['fulfillment_code'].'|box:1' ))), $data['merchant_trans_id'], array('id' => $data['delivery_id'], 'style'=>'width:100px;height:auto;' ));
        //$display = '<a href="'.URL::to('barcode/dl/'.urlencode($data['SKU'])).'">'.$display.'</a>';
        return $display.'<br />'. '<a href="'.URL::to('asset/detail/'.$data['delivery_id']).'" >'.$data['merchant_trans_id'].'</a>';
    }

    public function internalCod($data){
        if($data['logistic_type'] == 'internal'){
            if( ($data['cod'] == 0 || $data['cod'] == '')){
                return $data['cod'];
            }else{
                return '<span class="red">'.$data['cod'].'</span>';
            }
        }else{
            return $data['cod'];
        }
    }

    public function statusList($data)
    {
        $slist = array(
            Prefs::colorizestatus($data['status'],'delivery'),
            Prefs::colorizestatus($data['courier_status'],'courier'),
            //Prefs::colorizestatus($data['pickup_status'],'pickup'),
            Prefs::colorizestatus($data['warehouse_status'],'warehouse')
        );


        //return Prefs::colorizestatus($data['status'],'delivery');

        return implode('<br />', $slist);
        //return '<span class="orange white-text">'.$data['status'].'</span><br /><span class="brown">'.$data['pickup_status'].'</span><br /><span class="green">'.$data['courier_status'].'</span><br /><span class="maroon">'.$data['warehouse_status'].'</span>';
    }

    public function dupeFF($data){
        $count = Shipment::where('consignee_olshop_orderid','=',$data['consignee_olshop_orderid'])
                        ->where('status','!=', Config::get('jayon.trans_status_canceled') )
                        ->count();
        if($count > 1){
            return '<span class="red">'.$data['consignee_olshop_orderid'].'</span>'.'<div class="badge pull-right">'.$count.'</div>';
        }else{
            return $data['consignee_olshop_orderid'];
        }
    }

    public function colorizetype($data)
    {
        return Prefs::colorizetype($data['delivery_type']);
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

    public function postAssigndate(){
        $in = Input::get();
        $results = Shipment::whereIn('_id', $in['ids'])->get();

        date_default_timezone_set('Asia/Jakarta');

        if(is_null($in['trip']) || $in['trip'] == ''){
            $trip = 1;
        }else{
            $trip =  new MongoInt64($in['trip']);
        }

        //print_r($results->toArray());

        //if($results){
            $res = false;
        //}else{
            foreach($results as $r){
                if(is_null($in['date']) || $in['date'] == ''){

                }else{
                    $newdate = strtotime($in['date']);
                    $r->pick_up_date = new MongoDate($newdate) ;
                }

                $r->trip = $trip;

                if($r->logistic_type == 'internal'){
                    if($r->cod == 0 || $r->cod == ''){
                        $r->awb = $r->delivery_id;
                        $r->bucket = Config::get('jayon.bucket_dispatcher');
                        $r->status = Config::get('jayon.trans_status_admin_dated');
                    }
                }else{
                    if($r->awb != ''){
                        $r->bucket = Config::get('jayon.bucket_tracker');
                        $r->status = Config::get('jayon.trans_status_admin_dated');
                    }
                }

                $r->save();
            }
            $res = true;
        //}

        if($res){
            return Response::json(array('result'=>'OK:MOVED' ));
        }else{
            return Response::json(array('result'=>'ERR:MOVEFAILED' ));
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
        $labels = Shipment::whereIn('_id', $session)->get()->toArray();

        $skus = array();
        foreach($labels as $l){
            $skus[] = $l['_id'];
        }

        $skus = array_unique($skus);

        $products = Shipment::whereIn('_id',$skus)->get()->toArray();

        $plist = array();
        foreach($products as $product){
            $plist[$product['_id']] = $product;
        }

        return View::make('shared.printlabel')
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


    public function updateBox($delivery_id, $order_id, $fulfillment_code, $box_count){
        for($n = 0; $n < $box_count; $n++){
            $box = new Box();
            $box->delivery_id = $delivery_id;
            $box->order_id = $order_id;
            $box->fulfillment_code = $fulfillment_code;
            $box->box_id = $n + 1;
            $box->deliveryStatus = Config::get('jayon.trans_status_confirmed');
            $box->courierStatus = Config::get('jayon.trans_cr_atmerchant');
            $box->warehouseStatus = Config::get('jayon.trans_wh_atmerchant');
            $box->pickupStatus = Config::get('jayon.trans_status_tobepickup');
            $box->save();
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
