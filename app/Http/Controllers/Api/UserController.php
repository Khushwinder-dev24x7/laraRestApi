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
                'password' => Hash::make($request->password)
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
        }
    }
}
