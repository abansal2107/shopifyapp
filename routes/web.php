<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getaccesstoken', [OrderController::class, 'getaccesstoken']);
Route::get('/createorder', [OrderController::class, 'createOrder']);
Route::get('/refundorder', [OrderController::class, 'refundOrder']);
Route::post('/shopify-orders-webhook-test', [OrderController::class, 'webhooks_recived_order']);
Route::post('/shopify-refund-webhook-test', [OrderController::class, 'webhooks_recieved_refund']);