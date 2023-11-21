<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\dashboard\DoctorsController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\administrative\AdministrativeController;




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
Route::get('verified-status/{id}',[UserController::class,'isVerifiedChange']);
Route::group(['prefix' => 'administrative'], function (){
    Route::resource('patient-source',AdministrativeController::class);
    Route::get('change-status/{id}',[AdministrativeController::class,'statusChange']);
    Route::get('doctor-data',[AdministrativeController::class,'doctorData']);
    Route::post('save-doctor',[AdministrativeController::class,'saveDoctorData']);
});

