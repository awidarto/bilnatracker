<?php

class ApprovalController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Approval();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }

    public function getHistory($type = 'asset',$id)
    {
        $_id = new MongoId($id);

        if($type == 'Location'){
            $type = 'location';
        }elseif ($type == 'Rack') {
            $type = 'rack';
        }else{
            $type = 'asset';
        }

        $history = History::where('historyObject._id',$_id)->where('historyObjectType',$type)
                        ->orderBy('historyTimestamp','desc')
                        ->orderBy('historySequence','desc')
                        ->get();
        $diffs = array();

        foreach($history as $h){
            $h->date = date( 'Y-m-d H:i:s', $h->historyTimestamp->sec );
            $diffs[$h->date][$h->historySequence] = $h->historyObject;
        }

        $history = History::where('historyObject._id',$_id)->where('historyObjectType',$type)
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
                    $h->historyObject['SKU'],
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

        $asset = Asset::find($id);

        Breadcrumbs::addCrumb('Approval',URL::to( strtolower($this->controller_name) ));
        Breadcrumbs::addCrumb('Change Detail',URL::to( strtolower($this->controller_name) ));

        return View::make('history.table')
                    ->with('a',$asset)
                    ->with('title','Change Detail')
                    ->with('table',$itemtable);
    }

    public function objdiff($obj)
    {

        if(is_array($obj) && count($obj) == 2){
            $diff = array();
            foreach ($obj[0] as $key=>$value) {
                if(isset($obj[0][$key]) && isset($obj[1][$key])){
                    if($obj[0][$key] !== $obj[1][$key]){
                        if($key != '_id' && $key != 'createdDate' && $key != 'lastUpdate'){
                            if(!is_array($obj[0][$key])){
                                $diff[] = $key.' : '. $obj[0][$key].' -> '.$obj[1][$key];
                            }
                        }
                    }
                }
            }
            return implode('<br />', $diff);
        }else{
            return 'NA';
        }
    }


    public function getIndex()
    {

        $this->heads = array(
            array('Time',array('search'=>true,'sort'=>true,'datetimerange'=>true)),
            array('Change Type',array('search'=>true,'sort'=>false)),
            array('Approval Status',array('search'=>true,'sort'=>false)),
            array('Asset',array('search'=>true,'sort'=>true)),
            array('Requester',array('search'=>true,'sort'=>true))
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Pending Approval';

        $this->place_action = 'first';

        return parent::getIndex();

    }

    public function postIndex()
    {
        $this->fields = array(
            array('requestDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('approvalStatus',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('assetId',array('kind'=>'text','callback'=>'dispAsset','query'=>'like','pos'=>'both','show'=>true)),
            array('actor',array('kind'=>'text','callback'=>'dispActor' ,'query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander')))
        );

        $this->additional_query = array('approvalStatus'=>'pending');

        $this->def_order_by = 'requestDate';
        $this->def_order_dir = 'desc';

        return parent::postIndex();
    }


    public function getVerified()
    {

        $this->heads = array(
            array('Time',array('search'=>true,'sort'=>true,'datetimerange'=>true)),
            array('Change Type',array('search'=>true,'sort'=>false)),
            array('Approval Status',array('search'=>true,'sort'=>false)),
            array('Asset',array('search'=>true,'sort'=>true)),
            array('Requester',array('search'=>true,'sort'=>true))
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Verified Approval';

        $this->ajaxsource = 'approval/verified';

        $this->place_action = 'first';

        return parent::getIndex();

    }

    public function postVerified()
    {
        $this->fields = array(
            array('requestDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('approvalStatus',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('assetId',array('kind'=>'text','callback'=>'dispAsset','query'=>'like','pos'=>'both','show'=>true)),
            array('actor',array('kind'=>'text','callback'=>'dispActor' ,'query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander')))
        );

        $this->additional_query = array('approvalStatus'=>'verified');

        $this->def_order_by = 'requestDate';
        $this->def_order_dir = 'desc';

        $this->place_action = 'first';

        return parent::postIndex();
    }


    public function postAdd($data = null)
    {

        $this->validator = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=> 'required|unique:agents',
            'pass'=>'required|same:repass'
        );

        return parent::postAdd($data);
    }

    public function beforeSave($data)
    {
        unset($data['repass']);
        $data['pass'] = Hash::make($data['pass']);
        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        //print_r($data);

        if(isset($data['pass']) && $data['pass'] != ''){
            unset($data['repass']);
            $data['pass'] = Hash::make($data['pass']);

        }else{
            unset($data['pass']);
            unset($data['repass']);
        }

        //print_r($data);

        //exit();

        return $data;
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=> 'required'
        );

        if($data['pass'] == ''){
            unset($data['pass']);
            unset($data['repass']);
        }else{
            $this->validator['pass'] = 'required|same:repass';
        }

        return parent::postEdit($id,$data);
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('agent/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $history = '<a href="'.URL::to('approval/history/'.$data['assetType'].'/'.$data['assetId']).'"><i class="fa fa-clock-o"></i> Change Detail</a>';

        $actions = $history;
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

    public function dispAsset($data)
    {
        if($data['assetType'] == 'location'){
            return 'location';
        }else if($data['assetType'] == 'rack'){
            return 'rack';
        }else{
            $asset = Asset::find($data['assetId']);
            return (isset($asset->SKU))?$asset->SKU:'';
        }
    }

    public function dispActor($data)
    {
        $actor = User::find($data['actor']);
        return (isset($actor->fullname))?$actor->fullname:'';
    }

    public function namePic($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
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

    public function getViewpics($id)
    {

    }


}
