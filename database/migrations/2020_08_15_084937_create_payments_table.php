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
            $table->bigInteger('user_id');
            $table->string('transaction_id')->nullable(); //from transaction table
            $table->string('payment_ref')->nullable();
            $table->bigInteger('payment_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('verified')->default(false);
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
