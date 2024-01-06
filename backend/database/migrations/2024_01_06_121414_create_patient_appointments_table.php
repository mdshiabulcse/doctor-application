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
        Schema::create('patient_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->integer('doctor_id');
            $table->integer('appointment_sl');
            $table->date('appointment_date');
            $table->date('create_date');
            $table->tinyInteger('user_id');
            $table->tinyInteger('cancel_user_id')->nullable();
            $table->string('cancel_note')->nullable();
            $table->string('status')->comment('Cancel,Pending,Waiting,Done');
            $table->longText('ip_details')->comment('User Device IP and Other Information');
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
        Schema::dropIfExists('patient_appointments');
    }
};
