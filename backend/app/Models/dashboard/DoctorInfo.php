<?php

namespace App\Models\dashboard;

use App\Models\dashboard\patient\PatientPrescription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorInfo extends Model
{
    use HasFactory;

    public function prescriptions()
    {
        return $this->hasMany(PatientPrescription::class);
    }
}
