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
            $table->bigInteger('user_id')->nullable(); // transaction acceptor/decliner user_id
            $table->bigInteger('product_id');
            $table->integer('quantity')->unsigned()->nullable()->default(0); // total quantity purchased
            $table->string('transaction_ref')->nullable(); //Auto generate
            $table->integer('status')->unsigned()->default(0); // 0=pending, 1=completed, 2=failed or canceled
            $table->boolean('accept_transaction')->nullable(); // accepting a transation
            $table->boolean('dispute')->nullable()->default(false);
            $table->decimal('amount')->nullable()->default(0.00); // total amount           
            $table->longText('description')->nullable();
            $table->timestamp('transaction_reported_at')->useCurrent();
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
