<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount')->default(0.00); // total referral amount for a transaction           
            $table->boolean('withdrawal_status')->default(false); // 0= request pendding, 1= withdrawal complete
            $table->boolean('transaction_status')->default(false); // false= not complete, true= for complete
            $table->boolean('transfer_status')->default(false); // false= not complete, true= for complete
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
        Schema::dropIfExists('referers');
    }
}
