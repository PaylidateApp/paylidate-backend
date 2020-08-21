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
            $table->bigInteger('product_id');
            $table->bigInteger('payment_id');
            $table->string('status')->nullable();
            $table->decimal('amount')->nullable()->default(0.00);
            $table->decimal('wallet_befor')->nullable()->default(0.00);
            $table->decimal('amount_due')->nullable()->default(0.00);
            $table->decimal('wallet_after')->nullable()->default(0.00);
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
