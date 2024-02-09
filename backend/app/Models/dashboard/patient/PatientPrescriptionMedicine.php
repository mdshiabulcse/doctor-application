<?php

namespace App\Models\dashboard\patient;


use App\Models\dashboard\prescription\MedicineData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPrescriptionMedicine extends Model
{
    use HasFactory;
    public function prescription_medicine(){
        return $this->belongsTo(MedicineData::class, 'medicine_id');
    }
}
