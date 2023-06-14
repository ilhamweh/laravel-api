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
// Route::apiResource('/orders', App\Http\Controllers\Api\OrderController::class);
Route::get('/orders/activeorders','App\Http\Controllers\Api\OrderController@active_orders');
Route::get('/orders/activeorderstore/{store_id}','App\Http\Controllers\Api\OrderController@order_by_store');
Route::get('/orders/orderdet/{mk}','App\Http\Controllers\Api\OrderController@get_det_item_by_mk');
Route::get('/orders/notifpanel/{store_id}','App\Http\Controllers\Api\OrderController@notif_panel');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
