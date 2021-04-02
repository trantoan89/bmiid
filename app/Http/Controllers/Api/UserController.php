<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		  return UserResource::collection(User::where('id','!=',Auth::user()->id)->get());
    }

    public function search(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        return UserResource::collection(User::where('first_name', 'like', '%'.$request->input.'%')->orWhere('last_name', 'like', '%'.$request->input.'%')->orWhere('username', 'like', '%'.$request->input.'%')->get());
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
    public function store(Request $request)
    {
			$emailCheck = User::where('email', $request->email)->get();

			if($emailCheck->count() > 0 ){
				return response()->json([
					'status_code' => '202',
					'message' => 'Email is already taken.' 
				]);
			}else{
				$create = User::create([
					'username' => $request->username, 
					'first_name' => $request->firstName,
					'last_name' => $request->lastName,
					'role' => $request->role,
					'contact_number' => $request->contact,
					'email' => $request->email, 
					'password' => Hash::make('123456'),
				]);

				if($create){
					return response()->json([
						'status_code' => '200',
						'message' => 'Account successfully added.' 
					]);
				}else{
					return response()->json([
						'status_code' => '201',
						'message' => 'Error in adding new account.' 
					]);
				}
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function archive(Request $request)
    {
      if(Auth::user()->role === "Admin"){
        $user = User::find($request->id);
        $user->archive = !$request->archive;
        if($user->save()){
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

    public function destroy(Request $request)
    {
      $delete = User::destroy($request->id);

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
