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
        Schema::create('investigation_lists', function (Blueprint $table) {
            $table->id();
            $table->string('inv_name')->comment('Investigation name');
            $table->string('type');
            $table->string('description')->nullable();
            $table->decimal('bill_amount',9,2);
            $table->integer('discount')->default(0);
            $table->string('bill_type')->comment('fixed,flexible');
            $table->integer('user_id');
            $table->tinyInteger('status')->default(0)->comment('1=Active,0=Inactive');
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
        Schema::dropIfExists('investigation_lists');
    }
};
