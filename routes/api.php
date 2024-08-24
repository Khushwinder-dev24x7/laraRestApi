<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test',function(){
    return 'Hello World';
 });

 Route::post('/test',function(){
    return response()->json("Post url hit");
 });

 Route::delete('/user/{id}', function($id){
    return response("delete". $id, 200);
 });
Route::put('/user/{id}',function($id){
    return response("put". $id, 200);
});
