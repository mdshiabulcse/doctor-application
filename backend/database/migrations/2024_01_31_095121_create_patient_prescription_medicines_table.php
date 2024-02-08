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
        Schema::create('patient_prescription_medicines', function (Blueprint $table) {
            $table->id();
            $table->integer('prescription_id');
            $table->string('patient_id');
            $table->integer('medicine_id',50)->nullable();
            $table->string('type',50)->nullable();
            $table->string('duration')->nullable();
            $table->longText('medicine_instruction')->nullable();
            $table->integer('status')->default(1)->comment('1=Active,0=Inactive');
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
        Schema::dropIfExists('patient_prescription_medicines');
    }
};
