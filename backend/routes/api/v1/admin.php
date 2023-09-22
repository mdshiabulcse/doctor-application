<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\dashboard\DoctorsController;
use App\Http\Controllers\Api\User\UserController;




Route::controller(AdminAuthController::class)->group(function (){
    Route::post('/login','login');
    Route::post('/register','register');
});

Route::middleware('auth:admin-api')->group(function () {
    Route::controller(AdminAuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'user');
    });


});

Route::resource('doctor-data',DoctorsController::class);
Route::resource('user-data',UserController::class);
