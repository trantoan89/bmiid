<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Http\Controllers\Api\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        
        if(Auth::attempt($credentials)) {

            if(Auth::user()->archive) {
                Auth::logout();
                return response()->json([
                    'status_code' => '2',
                    'message' => 'Your account has been disabled.',
                    //'token' => $token,
                ]);
            }
            else {
Log::info('login ok', ['sessions' => DB::select('select * from sessions')]);
                // $user = Auth::user();

                //$token = $request->user()->createToken('authToken')->plainTextToken;
                // if($user->role == "Admin"){
                //   $token = $request->user()->createToken('authToken')->plainTextToken;
                // }else{
                //   $token = $request->user()->createToken('authToken')->plainTextToken;
                // }

                return response()->json([
                    'status_code' => '0',
                    'message' => 'Correct',
                    //'token' => $token,
                ]);
            }
        
      }else{
        return response()->json([
          'status_code' => '2',
          'message' => 'Incorrect username or password.'
        ]);
      }
    }

}

// if(auth()->user()->tokenCan('dashboard')){
            
          // }else{
          //   return response()->json([
          //     'status_code' => '3',
          //     'message' => 'Unauthorized Access',
          //   ]);
          // }