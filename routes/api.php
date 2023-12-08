<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\Permissions\RolesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProductController;
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

Route::get('/', function () {
    return ['message' => 'Welcome to STORE APP Backend'];
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{dataId}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store'])->middleware('moderatorRole');
    Route::put('/{dataId}', [CategoryController::class, 'update'])->middleware('moderatorRole');
    Route::delete('/{dataId}', [CategoryController::class, 'delete'])->middleware('adminRole');
});

Route::group(['prefix' => 'order', 'middleware' => 'userRole'],function () {
    Route::get('/', [OrderController::class, 'userOrders']);
    Route::get('/{dataId}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::delete('/{dataId}', [OrderController::class, 'delete']);
    Route::delete('/cancel/{orderId}', [OrderController::class, 'cancelOrder']);
});

Route::get('admin/order', [OrderController::class, 'index'])->middleware('adminRole');

Route::group(['prefix' => 'product'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{dataId}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store'])->middleware('moderatorRole');
    Route::put('/{dataId}', [ProductController::class, 'update'])->middleware('moderatorRole');
    Route::delete('/{dataId}', [ProductController::class, 'delete'])->middleware('adminRole');
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class ,'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class ,'me']);

});

Route::post('register', [\App\Http\Controllers\RegisterController::class, 'register']);
