<?php

namespace App\Providers;

use App\Models\dashboard\patient\PatientInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        Validator::extend('unique_patient_phone', function ($attribute, $value, $parameters, $validator) {
//            $patientName = $validator->getData()['patient_name'];
//            $existingPatient = PatientInfo::where('patient_name', $patientName)
//                ->where('patient_phone', $value)
//                ->exists();
//
//            return !$existingPatient;
//        });
//
//        Validator::replacer('unique_patient_phone', function ($message, $attribute, $rule, $parameters) {
//            return str_replace(':patient_name', $parameters[0], $message);
//        });
    }
}
