<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($abc)
    {
        //f
        // $user = User::all();
        $query = User::select("email","name");

        if($abc == 1){
            $query->where("status",1);
        }
        elseif($abc == 0)
        {

        }
        else{
             return response()->json([
                "message" => "invalid Paramenter",
                "status" => 0,
             ],400);
        }



        // $user = User::select("email", "name")->where("status",1)->get();
        $user = $query->get();
        if (count($user) > 0){
            // user exists
            // p($user);

            $response =[
                "message" => count($user). "User Found",
                "status" => 1,
                "data" => $user
            ];
        }
        else
        {
            $response =[
                "message" => count($user). "User Found",
                "status" => 0
            ];
        }
        // return response()->json($response,200);
        return response()->json($response,200);

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

        $validator = validator::make($request->all(),[
            'name' => ['required'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:8'],
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }
        else
        {
            $data=[
                'name' => $request->name,
                'email' => $request->email,
                // 'password' => Hash::make($request->password)
                'password' => $request->password
            ];
            // p($data);
            DB::beginTransaction();
            try{
                 $user = User::create($data);
                 DB::commit();
            }
            catch(\Exception $e){
                 DB::rollBack();
                 p($e->getMessage());
                 $user = null;
            }
            if($user != null){
                return response()->json(['User Registered Successfuly'],200);
            }
            else{
                return response()->json([
                    'message'=> 'Internal Server Error'
                ],500);
            }
        }
        //
        // p($request->all());
        // $request->validate([
        //     'name' => ['required'],
        //     'email' => ['required','email'],
        //     'password' => ['required','min:8'],
        // ]);

        p($request->all());
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
        $user= User::find($id);
        if(is_null($user)){
           $response =[
            "message" => "User Not Found",
            "status" => 0,
           ];
        }
        else{
             $response =[
                "message" => "user found",
                "status" => 1,
                "data" => $user
             ];
        }
         return response()->json($response,200);
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
        $user = User::find($id);
        // p($request->all());
        // die;
        if(is_null($user)){
             return response()->json([
                "meassaage" => "User Not Found",
                "status" => 0
             ],404);
        }
        else
        {
            DB::beginTransaction();
            try{
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->save();
            DB::commit();
            }
            catch(\Exception $e){
               DB::rollBack();
               $user = null;
            }
            if(is_null($user)){
                return response()->json([
                    "message" => "Internal server Error",
                    "status" => 0,
                    "error-msg" => $e->getMessage()
                ],500);
            }
            else{
                return response()->json([
                    "message" => "User Updated",
                    "status" => 1
                ],200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        if(is_null($user)){
            $response = [
                "message" => "User Not Found",
                "status" => 0
            ];
            $resCode = 404;
        }
        else{
            DB::beginTransaction();

            try{
                $user->delete();
                DB::commit();
                $response =[
                    "message" => "User Deleted",
                    "status" => 1
                ];
                $resCode =200;
            }
            catch(\Exception $e){
                DB::rollBack();
                $response = [
                    "message" => "Error",
                    "status" =>0
                ];
                $resCode =500;
            }
        }
        return response()->json($response,$resCode);
    }

    public function passChange(Request $request, $id)
    {

        dd($id);
        die;

        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                "message" => "User Not found",
                "status" => 0
            ],500);
        }
        else
        {
          if($user->password == $request['old_pass']){
             DB::beginTransaction();
             try{
                  $user->password = $request['password'];
                  $user->save();
                  DB::commit();
             }
             catch(\Exception $e){
                    $user = null;
                    DB::rollBack();

             }

             if(is_null($user)){
                return response()->json(
                    [
                      "meassage" =>  "Internal server Error",
                      "status" => 0,
                      "erMsg" => $e->getMessage()
                    ],500
                );
             }
             else{
                return response()->json([
                    "message" => "Pass Updated",
                    "status" => 1
                ],200);
             }
          }
        }
    }
}
