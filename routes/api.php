<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Models\User;
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

Route::get('test', function() {
    return 'hello world';
});

Route::get("users", function() {
    return User::all();
});

Route::group(['namespace' => 'Api\Auth'], function() {
    //login
    Route::post('login', [AuthController::class, 'login']);
    //logout, middleware supaya hanya user dengan API token yg valid yg bisa akses
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

