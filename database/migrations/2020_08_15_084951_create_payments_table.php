<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // buyer user_id
            $table->bigInteger('transaction_id')->nullable(); //from transaction table
            $table->string('payment_ref')->nullable(); // from flutterwave
            $table->string('payment_id')->nullable(); // from flutterwave
            $table->string('payment_method')->default('flutterwave');
            $table->string('currency')->default('NGN');
            $table->boolean('verified')->default(false);
            $table->longText('description')->nullable();
            $table->decimal('balance_before')->nullable()->default(0.00);
            $table->decimal('balance_after')->nullable()->default(0.00);
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
        Schema::dropIfExists('payments');
    }
}
