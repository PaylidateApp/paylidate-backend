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
            $table->bigInteger('user_id');
            $table->bigInteger('product_id')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_ref')->nullable();
            $table->string('status')->nullable();// pending, completed, failed
            $table->string('type')->nullable();// fund, payment, refund
            $table->boolean('verified')->default(false);// true if the transaction is verified
            $table->decimal('amount')->nullable()->default(0.00);
            $table->decimal('balance_befor')->nullable()->default(0.00);
            $table->decimal('balance_after')->nullable()->default(0.00);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
