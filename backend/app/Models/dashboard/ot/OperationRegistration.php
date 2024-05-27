<?php

namespace App\Models\dashboard\ot;

use App\Models\dashboard\DoctorInfo;
use App\Models\dashboard\patient\PatientInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationRegistration extends Model
{
    use HasFactory;

    public function patient_info(){
        return $this->belongsTo(PatientInfo::class, 'patient_id','patient_id');
    }
    public function user_info(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function doctor_info(){
        return $this->belongsTo(DoctorInfo::class, 'doctor_id');
    }
    public function operation_info(){
        return $this->belongsTo(OperationList::class, 'operation_id');
    }
}
