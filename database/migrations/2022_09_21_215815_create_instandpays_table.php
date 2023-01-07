<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstandpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instandpays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('tracking_id'); 
            $table->decimal('amount')->default(0.00);         
            $table->string('receiver_number'); 
            $table->string('sender_email'); 
            $table->string('sender_name'); 
            $table->string('link_token'); 
            $table->string('payment_ref'); 
            $table->string('bank_code')->nullable(); 
            $table->string('account_name')->nullable(); 
            $table->string('account_number')->nullable(); 
            $table->string('withdrawal_pin')->default(000); 
            $table->boolean('status')->default(false);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('instandpays');
    }
}
