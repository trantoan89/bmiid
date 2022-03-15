<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function authenticate(Request $request)
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
                ]);
            }
            else {
                $request->session()->regenerate();
 
                return redirect()->intended('dashboard');

            }
        
      }else{
        return response()->json([
          'status_code' => '2',
          'message' => 'Incorrect username or password.'
        ]);
      }
    }

}
