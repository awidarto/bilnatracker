<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class UploadapiController extends \Controller {
    public $controller_name = '';

    public function  __construct()
    {
        //$this->model = "Member";
        $this->controller_name = strtolower( str_replace('Controller', '', get_class()) );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postFile()
    {

        $key = Input::get('key');

        //$user = \Apiauth::user($key);

        $user = \Device::where('key','=',$key)->first();

        if(!$user){
            $actor = 'no id : no name';
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'device not found, upload image failed'));

            return \Response::json(array('status'=>'ERR:NODEVICE', 'timestamp'=>time(), 'message'=>$image_id ));
        }

        $parent_id = Input::get('parid');

        $parent_class = Input::get('parclass');

        $file_id = Input::get('fid');

        $image_id = Input::get('img');

        $ns = Input::get('ns');

        $isSignature = Input::get('signature');

        $lat = Input::get('lat');

        $lon = Input::get('lon');

        if( isset($file_id) && $file_id != '' ){
            $rstring = $file_id;
        }else{
            $rstring = str_random(15);
        }

        $result = '';

        //$destinationPath = realpath('storage/media').'/'.$rstring;

        if(Input::hasFile('imagefile')){

            $file = Input::file('imagefile');

            $destinationPath = realpath('storage/media').'/'.$rstring;

            $filename = $file->getClientOriginalName();
            $filemime = $file->getMimeType();
            $filesize = $file->getSize();
            $extension = $file->getClientOriginalExtension(); //if you need extension of the file

            $filename = str_replace(\Config::get('kickstart.invalidchars'), '-', $filename);

            $uploadSuccess = $file->move($destinationPath, $filename);

            $is_image = true;
            $is_audio = false;
            $is_video = false;
            $is_pdf = false;
            $is_doc = false;

            $is_image = $this->isImage($filemime);
            $is_audio = $this->isAudio($filemime);
            $is_video = $this->isVideo($filemime);
            $is_pdf = $this->isPdf($filemime);

            if(!($is_image || $is_audio || $is_video || $is_pdf)){
                $is_doc = true;
            }else{
                $is_doc = false;
            }

            $exif = array();

            if($is_image){

                $ps = \Config::get('picture.sizes');

                $thumbnail = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['thumbnail']['width'],$ps['thumbnail']['height'])
                    ->save($destinationPath.'/th_'.$filename);

                $medium = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['medium']['width'],$ps['medium']['height'])
                    ->save($destinationPath.'/med_'.$filename);

                $large = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['large']['width'],$ps['large']['height'])
                    ->save($destinationPath.'/lrg_'.$filename);

                $full = \Image::make($destinationPath.'/'.$filename)
                    ->save($destinationPath.'/full_'.$filename);

                $image_size_array = array(
                    'thumbnail_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['thumbnail']['prefix'].$filename),
                    'large_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['large']['prefix'].$filename),
                    'medium_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['medium']['prefix'].$filename),
                    'full_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['full']['prefix'].$filename),
                );

                $exif = \Image::make($destinationPath.'/'.$filename)
                    ->exif();

            }else{

                if($is_audio){
                    $thumbnail_url = \URL::to('images/audio.png');
                }elseif($is_video){
                    $thumbnail_url = \URL::to('images/video.png');
                }else{
                    $thumbnail_url = \URL::to('images/media.png');
                }

                $image_size_array = array(
                    'thumbnail_url'=> $thumbnail_url,
                    'large_url'=> '',
                    'medium_url'=> '',
                    'full_url'=> ''
                );
            }


            $item = array(
                    'ns'=>$ns,
                    'parent_id'=> $parent_id,
                    'parent_class'=> $parent_class,
                    'url'=> \URL::to('storage/media/'.$rstring.'/'.$filename),
                    'temp_dir'=> $destinationPath,
                    'file_id'=> $rstring,
                    'is_image'=>$is_image,
                    'is_audio'=>$is_audio,
                    'is_video'=>$is_video,
                    'is_signature'=>$isSignature,
                    'is_pdf'=>$is_pdf,
                    'is_doc'=>$is_doc,
                    'latitude'=>$lat,
                    'longitude'=>$lon,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'deleted'=>0,
                    'createdDate'=>new \MongoDate(),
                    'lastUpdate'=>new \MongoDate()
                );

            foreach($image_size_array as $k=>$v){
                $item[$k] = $v;
            }


            $item['_id'] = new \MongoId($image_id);

            $im = \Uploaded::find($image_id);
            if($im){

                foreach($item as $k=>$v){
                    if($k != '_id'){
                        $im->{$k} = $v;
                    }
                }

                $im->save();

            }else{
                \Uploaded::insertGetId($item);
            }

            $actor = $user->identifier.' : '.$user->devname;
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'upload image'));

            return \Response::json(array('status'=>'OK', 'timestamp'=>time(), 'message'=>$image_id ));


        }

        $actor = $user->identifier.' : '.$user->devname;
        \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'upload image failed'));

        return \Response::json(array('status'=>'ERR:NOFILE', 'timestamp'=>time(), 'message'=>$image_id ));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postFiles()
    {

        $key = Input::get('key');

        $user = \Apiauth::user($key);

        $parent_id = Input::get('parid');

        $parent_class = Input::get('parclass');

        $image_id = Input::get('img');

        $ns = Input::get('ns');

        $rstring = str_random(15);

        $result = '';

        $destinationPath = realpath('storage/media').'/'.$rstring;

        if(isset($_FILES['file'])){

            $file = $_FILES['file'];

            $filename = $file['name'];
            $filemime = $file['type'];
            $filesize = $file['size'];
            $extension = '.jpg'; //if you need extension of the file

            $tmp_name = $file['tmp_name'];

            $filename = str_replace(\Config::get('kickstart.invalidchars'), '-', $filename);

            //$uploadSuccess = $file->move($destinationPath, $filename);

            @move_uploaded_file($tmp_name, $destinationPath.'/'.$filename);

            $is_image = true;
            $is_audio = false;
            $is_video = false;
            $is_pdf = false;
            $is_doc = false;



            $is_image = $this->isImage($filemime);
            $is_audio = $this->isAudio($filemime);
            $is_video = $this->isVideo($filemime);
            $is_pdf = $this->isPdf($filemime);

            if(!($is_image || $is_audio || $is_video || $is_pdf)){
                $is_doc = true;
            }else{
                $is_doc = false;
            }

            if($is_image){

                $ps = \Config::get('picture.sizes');

                $thumbnail = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['thumbnail']['width'],$ps['thumbnail']['height'])
                    ->save($destinationPath.'/th_'.$filename);

                $medium = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['medium']['width'],$ps['medium']['height'])
                    ->save($destinationPath.'/med_'.$filename);

                $large = \Image::make($destinationPath.'/'.$filename)
                    ->fit($ps['large']['width'],$ps['large']['height'])
                    ->save($destinationPath.'/lrg_'.$filename);

                $full = \Image::make($destinationPath.'/'.$filename)
                    ->save($destinationPath.'/full_'.$filename);

                $image_size_array = array(
                    'thumbnail_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['thumbnail']['prefix'].$filename),
                    'large_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['large']['prefix'].$filename),
                    'medium_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['medium']['prefix'].$filename),
                    'full_url'=> \URL::to('storage/media/'.$rstring.'/'.$ps['full']['prefix'].$filename),
                );

            }else{

                if($is_audio){
                    $thumbnail_url = \URL::to('images/audio.png');
                }elseif($is_video){
                    $thumbnail_url = \URL::to('images/video.png');
                }else{
                    $thumbnail_url = \URL::to('images/media.png');
                }

                $image_size_array = array(
                    'thumbnail_url'=> $thumbnail_url,
                    'large_url'=> '',
                    'medium_url'=> '',
                    'full_url'=> ''
                );
            }


            $item = array(
                    'ns'=>$ns,
                    'parent_id'=> $parent_id,
                    'parent_class'=> $parent_class,
                    'url'=> \URL::to('storage/media/'.$rstring.'/'.$filename),
                    'temp_dir'=> $destinationPath,
                    'file_id'=> $rstring,
                    'is_image'=>$is_image,
                    'is_audio'=>$is_audio,
                    'is_video'=>$is_video,
                    'is_pdf'=>$is_pdf,
                    'is_doc'=>$is_doc,
                    'name'=> $filename,
                    'type'=> $filemime,
                    'size'=> $filesize,
                    'deleted'=>0,
                    'createdDate'=>new \MongoDate(),
                    'lastUpdate'=>new \MongoDate()
                );

            foreach($image_size_array as $k=>$v){
                $item[$k] = $v;
            }


            $item['_id'] = new \MongoId($image_id);

            $im = \Uploaded::find($image_id);
            if($im){

            }else{
                \Uploaded::insertGetId($item);
            }

            $actor = $user->fullname.' : '.$user->email;
            \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'upload image'));

            return \Response::json(array('status'=>'OK', 'timestamp'=>time(), 'message'=>$image_id ));


        }

        $actor = $user->fullname.' : '.$user->email;
        \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'upload image failed'));

        return \Response::json(array('status'=>'ERR:NOFILE', 'timestamp'=>time(), 'message'=>$image_id ));

    }

    private function isAudio($mime){
        return preg_match('/^audio/',$mime);
    }

    private function isVideo($mime){
        return preg_match('/^video/',$mime);
    }

    private function isImage($mime){
        return preg_match('/^image/',$mime);
    }

    private function isPdf($mime){
        return preg_match('/pdf/',$mime);
    }


}