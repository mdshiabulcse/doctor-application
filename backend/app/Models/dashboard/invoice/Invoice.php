<?php

namespace App\Models\dashboard\invoice;

use App\Models\dashboard\patient\PatientInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public function patient_info(){
        return $this->belongsTo(PatientInfo::class, 'patient_id','patient_id');
    }
    public function user_info(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
