<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id',60);
            $table->string('patient_id',60);
            $table->string('invoice_type',60)->comment('Consultation,Investigation,Pathology,Operation');
            $table->decimal('invoice_pay_amount',10,2);
            $table->tinyInteger('invoice_pay_status')->comment('1=Paid,2=Refund,3=Void,4=Due');
            $table->date('create_date');
            $table->tinyInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};
