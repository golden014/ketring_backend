<?php

use App\Http\Controllers\Api\AllocationController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\CarouselController;
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
    //order
    Route::post('updateOrderStatus', [OrderController::class, 'updateOrderStatus']);
    Route::get('getOngoingOrders', [OrderController::class, 'getOngoingOrders']);
    //menu
    Route::post('allocateMenu', [AllocationController::class, 'allocateMenu']);
    Route::post('insertMenu', [MenuController::class, 'insertMenu']);
    //carousel
    Route::post('addNewCarousel', [CarouselController::class, 'addNewCarousel']);
    Route::get('getAllCarousel', [CarouselController::class, 'getAllCarousel']);
    Route::post('deleteCarousel', [CarouselController::class, 'deleteCarousel']);
    Route::get('deleteAllCarousel', [CarouselController::class, 'deleteAllCarousel']);
});

//order
Route::post('createOrder', [OrderController::class, 'createOrder'])->middleware('auth:sanctum');

//get all menu
Route::post('getMenuWithPagination', [MenuController::class, 'getMenuWithPagination']);
Route::post('getMenuCount', [MenuController::class, 'getMenuCount']);
Route::get('getUpcomingMenu', [AllocationController::class, 'getUpcomingMenu']);
Route::get('getAllMenuNames', [MenuController::class, 'getAllMenuNames']);
