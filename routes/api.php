<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\RoomClassController;
use App\Http\Controllers\CustomerServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user',[AuthController::class,'store']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/otp',[AuthController::class,'sendOTP']);
Route::post('/verify-otp',[AuthController::class,'verifyCode']);
Route::post('/forget-Password',[AuthController::class,'forgetPassword']);
Route::post('/reset-Password',[AuthController::class,'resetPassword']);
Route::post('/verify-email',[AuthController::class,'verifyEmail']);


Route::get('/roomclass',[RoomClassController::class,'getRoomClasses']);

route::group(['middleware'=>'auth:api'],function(){
    Route::post('customer-services', [CustomerServiceController::class, 'store']);
    Route::get('customer-services', [CustomerServiceController::class, 'load']);
    Route::post('/roomclass',[RoomClassController::class,'addRoom']);
    Route::post('/question',[QuestionsController::class,'store']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
