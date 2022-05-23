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
            $table->bigInteger('secondary_user_id')->nullable(); // secondary user
            $table->string('seller_email')->nullable(); // this is use for a seller
            $table->bigInteger('product_id'); 
           // $table->string('transaction_id')->nullable(); // transaction_id from flutterwave
            $table->integer('quantity')->unsigned()->nullable()->default(0); // total quantity purchased
            $table->string('transaction_ref')->nullable(); //Auto generate
            $table->integer('status')->unsigned()->default(0);// 0=pending, 1=completed, 2=failed
            //$table->boolean('verified')->default(false);// true if the transaction is verified and complete
            $table->boolean('accept_transaction')->nullable(); // accpting a transation
            $table->boolean('dispute')->nullable()->default(false);
            $table->decimal('amount')->nullable()->default(0.00); // total amount           
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
