<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\dashboard\DoctorsController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\administrative\AdministrativeController;
use App\Http\Controllers\Api\patients\PatientsController;
use App\Http\Controllers\Api\invoice\ExaminationInvoiceController;
use App\Http\Controllers\Api\appointment\AppointmentController;
use App\Http\Controllers\Api\invoice\InvoiceController;
use App\Http\Controllers\Api\invoice\ConsultationInvoiceController;
use App\Http\Controllers\Api\prescription\PrescriptionController;




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

Route::group(['prefix' => 'patients'], function (){
    Route::resource('patients',PatientsController::class);
    Route::get('patient-sources',[PatientsController::class,'patientSources']);
});
Route::group(['prefix' => 'appointment'], function (){
    Route::get('appointment-setting',[AppointmentController::class,'appointmentSetting']);
    Route::get('appointment-booking-patient/{id}',[AppointmentController::class,'appointmentBookingPatient']);
    Route::resource('appointment-data',AppointmentController::class);
    Route::get('patient-appointment-info/{id}',[AppointmentController::class,'patientAppointmentInfo']);
});
Route::group(['prefix' => 'invoice'], function (){
    Route::resource('examination-invoice',ExaminationInvoiceController::class);
    Route::get('doctor-data',[ExaminationInvoiceController::class,'doctorData']);
    Route::get('examination-list',[ExaminationInvoiceController::class,'examinationList']);
    Route::get('discount-list',[ExaminationInvoiceController::class,'discountList']);
    Route::resource('invoice',InvoiceController::class);
    Route::get('user-data',[InvoiceController::class,'user_info']);
    Route::resource('consultation-invoice',ConsultationInvoiceController::class);
});
Route::group(['prefix' => 'prescription'], function (){
    Route::get('medicine-data',[PrescriptionController::class,'getMedicineInPrescription']);
    Route::resource('prescription',PrescriptionController::class);
    Route::post('prescription-update',[PrescriptionController::class,'prescriptionUpdate']);
});


