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
        Schema::create('examination_lists', function (Blueprint $table) {
            $table->id();
            $table->string('ex_name')->comment('examination name');
            $table->string('ex_type')->comment('pathology');
            $table->string('ex_details')->nullable();
            $table->decimal('price',9,2);
            $table->integer('discount')->default(0);
            $table->string('price_type')->comment('fixed,flexible');
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
        Schema::dropIfExists('examination_lists');
    }
};
