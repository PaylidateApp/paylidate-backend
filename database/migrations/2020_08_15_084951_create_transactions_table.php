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
            $table->string('transaction_id')->nullable(); // transaction_id from flutterwave
            $table->integer('quantity')->unsigned()->nullable()->default(0);
            $table->string('transaction_ref')->nullable(); // transaction_ref from flutterwave
            $table->string('status')->nullable();// pending, completed, failed
            $table->boolean('verified')->default(false);// true if the transaction is verified
            $table->boolean('dispute')->nullable()->default(false);
            $table->decimal('amount')->nullable()->default(0.00);            
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
