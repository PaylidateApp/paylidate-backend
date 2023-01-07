<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiverIdToInstandpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instandpays', function (Blueprint $table) {
            if (!Schema::hasColumn('instandpay', 'receiver_id')) {
                $table->bigInteger('receiver_id')->nullable(); 
                $table->string('receiver_name')->nullable(); 
                $table->string('bank_name')->nullable(); 
                };
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instandpays', function (Blueprint $table) {
            //
        });
    }
}
