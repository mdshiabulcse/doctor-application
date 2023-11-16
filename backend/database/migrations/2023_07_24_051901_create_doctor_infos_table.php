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
        Schema::create('doctor_infos', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id')->nullable();
            $table->string('doctor_name');
            $table->string('doctor_id');
            $table->decimal('doctor_fees', 8,2)->nullable();
            $table->string('dr_phone')->nullable();
            $table->string('dr_type');
            $table->string('hospital_name')->nullable();
            $table->longText('doctor_details')->nullable();
            $table->integer('user_id');
            $table->string('ip_address')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('doctor_infos');
    }
};
