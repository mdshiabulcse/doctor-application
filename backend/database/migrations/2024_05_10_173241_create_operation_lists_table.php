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
        Schema::create('operation_lists', function (Blueprint $table) {
            $table->id();
            $table->string('operation_name');
            $table->string('operation_sub_head')->nullable();
            $table->string('operation_procedure_details')->nullable();
            $table->integer('operation_category_id')->comment('Operation Categories Table ID');
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
        Schema::dropIfExists('operation_lists');
    }
};
