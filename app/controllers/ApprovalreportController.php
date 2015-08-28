<?php

class ApprovalreportController extends BaseReportController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = strtolower(str_replace('Controller', '', get_class()));

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Approval();
        //$this->model = DB::collection('documents');

    }


    public function getIndex()
    {

        Breadcrumbs::addCrumb('Reports',URL::to($this->controller_name));

        $this->report_action = $this->controller_name;

        $this->additional_filter = View::make('approvalreport.addfilter')
            ->with('report_action', $this->report_action)
            ->render();

                $dataArray01 = array(
                    'label'=>'My First Dataset',
                    'fillColor'=>'rgba(220,220,220,0.2)',
                    'strokeColor'=>'rgba(220,220,220,1)',
                    'pointColor'=>'rgba(220,220,220,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>array(65, 59, 80, 81, 56, 55, 40)
                );

                $dataArray02 = array(
                    'label'=>'My First Dataset',
                    'fillColor'=>'rgba(220,220,220,0.2)',
                    'strokeColor'=>'rgba(220,220,220,1)',
                    'pointColor'=>'rgba(220,220,220,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>array(28, 48, 40, 19, 86, 27, 90)
                );

                $dataArray03 = array(
                    'label'=>'My Third Dataset',
                    'fillColor'=>'rgba(200,200,200,0.2)',
                    'strokeColor'=>'rgba(220,220,220,1)',
                    'pointColor'=>'rgba(220,220,220,1)',
                    'pointStrokeColor'=>'#fff',
                    'pointHighlightFill'=>'#fff',
                    'pointHighlightStroke'=>'rgba(220,220,220,1)',
                    'data'=>array(35, 12, 60, 22, 100, 90, 85)
                );

                $labels = array("Januari", "Februari", "March", "April", "Mei", "June", "Juli");

                $this->data = array(
                    'series01'=>$dataArray01,
                    'series02'=>$dataArray02,
                    'series03'=>$dataArray03,
                    'labels'=>$labels
                    );

        $this->report_view = 'approvalreport.report';
        $this->title = 'Approvals';
        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('access',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('docShare',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitShare')),
            array('creatorName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('docCategoryLabel',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('docFilename',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('docTag',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitTag')),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'brandName' => 'required',
            'productName'=> 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'brandName' => 'required',
            'productName'=> 'required'
        );

        return parent::postEdit($id,$data);
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('document/edit/'.$data['_id']).'"><i class="fa fa-edit"></i>Update</a>';

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
