<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use App\Models\NewsModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return new  NewsResource(NewsModel::where('show', true)->first());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $user = Auth::user();
      if($user->role == "Admin"){
        $create = NewsModel::create([
          'news_date' => $request->date,
          'news_description' => $request->content,
        ]);

        if($create){
          return response()->json([
            "status_code" => "0",
            "message" => "Successfully added News",
          ]);
        }else{
          return response()->json([
            "status_code" => "2",
            "message" => "Failed to add News",
          ]);
        }
      }else{
        return response()->json([
          "status_code" => "1",
          "message" => "Unauthorized access",
        ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      return NewsResource::collection(NewsModel::orderBy('news_date','DESC')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
      
      $current = NewsModel::where('show', true)->first();
      $current->show = false;

      if(Auth::user()->role === "Admin"){
        if($current->save()){
          $change = NewsModel::find($request->id);
          $change->show = true;
  
          if($change->save()){
            return response()->json([
              'status_code' => '200',
              'message' => 'Successfully change shown in homepage'
            ]);
          }else{
            return response()->json([
              'status_code' => '403',
              'message' => 'Error in changing current news'
            ]);
          }
        }else{
          return response()->json([
            'status_code' => '400',
            'message' => 'Error in changing current news'
          ]);
        }
      }else{
        return response()->json([
          'status_code' => '401',
          'message' => 'Unauthorized access'
        ]);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
      $newsData = NewsModel::find($request->id);
      $newsData->news_date = $request->date;
      $newsData->news_description = $request->content;

      if(Auth::user()->role === "Admin"){
        if($newsData->save()){
          return response()->json([
            "status_code" => "0",
            "message" => "Successfully updated News",
          ]);
        }else{
          return response()->json([
            "status_code" => "1",
            "message" => "Error in updating News details",
          ]);
        }
      }else{
        return response()->json([
          'status_code' => '401',
          'message' => 'Unauthorized access'
        ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $delete = NewsModel::destroy($request->ids);

      if(Auth::user()->role === "Admin"){
        if($delete){
          return response()->json([
            'status_code' => '200 ',
            'message' => 'News successfully deleted',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error, No content found',
          ]);
        }
      }else{
        return response()->json([
          'status_code' => '401',
          'message' => 'Unauthorized access'
        ]);
      }
    }
}
