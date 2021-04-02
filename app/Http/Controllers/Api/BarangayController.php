<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BarangayResource;
use Illuminate\Http\Request;
use App\Models\BarangayModel;
use Illuminate\Support\Facades\Auth;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::check()){
        if(Auth::user()->role === "Admin"){
          return BarangayResource::collection(BarangayModel::all());
        }else{
          return BarangayResource::collection(BarangayModel::where('archive','==', false)->get());
        }
      }else{
        return BarangayResource::collection(BarangayModel::where('archive','==', false)->get());
      }
    }

    public function allBarangays(){
      return BarangayResource::collection(BarangayModel::where('archive','==', false)->get());
    }

    public function search(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        return BarangayResource::collection(BarangayModel::where('barangay_name', 'like', '%'.$request->input.'%')->get());
      }else{
        return BarangayResource::collection(BarangayModel::where('barangay_name', 'like', '%'.$request->input.'%')->where('archive','==', false)->get());
      }
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

    public function archive(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        $barangay = BarangayModel::find($request->ID);
        $barangay->archive = !$request->archive;
        if($barangay->save()){
          return response()->json([
            'status_code' => '200',
            'message' => 'Barangay successfully archived',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error in archiving barangay',
          ]);
        }
      }else{
        return response()->json([
          'status_code' => '401',
          'message' => 'Unauthorized access'
        ]);
      }
    }

    public function store(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        $create = BarangayModel::create([
          'barangay_name' => $request->name,
          'latitude' => $request->lat,
          'longitude' => $request->lng,
          'population' => $request->population,
        ]);

        if($create){
          return response()->json([
            'status_code' => '200',
            'message' => 'Barangay successfully created',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error in creating new record',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
      $brngy = BarangayModel::find($request->id);

      if(Auth::user()->role === "Admin"){
        $brngy->population = $request->population;
        if($brngy->save()){
          return response()->json([
            'status_code' => '200',
            'message' => 'Barangay successfully updated',
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
      if(Auth::user()->role === "Admin"){
        $barangay = BarangayModel::find($request->ID);
        $barangay->barangay_name = $request->name;
        $barangay->latitude = $request->lat;
        $barangay->longitude = $request->lng;
        
        if($barangay->save()){
          return response()->json([
            'status_code' => '200',
            'message' => 'Barangay successfully updated',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if(Auth::user()->role === "Admin"){
            $delete = BarangayModel::destroy($request->id);
            if($delete){
                return response()->json([
                    'status_code' => '200 ',
                    'message' => 'Barangay successfully deleted',
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
