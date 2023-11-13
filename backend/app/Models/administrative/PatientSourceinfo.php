<?php

namespace App\Models\administrative;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSourceinfo extends Model
{
    use HasFactory;
    public function user_data(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
