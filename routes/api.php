<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\DiseaseController;
use App\Http\Controllers\Api\BarangayController;
use App\Http\Controllers\Api\CasesController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/all_news', function (Request $request) {
//   if(auth()->user()->tokenCan('server:update')){
//     return $request->user();
//   }else{
//     return repsonse()->json([
//       'message' => 'invalid',
//     ]);
//   }
// });

Route::middleware('auth:sanctum')->group(function (){
  Route::get('/all_news', [NewsController::class, 'show']);
  Route::get('/token', [AuthController::class, 'getToken']);

  //News Routes
  Route::post('/add_news', [NewsController::class, 'store']);
  Route::post('/update_news', [NewsController::class, 'update']);
  Route::post('/news_delete', [NewsController::class, 'destroy']);
  Route::post('/news_show', [NewsController::class, 'edit']);

  //Barangay Rotues
  Route::post('/delete_barangay', [BarangayController::class, 'destroy']);
  Route::post('/update_pop', [BarangayController::class, 'edit']);
  Route::post('/update_barangay', [BarangayController::class, 'update']);
  Route::post('/create_barangay', [BarangayController::class, 'store']);
  Route::post('/barangay_archive', [BarangayController::class, 'archive']);
  Route::post('/barangay_search', [BarangayController::class, 'search']);

  
  //Disease Routes
  Route::post('/add_disease', [DiseaseController::class, 'store']);
  Route::post('/disease_update', [DiseaseController::class, 'update']);
  Route::post('/disease_delete', [DiseaseController::class, 'destroy']);
  Route::post('/disease_archive', [DiseaseController::class, 'archive']);
  Route::post('/disease_show', [DiseaseController::class, 'edit']);
  Route::post('/disease_search', [DiseaseController::class, 'search']);

  //Cases Routes
  Route::post('/add_case', [CasesController::class, 'store']);
  Route::get('/all_case', [CasesController::class, 'index']);
  Route::post('/case_update', [CasesController::class, 'update']);
  Route::post('/case_status', [CasesController::class, 'caseStatus']);
  Route::post('/case_delete', [CasesController::class, 'destroy']);
  Route::get('/total_barangay_case', [CasesController::class, 'totalBarangayCase']);
  Route::get('/total_disease_case', [CasesController::class, 'totalDiseaseCase']);
  Route::get('/pending_case', [CasesController::class, 'pendingCase']);
  Route::get('/decline_case', [CasesController::class, 'declineCase']);
  Route::post('/bar_graph', [CasesController::class, 'barGraph']);
  Route::post('/pie_graph', [CasesController::class, 'pieGraph']);
  
  //account routes
  Route::post('/add_account', [UserController::class, 'store']);
  Route::get('/users', [UserController::class, 'index']);
  Route::post('/user_archive', [UserController::class, 'archive']);
  Route::post('/user_delete', [UserController::class, 'destroy']);
  Route::post('/user_search', [UserController::class, 'search']);

});


Route::get('/news', [NewsController::class, 'index']);

//disease routes
Route::get('/disease', [DiseaseController::class, 'index']);
Route::get('/disease-names', [DiseaseController::class, 'show']);
Route::get('/alldisease', [DiseaseController::class, 'allDisease']);

//cases routes
Route::get('/count-cases', [DiseaseController::class, 'countCases']);
Route::post('/count-cases2', [DiseaseController::class, 'countCases2']);
Route::post('/chart-form', [CasesController::class, 'show']);
Route::post('/map_filter', [CasesController::class, 'mapSearch']);
Route::post('/map_marker', [CasesController::class, 'mapMarker']);

//barangay routes
Route::get('/barangay', [BarangayController::class, 'index']);
Route::get('/allbarangay', [BarangayController::class, 'allBarangays']);


Route::post('/auth', [AuthController::class, 'login']);


