<?php

namespace App\Models\dashboard\invoice;

use App\Models\dashboard\examination\ExaminationList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceInfo extends Model
{
    use HasFactory;
    public function exam_data(){
        return $this->belongsTo(ExaminationList::class, 'invoice_item_id');
    }
}
