<?php
namespace Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;


class ImgapiController extends \BaseController {

    public $controller_name = '';

    public $objmap = array(
        'ns' => 'ns',
        'parentId' => 'parent_id',
        'parentClass' => 'parent_class',
        'url' => 'url',
        'fileId' => 'file_id',
        'isImage' => 'is_image',
        'isAudio' => 'is_audio',
        'isVideo' => 'is_video',
        'isPdf' => 'is_pdf',
        'isDoc' => 'is_doc',
        'name' => 'name',
        'type' => 'type',
        'size' => 'size',
        'createdDate' => 'createdDate',
        'lastUpdate' => 'lastUpdate',
        'pictureFullUrl'=> 'full_url',
        'pictureLargeUrl'=> 'large_url',
        'pictureMediumUrl'=> 'medium_url',
        'pictureThumbnailUrl'=> 'thumbnail_url'
    );

    public $exclude = array(
        'ns',
        'url',
        'name',
        'type',
        'size'
    );

    public function  __construct()
    {
        //$this->model = "Member";
        $this->controller_name = strtolower( str_replace('Controller', '', get_class()) );

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $key = Input::get('key');

        $user = \Apiauth::user($key);

        $id = Input::get('id');
        $class = Input::get('cls');

        if(is_null($id) || $id == 'all'){
            $cimages = \Uploaded::all();
        }else{
            $cimages = \Uploaded::where('parent_id', $id)
                            ->where('parent_class', $class)
                            ->get();
        }

        $images = array();

        for($i = 0; $i < count($cimages);$i++){
            if( is_null($cimages[$i]->parent_id) || is_null($cimages[$i]->parent_class) || $cimages[$i]->parent_id == '' || $cimages[$i]->parent_class == '' ){

            }else{
                $images[] = $cimages[$i];
            }
        }

        for($i = 0; $i < count($images);$i++){


                unset($images[$i]->_id);
                unset($images[$i]->_token);

                foreach($this->objmap as $k=>$v){
                    $images[$i]->{$k} = $images[$i]->{$v};
                    if(!in_array($k, $this->exclude)){
                        unset($images[$i]->{$v});
                    }
                }

                unset($images[$i]->delete_url);
                unset($images[$i]->delete_type);

                $images[$i]->extId = $images[$i]->parentId;

                if(!isset($images[$i]->deleted)){
                    $images[$i]->deleted = 0;
                }

                if( isset($images[$i]->createdDate) && !is_string($images[$i]->createdDate) ){
                    $images[$i]->createdDate = date('Y-m-d H:i:s',$images[$i]->createdDate->sec);
                }

                if(isset($images[$i]->lastUpdate) && !is_string($images[$i]->lastUpdate)){
                    $images[$i]->lastUpdate = date('Y-m-d H:i:s',$images[$i]->lastUpdate->sec);
                }

                if(isset($images[$i]->isDoc) && is_bool($images[$i]->isDoc)){
                    $images[$i]->isDoc = ($images[$i]->isDoc)?1:0;
                }

                if(isset($images[$i]->isImage) && is_bool($images[$i]->isImage)){
                    $images[$i]->isImage = ($images[$i]->isImage)?1:0;
                }
                if(isset($images[$i]->isVideo) && is_bool($images[$i]->isVideo)){
                    $images[$i]->isVideo = ($images[$i]->isVideo)?1:0;
                }
                if(isset($images[$i]->isAudio) && is_bool($images[$i]->isAudio)){
                    $images[$i]->isAudio = ($images[$i]->isAudio)?1:0;
                }
                if(isset($images[$i]->isPdf) && is_bool($images[$i]->isPdf)){
                    $images[$i]->isPdf = ($images[$i]->isPdf)?1:0;
                }



        }

        $actor = $user->fullname.' : '.$user->email;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'get image list'));

        return $images;
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $key = Input::get('key');

        $user = \Apiauth::user($key);

        $files = Input::file('files');

        $parent_id = Input::get('parid');

        $parent_class = Input::get('parclass');

        $image_id = Input::get('img');

        $ns = Input::get('ns');

        $file = $files[0];

        $rstring = str_random(15);

        $destinationPath = realpath('storage/media').'/'.$rstring;

        $filename = $file->getClientOriginalName();
        $filemime = $file->getMimeType();
        $filesize = $file->getSize();
        $extension =$file->getClientOriginalExtension(); //if you need extension of the file

        $filename = str_replace(Config::get('kickstart.invalidchars'), '-', $filename);

        $uploadSuccess = $file->move($destinationPath, $filename);


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

            $ps = Config::get('picture.sizes');

            $thumbnail = Image::make($destinationPath.'/'.$filename)
                ->fit($ps['thumbnail']['width'],$ps['thumbnail']['height'])
                ->save($destinationPath.'/th_'.$filename);

            $medium = Image::make($destinationPath.'/'.$filename)
                ->fit($ps['medium']['width'],$ps['medium']['height'])
                ->save($destinationPath.'/med_'.$filename);

            $large = Image::make($destinationPath.'/'.$filename)
                ->fit($ps['large']['width'],$ps['large']['height'])
                ->save($destinationPath.'/lrg_'.$filename);

            $full = Image::make($destinationPath.'/'.$filename)
                ->save($destinationPath.'/full_'.$filename);

            $image_size_array = array(
                'thumbnail_url'=> URL::to('storage/media/'.$rstring.'/'.$ps['thumbnail']['prefix'].$filename),
                'large_url'=> URL::to('storage/media/'.$rstring.'/'.$ps['large']['prefix'].$filename),
                'medium_url'=> URL::to('storage/media/'.$rstring.'/'.$ps['medium']['prefix'].$filename),
                'full_url'=> URL::to('storage/media/'.$rstring.'/'.$ps['full']['prefix'].$filename),
            );

        }else{

            if($is_audio){
                $thumbnail_url = URL::to('images/audio.png');
            }elseif($is_video){
                $thumbnail_url = URL::to('images/video.png');
            }else{
                $thumbnail_url = URL::to('images/media.png');
            }

            $image_size_array = array(
                'thumbnail_url'=> $thumbnail_url,
                'large_url'=> '',
                'medium_url'=> '',
                'full_url'=> ''
            );
        }


        $fileitems = array();

        if($uploadSuccess){
            $item = array(
                    'ns'=>$ns,
                    'parent_id'=> $parent_id,
                    'parent_class'=> $parent_class,
                    'url'=> URL::to('storage/media/'.$rstring.'/'.$filename),
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
                    'createdDate'=>new MongoDate(),
                    'lastUpdate'=>new MongoDate()
                );

            foreach($image_size_array as $k=>$v){
                $item[$k] = $v;
            }

            $item['_id'] = new MongoId($image_id);

            \Uploaded::insertGetId($item);

            //$fileitems[] = $rstring.'/'.$filename;

        }


        $actor = $user->fullname.' : '.$user->email;
        \Event::fire('log.api',array($this->controller_name, 'post' ,$actor,'upload image'));

        return \Response::json(array('status'=>'OK', 'timestamp'=>time(), 'message'=>$image_id ));

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $itemId
     * @param string $key
     * @return Response
     */
    public function show($itemId, $key)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
