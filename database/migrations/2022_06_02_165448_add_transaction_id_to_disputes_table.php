<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionIdToDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disputes', function (Blueprint $table) {
            if (!Schema::hasColumn('disputes', 'subject')){
            
                    $table->string('subject')->after('id');
                   
                  };
            if (!Schema::hasColumn('disputes', 'transaction_id')){
            
                    $table->bigInteger('transaction_id')->after('dispute_solved');
                   
                  };

            if (!Schema::hasColumn('disputes', 'user_id')){
            
                    $table->bigInteger('user_id')->after('dispute_solved');
                   
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
        Schema::table('disputes', function (Blueprint $table) {
            //
        });
    }
}
