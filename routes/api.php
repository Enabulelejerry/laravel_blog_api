<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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


Route::post('/register',[AuthController::class, 'Register']);
Route::post('/login',[AuthController::class, 'Login']);

Route::post('/add/post',[PostController::class,'AddPost']);
Route::group(['middleware'=>['auth:api']],function(){
    Route::post('/user/update',[AuthController::class,'updateUser']);  
    Route::post('/user/delete',[AuthController::class,'deleteUser']);
    
});
