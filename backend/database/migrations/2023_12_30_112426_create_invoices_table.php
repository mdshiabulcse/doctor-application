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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id',60);
            $table->string('invoice_type',50)->comment('Consultation,Investigation,Pathology,Operation');
            $table->string('patient_id',50);
            $table->integer('dr_id')->nullable();
            $table->integer('ref_dr_id')->nullable();
            $table->decimal('invoice_total_amount',9,2)->comment('Invoice Total Amount');
            $table->decimal('subtotal_amount',9,2)->comment('Invoice Subtotal Amount');
            $table->decimal('total_discount_amount',9,2)->default(0)->comment('Invoice Total Discount Amount');
            $table->integer('discount')->default(0)->comment('Invoice  Discount %');
            $table->decimal('paid_amount',9,2)->comment('Invoice  Paid Amount');
            $table->decimal('received_amount',9,2)->comment('Invoice  Received Amount');
            $table->decimal('due_amount',9,2)->default(0)->comment('Invoice  Due Amount');
            $table->string('pay_method',50);
            $table->string('status',20)->comment('Invoice  Status: Paid, Due, Void');
            $table->date('inv_create')->comment('Invoice Create Date');
            $table->tinyInteger('user_id')->comment('create user ID');
            $table->longText('create_user_device_info')->comment('Create user device information');
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
        Schema::dropIfExists('invoices');
    }
};
