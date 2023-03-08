<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_intend_id')->unique()->nullable();
            $table->string('payment_intent_client_secret')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->decimal('amount',12,2)->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['P','U'])->default('U')->comment('U=Unpaid,P=Paid');
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
        Schema::dropIfExists('transactions');
    }
}
