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
        Schema::create('branch_infos', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('branch_name');
            $table->string('branch_phone');
            $table->string('branch_email')->nullable();
            $table->string('country')->nullable();
            $table->longText('branch_details')->nullable();
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
        Schema::dropIfExists('branch_infos');
    }
};
