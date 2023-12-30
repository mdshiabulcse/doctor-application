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
        Schema::create('invoice_infos', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id',60);
            $table->string('invoice_type',60)->comment('Pathology,Consultation');
            $table->integer('invoice_item_id')->comment('Invoice Item Id');
            $table->decimal('invoice_item_amount',9,2)->comment('Invoice Item Unit Price');
            $table->tinyInteger('discount')->comment('Invoice Item Discount %');
            $table->integer('user_id');
            $table->date('inv_create');
            $table->string('status',20)->comment('Invoice  Status: Paid, Due, Void');
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
        Schema::dropIfExists('invoice_infos');
    }
};
