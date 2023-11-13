<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\MenuController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\File\FileController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('user', function(Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function() {
    return 'hello world';
});

Route::get("users", function() {
    return User::all();
});

Route::get('error_route', function() {
    return response(['error' => 'unauthenticated'], 400);
});


//auth
Route::group(['namespace' => 'Api\Auth'], function() {
    //login
    Route::post('login', [AuthController::class, 'login']);
    //logout, middleware supaya hanya user dengan API token yg valid yg bisa akses
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    //register
    Route::post('register', [AuthController::class, 'register']);
});


Route::post('createStorageFolders', [FileController::class, 'initFolders']);

//harus bawa bearer token, dan user nya harus admin, utk akses route2 ini
Route::group(['middleware' => ['auth:sanctum', 'can:admin-only']], function () {
    Route::post('insertMenu', [MenuController::class, 'insertMenu']);
    Route::post('updateOrderStatus', [OrderController::class, 'updateOrderStatus']);
});

//order
Route::post('createOrder', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');