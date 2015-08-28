<?php

class PictureController extends AdminController {

    public $pic_dir;

    public $pic_temp_dir;

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        //$this->crumb->append('Home','left',true);
        //$this->crumb->append(strtolower($this->controller_name));

        //$this->model = new Events();
        //$this->model = DB::collection('documents');

        $this->pic_dir = realpath('storage/media');

    }

    public function getIndex(){

    }

    public function getEdit($pic_id){

        $pic_dir = $this->pic_dir.'/'.$pic_id;

        $pic_dir_temp = $pic_dir.'/tmp';

        $formdata = array('_id'=>$pic_id);

        $ps = Config::get('picture.sizes');

        $prefixes = array();

        foreach($ps as $px){
            $prefixes[] = $px['prefix'];
        }

        if(is_array($prefixes)){
            $regex = implode('|^',$prefixes);
            $regex = '^tmp|^'.$regex;
        }else{
            $regex = '^tmp';
        }

        if(file_exists($pic_dir)){
            if(file_exists($pic_dir.'/tmp')){

            }else{
                mkdir($pic_dir.'/tmp');
            }
        }

        $pictures = glob($pic_dir.'/*');

        if(is_array($pictures)){
            $raw = '';
            foreach($pictures as $pic ){
                $pic = str_replace(array($pic_dir.'/',$pic_dir_temp ), '', $pic);

                if(!preg_match('/'.$regex.'/', $pic)){
                    $raw = $pic;
                }

            }
        }

        copy($pic_dir.'/'.$raw, $pic_dir_temp.'/'.$raw);

        $image = Image::make($pic_dir_temp.'/'.$raw);

        $info = array();
        $info['filename'] = $raw;
        $info['width'] = $image->width();
        $info['height'] = $image->height();
        $info['ratio'] = $image->width() / $image->height();



        $src_url = URL::to('storage/media/'.$pic_id.'/tmp/'.$raw);
        $this->backlink = URL::to('property');

        return View::make('picture.edit')
            ->with('title','Edit '.Str::singular($this->controller_name))
            ->with('formdata',$formdata)
            ->with('submit',URL::to('picture/edit'))
            ->with('src_url',$src_url)
            ->with('pic_info',$info)
            ->with('pic_dir',$this->pic_dir)
            ->with('pic_temp_dir',$this->pic_temp_dir)
            ->with('back',$this->backlink)
            ->with('pic_id',$pic_id);
    }

    public function postEdit($id, $data = NULL)
    {

    }

    public function postCrop()
    {


        $in = Input::get();

        $pic_id = $in['id'];

        $x = $in['image']['x1'];
        $y = $in['image']['y1'];
        $width = $in['image']['width'];
        $height = $in['image']['height'];

        $filename = $in['filename'];

        $pic_dir = $this->pic_dir.'/'.$pic_id;

        $pic_dir_temp = $pic_dir.'/tmp';


        if($in['mode'] == 'preview'){
            $image = Image::make($pic_dir_temp.'/'.$filename);
            $image->crop($width,$height,$x,$y)
                ->save($pic_dir_temp.'/preview_'.$filename);
            $src_url = URL::to('storage/media/'.$pic_id.'/tmp/preview_'.$filename);
            return Response::json(array('result'=>'OK','url'=>$src_url ));
        }

    }

    public function postExpand()
    {


        $in = Input::get();

        $pic_id = $in['id'];

        $width = (int)$in['image']['width'];
        $height = (int)$in['image']['height'];

        $filename = $in['filename'];

        $pic_dir = $this->pic_dir.'/'.$pic_id;

        $pic_dir_temp = $pic_dir.'/tmp';


        if($in['mode'] == 'preview'){
            $image = Image::make($pic_dir_temp.'/'.$filename);

            //landscape
            if($image->width() > $image->height() ){
                $image->resize($width,null,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas(null,$height)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }

            //portrait
            if($image->width() < $image->height() ){
                $image->resize(null,$height,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas($width,null)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }

            //square
            if($image->width() == $image->height() ){
                $image->resize(null,$height,function ($constraint) {
                                $constraint->aspectRatio();
                            },function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas($width,null)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }

            $src_url = URL::to('storage/media/'.$pic_id.'/tmp/preview_'.$filename.'?'.time());
            return Response::json(array('result'=>'OK','url'=>$src_url ));
        }

    }

    public function postApply()
    {

        $in = Input::get();

        $pic_id = $in['id'];

        $applyto = $in['apply'];

        $width = (int) $in['image']['width'];
        $height = (int) $in['image']['height'];

        $ps = Config::get('picture.sizes');

        $filename = $in['filename'];

        $pic_dir = $this->pic_dir.'/'.$pic_id;

        $pic_dir_temp = $pic_dir.'/tmp';

        $image = Image::make($pic_dir.'/'.$filename);

        if($in['mode'] == 'crop'){

            $x = (int) $in['image']['x1'];
            $y = (int) $in['image']['y1'];

            if(in_array('all', $applyto)){
                foreach( $ps as $p){
                    $image = Image::make($pic_dir.'/'.$filename);
                    $image->crop($width,$height,$x,$y)
                        ->resize((int) $p['width'],null, function ($constraint) {
                                $constraint->aspectRatio();
                            })
                        ->save($pic_dir.'/'.$p['prefix'].$filename);
                    $src_url = URL::to('storage/media/'.$pic_id.'/'.$filename);
                }
            }else{
                foreach( $applyto as $p){
                    $image = Image::make($pic_dir.'/'.$filename);
                    $image->crop($width,$height,$x,$y)
                        ->resize((int) $ps[$p]['width'],null,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                        ->save($pic_dir.'/'.$ps[$p]['prefix'].$filename);
                    $src_url = URL::to('storage/media/'.$pic_id.'/'.$filename);
                }
            }

        }else{
            $image = Image::make($pic_dir_temp.'/'.$filename);

            //landscape
            if($image->width() > $image->height() ){
                $image->resize($width,null,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas(null,$height)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }

            //portrait
            if($image->width() < $image->height() ){
                $image->resize(null,$height,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas($width,null)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }

            //square
            if($image->width() == $image->height() ){
                $image->resize(null,$height,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                    ->resizeCanvas($width,null)
                    ->save($pic_dir_temp.'/preview_'.$filename);
            }


            if(in_array('all', $applyto)){
                foreach( $ps as $p){
                    $pimage = Image::make($pic_dir_temp.'/preview_'.$filename);
                    $pimage->resize((int)$p['width'],null,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                        ->save($pic_dir.'/'.$p['prefix'].$filename);
                }
            }else{
                foreach( $applyto as $p){
                    $pimage = Image::make($pic_dir_temp.'/preview_'.$filename);
                    $pimage->resize((int)$ps[$p]['width'],null,function ($constraint) {
                                $constraint->aspectRatio();
                            })
                        ->save($pic_dir.'/'.$ps[$p]['prefix'].$filename);
                }
            }


        }

        return Response::json(array('result'=>'OK'));

    }


}