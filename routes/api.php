<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\Permissions\RolesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProductsController;
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
    return ['message' => 'Hello World'];
});

Route::prefix('category')->group(function () {
        Route::post('restore-Category', [\App\Http\Controllers\CategoriesController::class, 'restoreCategory']);
        Route::post('store-Category', [\App\Http\Controllers\CategoriesController::class, 'store']);
        Route::put('update-Category/{category}', [\App\Http\Controllers\CategoriesController::class, 'update']);
        Route::get('categories', [\App\Http\Controllers\CategoriesController::class, 'index']);
        Route::delete('delete-Category/{id}', [\App\Http\Controllers\CategoriesController::class, 'delete']);
        Route::get('products-by-Category/{id}', [\App\Http\Controllers\CategoriesController::class, 'productsByCategory']);
        Route::get('get-info', [\App\Http\Controllers\CategoriesController::class, 'getInfo']);
});

Route::group(['prefix' => 'order', 'middleware' => 'userRole'],function () {
    Route::get('/', [OrdersController::class, 'index']);
    Route::get('/{dataId}', [OrdersController::class, 'dataById']);
    Route::post('/', [OrdersController::class, 'store']);
    Route::put('/{dataId}', [OrdersController::class, 'update']);
    Route::delete('/{dataId}', [OrdersController::class, 'delete']);
});

Route::group(['prefix' => 'user/product', 'middleware' => 'userRole'], function () {
    Route::get('/', [ProductsController::class, 'index']);
    Route::get('/{dataId}', [ProductsController::class, 'dataById']);
});

Route::group(['prefix' => 'admin/product', 'middleware' => 'adminRole'], function () {
    Route::get('/', [ProductsController::class, 'index']);
    Route::get('/{dataId}', [ProductsController::class, 'dataById']);
    Route::post('/', [ProductsController::class, 'store']);
    Route::put('/{dataId}', [ProductsController::class, 'update']);
    Route::delete('/{dataId}', [ProductsController::class, 'delete']);
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
