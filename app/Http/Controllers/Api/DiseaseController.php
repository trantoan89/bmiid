<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\CasesCountResource;
use App\Http\Resources\RiskResource;
use Illuminate\Http\Request;
use App\Models\DiseaseModel;
use App\Models\CasesModel;
use App\Models\Risk;
use Illuminate\Support\Facades\Auth;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
      return new DiseaseResource(DiseaseModel::where('homepage', true)->first());
    }

    public function search(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        return DiseaseResource::collection(DiseaseModel::where('disease_name', 'like', '%'.$request->input.'%')->where('archive', $request->archives)->get());
      }
    }

    public function countCases()
    {
      $Query = DiseaseModel::where('homepage', true)->first();
      
      $active = CasesModel::where('disease_id', $Query->id)->where('status', 'approved')->get()->sum('active');
      $deceased = CasesModel::where('disease_id', $Query->id)->where('status', 'approved')->get()->sum('deceased');
      $recovered = CasesModel::where('disease_id', $Query->id)->where('status', 'approved')->get()->sum('recovered');
      $total = $deceased + $recovered + ($active - ($deceased + $recovered));

      return response()->json([
        'active' =>$active,
        'deceased' => $deceased,
        'recovered' => $recovered,
        'total' => $total,
      ]);
    }

    public function countCases2(Request $request)
    {
      $active = CasesModel::where('disease_id', $request->id)->where('status', 'approved')->get()->sum('active');
      $deceased = CasesModel::where('disease_id', $request->id)->where('status', 'approved')->get()->sum('deceased');
      $recovered = CasesModel::where('disease_id', $request->id)->where('status', 'approved')->get()->sum('recovered');
      $total = $deceased + $recovered + ($active - ($deceased + $recovered));

      return response()->json([
        'active' =>$active,
        'deceased' => $deceased,
        'recovered' => $recovered,
        'total' => $total,
      ]);
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
      if(Auth::user()->role === "Admin"){
        $add = DiseaseModel::create([
          'disease_name' => $request->name,
          'description' => $request->description,
        ]);

        $risk = Risk::create([
          'low_risk' => $request->lowRisk,
          'severe_risk' => $request->severeRisk,
          'high_risk' => $request->highRisk,
          'disease_id' => $add->id,
        ]);

        if($add && $risk){
          return response()->json([
            'status_code' => '200',
            'message' => 'Disease successfully added',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error in adding disease',
          ]);
        }
      }else{
        return response()->json([
          'status_code' => '401',
          'message' => 'Unauthorized access'
        ]);
      }
    }

    public function archive(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        $disease = DiseaseModel::find($request->id);
        $disease->archive = !$request->archive;
        if($disease->save()){
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
      //return RiskResource::collection(Risk::all());
      return DiseaseResource::collection(DiseaseModel::with('risk')->get());
      // if(Auth::check()){
      //   if(Auth::user()->role === "Admin"){
      //     return DiseaseResource::collection(DiseaseModel::all()->with('risk'));
      //   }else{
      //     return DiseaseResource::collection(DiseaseModel::where('archive','==', false)->get());
      //   }
      // }else{
      //   return DiseaseResource::collection(DiseaseModel::where('archive','==', false)->get());
      // }
    }

    public function allDisease(){
      return DiseaseResource::collection(DiseaseModel::where('archive','==', false)->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
      $current = DiseaseModel::where('homepage', true)->first();
      $current->homepage = false;

      if(Auth::user()->role === "Admin"){
        if($current->save()){
          $change = DiseaseModel::find($request->id);
          $change->homepage = true;

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
      if(Auth::user()->role === "Admin"){
        $disease = DiseaseModel::find($request->id);
        $disease->disease_name = $request->name;
        $disease->description = $request->description;

        

        if($disease->save()){
          $risk = Risk::find($request->risk_id);
          $risk->low_risk = $request->lowRisk;
          $risk->severe_risk = $request->severeRisk;
          $risk->high_risk = $request->highRisk;
          $risk->save();

          return response()->json([
            'status_code' => '200',
            'message' => 'Disease successfully updated',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error in adding disease',
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
      if(Auth::user()->role === "Admin"){
        $delete = DiseaseModel::destroy($request->id);
        if($delete){
          return response()->json([
            'status_code' => '200',
            'message' => 'Disease successfully deleted',
          ]);
        }else{
          return response()->json([
            'status_code' => '204',
            'message' => 'Error in deleting disease',
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
