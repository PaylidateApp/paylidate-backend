<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('transaction_id')->constrained();
            $table->foreignId('payment_id')->constrained();
            $table->foreignId('bank_id')->constrained();
            $table->string('narration')->nullable();          
            $table->string('debit_currency')->default('NGN');
            $table->bigInteger('f_withdrawal_id')->nullable(); // transfer id from fluuterwave
            $table->boolean('status')->nullable()->default(false); // true for withdrawal complete. false for withdrawal not complete
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
        Schema::dropIfExists('refunds');
    }
}
