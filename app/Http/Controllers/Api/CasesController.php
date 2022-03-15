<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CasesResource;
use Illuminate\Http\Request;
use App\Models\CasesModel;
use App\Models\DiseaseModel;
use App\Models\BarangayModel;
use App\Models\Risk;
use Illuminate\Support\Facades\Auth;

class CasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::user()->role === "Admin"){
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'approved')->get());
      }else{
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'approved')->where('created_by', Auth::user()->id)->get());
      }
    }

    public function pendingCase(){
      if(Auth::user()->role === "Admin"){
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'pending')->get());
      }else{
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'pending')->where('created_by', Auth::user()->id)->get());
      }
    }

    public function declineCase(){
      if(Auth::user()->role === "Admin"){
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'declined')->get());
      }else{
        return CasesResource::collection(CasesModel::with('disease')->with('barangay')->with('createdBy')->with('approvedBy')->where('status', 'declined')->where('created_by', Auth::user()->id)->get());
      }
    }

    public function totalBarangayCase()
    {
      $disease = DiseaseModel::all();
      $barangay = BarangayModel::all();
      $arr = [];

      foreach($barangay as $b){
        foreach($disease as $d){
          $active = CasesModel::where('barangay_id', $b->id)->where('disease_id', $d->id)->where('status', 'approved')->get()->sum('active');
          $deceased = CasesModel::where('barangay_id', $b->id)->where('disease_id', $d->id)->where('status', 'approved')->get()->sum('deceased');
          $recovered = CasesModel::where('barangay_id', $b->id)->where('disease_id', $d->id)->where('status', 'approved')->get()->sum('recovered');
          $arr[] = array(
            'barangay_name' => $b->barangay_name,
            'disease_name' => $d->disease_name,
            'active' => $active,
            'deceased' => $deceased,
            'recovered' => $recovered,
          );
        }
      }

      return response()->json([$arr]);
    }

    public function totalDiseaseCase()
    {
      $disease = DiseaseModel::all();
      $arr = [];

      foreach($disease as $d){
        $active = CasesModel::where('disease_id', $d->id)->where('status', 'approved')->get()->sum('active');
        $deceased = CasesModel::where('disease_id', $d->id)->where('status', 'approved')->get()->sum('deceased');
        $recovered = CasesModel::where('disease_id', $d->id)->where('status', 'approved')->get()->sum('recovered');
        $arr[] = array(
          'disease_name' => $d->disease_name,
          'active' => $active,
          'deceased' => $deceased,
          'recovered' => $recovered,
        );
      }

      return response()->json([$arr]);
    }

    public function mapSearch(Request $request)
    {
      if($request->barangay_id === "Baguio"){
        $active = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('active');
        $deceased = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('deceased');
        $recovered = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('recovered');
      }else{
        $active = CasesModel::where('disease_id', $request->disease_id)->where('barangay_id', $request->barangay_id)->where('status', 'approved')->get()->sum('active');
        $deceased = CasesModel::where('disease_id', $request->disease_id)->where('barangay_id', $request->barangay_id)->where('status', 'approved')->get()->sum('deceased');
        $recovered = CasesModel::where('disease_id', $request->disease_id)->where('barangay_id', $request->barangay_id)->where('status', 'approved')->get()->sum('recovered');
      }
    
      return response()->json([
        'active' => $active,
        'deceased' => $deceased,
        'recovered' => $recovered,
      ]);
    }

    public function mapMarker(Request $request){
      $barangay = BarangayModel::where('archive','==', false)->get();
      $arr = [];

      foreach($barangay as $b){
        $color;

        $active = CasesModel::where('barangay_id', $b->id)->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('active');
        $deceased = CasesModel::where('barangay_id', $b->id)->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('deceased');
        $recovered = CasesModel::where('barangay_id', $b->id)->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('recovered');
        
        $computed = $active - ($deceased + $recovered);

        $risk = Risk::where('disease_id', $request->disease_id)->first();

        $computedLow = $b->population * ($risk->low_risk/100);
        $computedSevere = $b->population * ($risk->severe_risk/100);
        $computedHigh = $b->population * ($risk->high_risk/100);

        // if($computed === 0){
        //   $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
        // }else if($computed > 0 && $computed < 10){
        //   $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
        // }else if($computed > 9 && $computed < 20){
        //   $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png';
        // }else if($computed > 19){
        //   $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
        // }

        if($computed === 0){
          $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
        }else if($computed > 0 && $computed < $computedLow){
          $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
        }else if($computed > $computedLow && $computed < $computedHigh){
          $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/orange-dot.png';
        }else if($computed > $computedHigh){
          $arr[$b->id] = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
        }
      }

      return $arr;
    }

    public function barGraph(Request $request){

      $arr = [];

      for($x = 1; $x <= 12 ; $x++){
        $active = CasesModel::whereBetween('case_date', [$request->years.'-'. $x .'-01', $request->years.'-'. $x .'-31'])->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('active');
        $deceased = CasesModel::whereBetween('case_date', [$request->years.'-'. $x .'-01', $request->years.'-'. $x .'-31'])->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('deceased');
        $recovered = CasesModel::whereBetween('case_date', [$request->years.'-'. $x .'-01', $request->years.'-'. $x .'-31'])->where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('recovered');
        
        $computed = $active - ($deceased + $recovered);
        $arr[$x] = $computed;
      }
      
      return $arr;
    }

    public function pieGraph(Request $request){
      $active = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('active');
      $deceased = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('deceased');
      $recovered = CasesModel::where('disease_id', $request->disease_id)->where('status', 'approved')->get()->sum('recovered');

      $total = $deceased + $recovered + ($active - ($deceased + $recovered));

      return response()->json([
        'active' => $active,
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
      $add;
      
      if(Auth::user()->role === 'Admin'){
        $add = CasesModel::create([
          'case_date' => $request->date,
          'active' => $request->active,
          'deceased' => $request->deceased,
          'recovered' => $request->recovered,
          'barangay_id' => $request->barangayID,
          'disease_id' => $request->disease,
          'status' => 'approved',
          'created_by' => Auth::user()->id,
          'approved_by' => Auth::user()->id,
        ]);
      }else{
        $add = CasesModel::create([
          'case_date' => $request->date,
          'active' => $request->active,
          'deceased' => $request->deceased,
          'recovered' => $request->recovered,
          'barangay_id' => $request->barangayID,
          'disease_id' => $request->disease,
          'status' => 'pending',
          'created_by' => Auth::user()->id,
        ]);
      }

      if($add){
        return response()->json([
          'status_code' => '200',
          'message' => 'Successfully added new case.',
        ]);
      }else{
        return response()->json([
          'status_code' => '201',
          'message' => 'Error in adding new case.',
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
      //
      $data = CasesModel::where(['barangay_id' => $request->barangay, 'disease_id' => $request->disease]);
      
      return CasesResource::collection(CasesModel::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
      $update = CasesModel::find($request->id);
      $update->active = $request->active;
      $update->deceased = $request->deceased;
      $update->recovered = $request->recovered;

      if($update->save()){
        return response()->json([
          'status_code' => '200',
          'message' => 'updated',
        ]);
      }else{
        return response()->json([
          'status_code' => '201',
          'message' => 'error',
        ]);
      }
    }

    public function caseStatus(Request $request){

      $update = CasesModel::find($request->id);
      $update->status = $request->status;
      $update->approved_by = Auth::user()->id;
      
      if($update->save()){
        return response()->json([
          'status_code' => '200',
          'message' => 'updated',
        ]);
      }else{
        return response()->json([
          'status_code' => '201',
          'message' => 'error',
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
      $delete = CasesModel::destroy($request->id);
      if($delete){
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
    }
}
