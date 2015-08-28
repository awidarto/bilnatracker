<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class AssetapiController extends \BaseController {

    public $controller_name = '';

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

        $assets = \Asset::get();
        for($i = 0; $i < count($assets);$i++){

                $assets[$i]->extId = $assets[$i]->_id;

                unset($assets[$i]->_id);
                unset($assets[$i]->_token);

                unset($assets[$i]->thumbnail_url);
                unset($assets[$i]->large_url);
                unset($assets[$i]->medium_url);
                unset($assets[$i]->full_url);
                unset($assets[$i]->delete_type);
                unset($assets[$i]->delete_url);
                unset($assets[$i]->filename);
                unset($assets[$i]->filesize);
                unset($assets[$i]->temp_dir);
                unset($assets[$i]->filetype);
                unset($assets[$i]->is_image);
                unset($assets[$i]->is_audio);
                unset($assets[$i]->is_video);
                unset($assets[$i]->fileurl);
                unset($assets[$i]->file_id);
                unset($assets[$i]->caption);
                unset($assets[$i]->files);
                unset($assets[$i]->medium_portrait_url);

                if(isset($assets[$i]->defaultpictures)){
                    $dp = $assets[$i]->defaultpictures;

                    unset($dp['delete_type']);
                    unset($dp['delete_url']);
                    unset($dp['temp_dir']);

                    foreach($dp as $k=>$v){
                        $name = 'picture'.str_replace(' ', '', ucwords( str_replace('_', ' ', $k) ));
                        $assets[$i]->{$name} = $v;
                    }
                    unset($assets[$i]->defaultpictures);

                }else{
                    $assets[$i]->pictureThumbnailUrl = '';
                    $assets[$i]->pictureLargeUrl = '';
                    $assets[$i]->pictureMediumUrl = '';
                    $assets[$i]->pictureFullUrl = '';
                    $assets[$i]->pictureBrchead = '';
                    $assets[$i]->pictureBrc1 = '';
                    $assets[$i]->pictureBrc2 = '';
                    $assets[$i]->pictureBrc3 = '';
                }

                $assets[$i]->createdDate = date('Y-m-d H:i:s',$assets[$i]->createdDate->sec);
                $assets[$i]->lastUpdate = date('Y-m-d H:i:s',$assets[$i]->lastUpdate->sec);


        }

        $actor = $key;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'logged out'));

        return $assets;
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
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $in = Input::get();
        if(isset($in['key']) && $in['key'] != ''){
            print $in['key'];
        }else{
            print 'no key';
        }
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
