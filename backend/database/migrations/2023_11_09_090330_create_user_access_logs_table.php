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
        Schema::create('user_access_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('device_info')->nullable();
            $table->string('os_platform')->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('login_time')->nullable();
            $table->dateTime('logout_time')->nullable();
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
        Schema::dropIfExists('user_access_logs');
    }
};
