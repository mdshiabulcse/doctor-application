<?php

namespace App\Models\dashboard\ot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationList extends Model
{
    use HasFactory;
    public function operation_type()
    {
        return $this->hasMany(OperationType::class,'operation_type_id');
    }

    public function operation_categories()
    {
        return $this->hasMany(OperationCategory::class,'operation_category_id');
    }
}
