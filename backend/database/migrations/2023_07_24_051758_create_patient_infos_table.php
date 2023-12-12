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
        Schema::create('patient_infos', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('patient_name');
            $table->string('patient_phone');
            $table->string('patient_email')->nullable();
            $table->string('patient_address')->nullable();
            $table->string('patient_father')->nullable();
            $table->string('patient_mother')->nullable();
            $table->string('hospital_name')->nullable();
            $table->string('gender');
            $table->string('patient_dob')->nullable();
            $table->date('patient_age')->nullable();
            $table->integer('user_id');
            $table->string('ip_address')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('patient_infos');
    }
};
