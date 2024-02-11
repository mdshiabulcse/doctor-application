<?php

namespace App\Models\dashboard\patient;

use App\Models\dashboard\DoctorInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPrescription extends Model
{
    use HasFactory;
    public function patient_info(){
        return $this->belongsTo(PatientInfo::class, 'patient_id','patient_id');
    }
    public function doctor_info(){
        return $this->belongsTo(DoctorInfo::class, 'doctor_id');
    }


}
