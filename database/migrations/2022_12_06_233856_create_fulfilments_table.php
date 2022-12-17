<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFulfilmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fulfilments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('transaction_id');
            $table->string('transaction_ref');
            $table->unsignedBigInteger('code');
            $table->integer('status')->default(0); // 0 = Pending; 1 = complete;
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
        Schema::dropIfExists('fulfilments');
    }
}
