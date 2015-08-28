<?php

class TemplatesController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        $this->model = new Template();
        //$this->model = DB::collection('documents');
        $this->title = $this->controller_name;

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $categories = Prefs::getCategory()->catToSelection('title','title');

        $this->heads = array(
            array('Title',array('search'=>true,'sort'=>true)),
            array('Status',array('search'=>true,'sort'=>true)),
            array('Creator',array('search'=>true,'sort'=>false)),
            array('Category',array('search'=>true,'select'=>$categories,'sort'=>true)),
            array('Tags',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->modal_sets = View::make(strtolower($this->controller_name).'.modal')->render();

        $this->js_table_event = View::make(strtolower($this->controller_name).'.jsevent')->render();

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('status',array('kind'=>'text','query'=>'like','callback'=>'activeColor','pos'=>'both','show'=>true)),
            array('creatorName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('category',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('tags',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'callback'=>'splitTag')),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'title' => 'required',
            'slug'=> 'required'
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'title' => 'required',
            'slug'=> 'required'
        );

        return parent::postEdit($id,$data);
    }

    public function beforeSave($data)
    {
        $data['creatorName'] = Auth::user()->fullname;

        $data['status'] = 'inactive';

        //$template = Str::random(8);
        /*
        if(file_put_contents(public_path().'/themes/default/views/tmpl/'.$template.'.blade.php', $data['body'])){
            $data['template'] = $template;
        }
        */

        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        //print_r($data);
        //die();

        //$template = ($data['template'] == '')?Str::random(8):$data['template'];

        //file_put_contents(public_path().'/themes/default/views/tmpl/'.$template.'.blade.php', $data['body']);

        return $data;
    }

    public function postActivate()
    {
        $id = Input::get('id');
        $type = Input::get('type');

        $_id = new MongoId($id);

        $actives = Template::where('status', 'active')
            ->where('type', $type)
            ->get();


        $incl = false;
        foreach($actives as $deactive){
            if($deactive->_id == $id){
                $incl = true;
            }else{
                $deactive->status = 'inactive';
                $deactive->save();
            }
        }

        if($incl == false){
            $active = Template::find($id);
            $active->status = 'active';
            $active->save();
        }

        return Response::json(array('status'=>'OK'));
    }

    public function postApply()
    {
        $in = Input::get();
        $template = Template::find($in['id']);

        if($template){
            $template->body = $in['body'];
            $tmplfile = $template->template;
            if($template->save()){
                file_put_contents(public_path().'/themes/default/views/brochuretmpl/'.$tmplfile.'.blade.php', $in['body']);
            }
            return Response::json(array('result'=>'OK'));
        }else{
            return Response::json(array('result'=>'FAILED'));
        }

    }

    public function getPreview($template,$type = null)
    {

        $tmpl = Template::find($template)->first();

        if(!is_null($type) && $type != 'pdf'){
            $content = DbView::make($tmpl)
                    ->field('body')
                    ->render();
            return $content;
        }else{
            //return PDF::loadView('print.brochure',array('prop'=>$prop))
            //    ->stream('download.pdf');
            $tmpl = $tmpl->toArray();

            return PDF::loadView('brochuretmpl.'.$template, array('prop'=>$prop,'contact'=>$contact,'roi3'=>$roi3,'roi5'=>$roi5))
                        ->setOption('margin-top', $tmpl['margin-top'])
                        ->setOption('margin-left', $tmpl['margin-left'])
                        ->setOption('margin-right', $tmpl['margin-right'])
                        ->setOption('margin-bottom', $tmpl['margin-bottom'])
                        ->setOption('dpi',$tmpl['dpi'])
                        ->setPaper($tmpl['paper-size'])
                        ->stream($prop['propertyId'].'.pdf');

            //return PDF::html('print.brochure',array('prop' => $prop), 'download.pdf');
        }

    }

    public function getDl($id,$type = null)
    {

        $prop = Property::find($id)->toArray();

        $type = (is_null($type))?'pdf':$type;

        $tmpl = Template::where('type','brochure')->where('status','active')->first();

        $template = $tmpl->template;

        $nophotolrg = URL::to('images/no-photo-lrg.jpg');
        $nophoto = URL::to('images/no-photo.jpg');
        $nophotomd = URL::to('images/no-photo-md.jpg');

            if(isset($prop['defaultpictures'])){
                $d = $prop['defaultpictures'];
                $d['brchead'] = (isset($d['brchead']) && $d['brchead'] != '')?$d['brchead']:$nophotolrg;
                $d['brc1'] = ( isset($d['brc1']) && $d['brc1'] != '')?$d['brc1']:$nophotomd;
                $d['brc2'] = ( isset($d['brc2']) && $d['brc2'] != '')?$d['brc2']:$nophotomd;
                $d['brc3'] = ( isset($d['brc3']) && $d['brc3'] != '')?$d['brc3']:$nophotomd;
            }else{
                $d = array();
                $d['brchead'] = $nophoto;
                $d['brc1'] = $nophotomd;
                $d['brc2'] = $nophotomd;
                $d['brc3'] = $nophotomd;
            }

        $prop['defaultpictures'] = $d;

        if(Auth::check() && ( isset(Auth::user()->showContact) && Auth::user()->showContact == 'yes') ){
            if(isset(Auth::user()->firstname)){
                $contact['fullname'] = Auth::user()->firstname.' '.Auth::user()->lastname;
            }else if( isset(Auth::user()->fullname)){
                $contact['fullname'] = Auth::user()->fullname;
            }else{
                $contact['fullname'] = '';
            }
            $contact['email'] = Auth::user()->email;
            $contact['mobile'] = Auth::user()->mobile;
        }else{
            $contact['fullname'] = '';
            $contact['email'] = '';
            $contact['mobile'] = '';
        }

        $rental = (double)$prop['monthlyRental'] * 12;
        $price = (double)$prop['listingPrice'];
        $year = 3;

        $roi = 0;
        $initprice = $price;
        $counter = $year;
        $result = 0;
        $pct = 5;

        $projected = px($price, $pct, $year,$initprice,$rental ,$roi, $counter, $result);

        $roi3 = $result;
        //print 'projected ROI : '.$result;

        $pct = 10;

        $roi = 0;
        $initprice = $price;
        $counter = $year;
        $result = 0;
        $projected = px($price, $pct, $year,$initprice,$rental ,$roi, $counter, $result);

        $roi5 = $result;
        //print 'projected ROI : '.$result;

        if(!is_null($type) && $type != 'pdf'){
            $content = View::make('brochuretmpl.'.$template)
                ->with('roi3',$roi3)
                ->with('roi5',$roi5)
                ->with('prop',$prop)
                ->with('contact',$contact)->render();
            return $content;
        }else{
            //return PDF::loadView('print.brochure',array('prop'=>$prop))
            //    ->stream('download.pdf');
            $tmpl = $tmpl->toArray();

            return PDF::loadView('brochuretmpl.'.$template, array('prop'=>$prop,'contact'=>$contact,'roi3'=>$roi3,'roi5'=>$roi5))
                        ->setOption('margin-top', $tmpl['margin-top'])
                        ->setOption('margin-left', $tmpl['margin-left'])
                        ->setOption('margin-right', $tmpl['margin-right'])
                        ->setOption('margin-bottom', $tmpl['margin-bottom'])
                        ->setOption('dpi',$tmpl['dpi'])
                        ->setPaper($tmpl['paper-size'])
                        ->stream($prop['propertyId'].'.pdf');

            //return PDF::html('print.brochure',array('prop' => $prop), 'download.pdf');
        }

    }

    function px($price, $pct, $year, $initprice,$rental ,$roi, $counter, &$result){
        if($counter == 0){
            return $roi;
        }else{
            $price = $price + ($price * ( $pct / 100));
            $counter--;
            $rental = $rental + $rental;

            $roi = (($price - $initprice) + $rental )/ $initprice;

            $result = $roi;

            //print $price.' '.number_format($roi * 100, 1,'.',',').'%<br />';

            px($price, $pct, $year, $initprice, $rental, $roi ,$counter, $result);
        }
    }

    public function getMail($id)
    {

        $prop = Property::find($id)->toArray();

        $tmpl = Template::where('type','brochure')->where('status','active')->first();

        $template = $tmpl->template;

        if(Auth::check()){
            $contact['fullname'] = Auth::user()->firstname.' '.Auth::user()->lastname;
            $contact['email'] = Auth::user()->email;
            $contact['mobile'] = Auth::user()->mobile;
        }else{
            $contact['fullname'] = Options::get('brochure_default_name');
            $contact['email'] = Options::get('brochure_default_email');
            $contact['mobile'] = Options::get('brochure_default_mobile');
        }

        //$content = View::make('print.brochure')->with('prop',$prop)->render();

        $brochurepdf = PDF::loadView('brochuretmpl.'.$template, array('prop'=>$prop, 'contact'=>$contact))
                        ->setOption('margin-top', $tmpl['margin-top'])
                        ->setOption('margin-left', $tmpl['margin-left'])
                        ->setOption('margin-right', $tmpl['margin-right'])
                        ->setOption('margin-bottom', $tmpl['margin-bottom'])
                        ->setOption('dpi',$tmpl['dpi'])
                        ->setPaper($tmpl['paper-size'])
                        ->output();

        file_put_contents(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf', $brochurepdf);

        //$mailcontent = View::make('emails.brochure')->with('prop',$prop)->render();

        Mail::send('emails.brochure',$prop, function($message) use ($prop, &$prop){
            $to = Input::get('to');
            $tos = explode(',', $to);
            if(is_array($tos) && count($tos) > 1){
                foreach($tos as $to){
                    $message->to($to, $to);
                }
            }else{
                    $message->to($to, $to);
            }

            $message->subject('Investors Alliance - '.$prop['propertyId']);

            $message->cc('support@propinvestorsalliance.com');

            $message->attach(public_path().'/storage/pdf/'.$prop['propertyId'].'.pdf');
        });

        print json_encode(array('result'=>'OK'));

    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="fa fa-trash"></i>Delete</span>';
        $active = '<span class="active" id="'.$data['_id'].'" data-type="'.$data['type'].'" ><i class="fa fa-trash"></i>Set Active</span>';
        $edit = '<a href="'.URL::to('templates/edit/'.$data['_id']).'"><i class="fa fa-edit"></i>Update</a>';

        $pdf = '<a href="'.URL::to('templates/preview/'.$data['_id'].'/pdf').'" target="blank"
        ><i class="fa fa-edit"></i>PDF Preview</a>';
        $html = '<a href="'.URL::to('templates/preview/'.$data['_id'].'/html').'" target="blank"
        ><i class="fa fa-edit"></i>HTML Preview</a>';

        $actions = $edit.'<br />'.$active.'<br />'.$delete.'<br />'.$pdf.'<br />'.$html;
        return $actions;
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

    public function activeColor($data)
    {
        return ($data['status'] == 'active')?'<span class="red">'.$data['status'].'</span>':$data['status'];
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
