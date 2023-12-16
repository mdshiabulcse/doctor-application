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
            $table->date('patient_dob');
            $table->integer('patient_age');
            $table->integer('user_id');
            $table->longText('ip_address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->date('create_date');
            $table->integer('update_user_id')->nullable();
            $table->date('update_date')->nullable();
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
