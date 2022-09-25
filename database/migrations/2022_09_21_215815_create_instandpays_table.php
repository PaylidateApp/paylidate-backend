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
            $table->string('tracking_id'); 
            $table->decimal('amount')->default(0.00);         
            $table->integer('receiver_number'); 
            $table->string('sender_email'); 
            $table->string('sender_name'); 
            $table->integer('otp'); 
            $table->string('link_token'); 
            $table->integer('bank_code'); 
            $table->string('account_name'); 
            $table->integer('account_number'); 
            $table->integer('withdrawal_pin')->default(000); 
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
