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
        Schema::create('operation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_of_type')->comment('Operation Type Name');
            $table->string('details_of_type')->comment('Operation Type Details')->nullable();
            $table->tinyInteger('status')->default(1)->comment('Operation Type Status');
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
        Schema::dropIfExists('operation_types');
    }
};
