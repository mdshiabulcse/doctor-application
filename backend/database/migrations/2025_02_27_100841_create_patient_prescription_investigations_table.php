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
        Schema::create('patient_prescription_investigations', function (Blueprint $table) {
            $table->id();
            $table->integer('prescription_id');
            $table->string('patient_id');
            $table->integer('investigation_id')->comment('Investigation List Foreign Key');
            $table->string('type',60)->comment('Investigation type');
            $table->string('eye_type',60)->nullable();
            $table->longText('investigation_details')->nullable();
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
        Schema::dropIfExists('patient_prescription_investigations');
    }
};
