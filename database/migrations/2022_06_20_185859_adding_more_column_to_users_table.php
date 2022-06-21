<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingMoreColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawals', 'f_withdrawal_id')){
                $table->bigInteger('f_withdrawal_id')->nullable()->after('debit_currency');
              };

              if (!Schema::hasColumn('withdrawals', 'status')){
                $table->boolean('status')->nullable()->default(false)->after('debit_currency'); // true for withdrawal complete. false for withdrawal not complete
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
        Schema::table('withdrawals', function (Blueprint $table) {
            //
        });
    }
}
