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
        Schema::create('operation_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number',60);
            $table->string('patient_id',60);
            $table->integer('operation_id');
            $table->string('operation_subhead')->nullable();
            $table->integer('operation_package_id')->nullable();
            $table->date('operation_date');
            $table->time('operation_time')->nullable();
            $table->decimal('ot_amount',9,2);
            $table->decimal('ot_amount_paid',9,2)->nullable();
            $table->integer('doctor_id');
            $table->integer('refer_doctor_id')->nullable();
            $table->string('ot_description')->nullable();
            $table->string('ot_cancel_comment')->nullable();
            $table->tinyInteger('ot_cancel_user_id')->nullable();
            $table->string('status')->comment('Active,Registration,Done,Cancel');
            $table->string('ip_information');
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
        Schema::dropIfExists('operation_registrations');
    }
};
