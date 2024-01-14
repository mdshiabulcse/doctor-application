<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\print\PrintController;
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



Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');

    return "Cache cleared successfully";
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return send_ms('User Unauthenticated',false, 401);
})->name('login');

Route::resource('print-data',\App\Http\Controllers\Api\User\UserController::class);
Route::group(['prefix'=> 'print'],function (){
    Route::get('ex-invoice-print/{inv_id}',[PrintController::class, 'exInvoicePrint']);
    Route::get('consultation-invoice-print/{inv_id}',[PrintController::class, 'consultationInvoicePrint']);
});

