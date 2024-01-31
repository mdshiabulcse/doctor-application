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
        Schema::create('medicine_data', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_name');
            $table->string('generic')->nullable();
            $table->string('type')->nullable()->comment('Tab,Cap,Drop,Syrup,Injection');
            $table->string('brand')->nullable();
            $table->string('vendor')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active,0=Inactive');
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
        Schema::dropIfExists('medicine_data');
    }
};
