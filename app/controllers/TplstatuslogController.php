<?php

class TplstatuslogController extends AdminController {

    public $heads = array(
            array('Timestamp',array('search'=>true,'sort'=>true,'datetimerange'=>true)),
            array('Logistic Id',array('search'=>true,'sort'=>true)),
            array('Logistic Name',array('search'=>true,'sort'=>true)),
            array('Raw',array('search'=>true,'sort'=>false)),
        );

    public $fields = array(
            array('ts',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_olshop_cust',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_logistic_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('consignee_logistic_id',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true))
        );



    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Threeplstatuses();
        //$this->model = DB::collection('documents');

    }

    public function getIndex()
    {
        //$this->heads = $this->def_heads;

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Order Status Log';

        $this->show_select = false;

        $this->place_action = 'none';

        return parent::getIndex();

    }

    public function postIndex()
    {

        //$this->fields = $this->def_fields;

        $this->def_order_by = 'ts';
        $this->def_order_dir = 'desc';
        $this->show_select = false;

        $this->place_action = 'none';

        return parent::postIndex();
    }

    public function dispRaw($data)
    {
        return json_encode($data);
    }

    public function postDlxl()
    {

        return parent::postDlxl();
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
