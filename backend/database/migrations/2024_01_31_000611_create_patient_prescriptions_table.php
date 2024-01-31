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
        Schema::create('patient_prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->longText('symptoms')->nullable();
            $table->longText('advice_note')->nullable();
            $table->date('followup_date')->nullable();
            $table->date('create_date');
            $table->integer('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->longText('ip_address')->comment('creator device and ip information');
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
        Schema::dropIfExists('patient_prescriptions');
    }
};
