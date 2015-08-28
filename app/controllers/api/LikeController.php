<?php
namespace Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;


class LikeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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
		$itemid = Input::get('itemid'); 
		$itemtype = Input::get('itemtype'); 
		$userid = Input::get('userid'); 
		$key = Input::get('key'); 
		
		$retVal = array('status' => 'ERR', 'msg' => 'Invalid Session');

		try {
			$user = \Member::where('session_key', '=', $key)->exists();
			if(!$user){
				return Response::json($retVal);
			}
			$media = \Media::where('_id', '=', $itemid)->exists();
			//var_dump($media);
			if(!$media){
				$retVal = array('status' => 'ERR', 'msg' => 'Invalid item.');
				return Response::json($retVal);
			}
			
			$like = new \Likes();
			$like->itemid = $itemid;
			$like->itemtype = $itemtype;
			$like->userid = $userid;
			$like->save();
			
			$newCount = \Likes::where('itemid', '=', $itemid)->count();
			$retVal = array('status' => 'OK', 'count' => $newCount, 'itemid' => $itemid, 'itemtype' => $itemtype);
			return Response::json($retVal);
			
		}
		catch (ModelNotFoundException $e)
		{
		}
		
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
		$retVal = array('status' => 'ERR', 'msg' => 'Invalid Session');
		
		try {
			$user = \Member::where('session_key', '=', $key)->exists();
			if(!$user) return Response::json($retVal);
			
			$like = \Likes::where('itemid', '=', $itemId)->get();
			if($like->count() > 0)
			{
				$retVal = array('status' => 'OK', 'count' => $like->count(), 'itemid' => $itemId, 'itemtype' => $like[0]->itemtype);
			}
			else
			{
				$retVal = array('status' => 'ERR', 'msg' => 'beyond your imagination :)');
			}
			return Response::json($retVal);
		}
		catch (ModelNotFoundException $e)
		{
				
		}
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
