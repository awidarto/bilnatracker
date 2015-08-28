<?php
namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;

class RackapiController extends \BaseController {

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
		//
        $locations = \Rack::get();
        for($i = 0; $i < count($locations);$i++){

                $locations[$i]->extId = $locations[$i]->_id;

                unset($locations[$i]->_id);
                unset($locations[$i]->_token);

                unset($locations[$i]->thumbnail_url);
                unset($locations[$i]->large_url);
                unset($locations[$i]->medium_url);
                unset($locations[$i]->full_url);
                unset($locations[$i]->delete_type);
                unset($locations[$i]->delete_url);
                unset($locations[$i]->filename);
                unset($locations[$i]->filesize);
                unset($locations[$i]->temp_dir);
                unset($locations[$i]->filetype);
                unset($locations[$i]->is_image);
                unset($locations[$i]->is_audio);
                unset($locations[$i]->is_video);
                unset($locations[$i]->fileurl);
                unset($locations[$i]->file_id);
                unset($locations[$i]->caption);
                unset($locations[$i]->files);
                unset($locations[$i]->medium_portrait_url);

                if(isset($locations[$i]->defaultpictures)){
                    $dp = $locations[$i]->defaultpictures;

                    unset($dp['delete_type']);
                    unset($dp['delete_url']);
                    unset($dp['temp_dir']);

                    foreach($dp as $k=>$v){
                        $name = 'picture'.str_replace(' ', '', ucwords( str_replace('_', ' ', $k) ));
                        $locations[$i]->{$name} = $v;
                    }
                    unset($locations[$i]->defaultpictures);

                }else{
                    $locations[$i]->pictureThumbnailUrl = '';
                    $locations[$i]->pictureLargeUrl = '';
                    $locations[$i]->pictureMediumUrl = '';
                    $locations[$i]->pictureFullUrl = '';
                    $locations[$i]->pictureBrchead = '';
                    $locations[$i]->pictureBrc1 = '';
                    $locations[$i]->pictureBrc2 = '';
                    $locations[$i]->pictureBrc3 = '';
                }

                $locations[$i]->createdDate = date('Y-m-d H:i:s',$locations[$i]->createdDate->sec);
                $locations[$i]->lastUpdate = date('Y-m-d H:i:s',$locations[$i]->lastUpdate->sec);

        }

        $actor = $key;
        \Event::fire('log.api',array($this->controller_name, 'get' ,$actor,'rack list'));

        return $locations;
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
