<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            //$table->bigInteger('secondary_user_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable()->default('default_product.png');
            $table->string('product_number')->nullable();
            $table->double('price', 15, 8)->nullable()->default(0.00);
            $table->integer('quantity')->unsigned()->nullable()->default(0);
            $table->string('type')->nullable();// product, or service
            $table->string('transaction_type')->nullable();// buy, or sell
           // $table->boolean('confirmed')->nullable()->default(false);
           // $table->tinyInteger('delivery_status')->nullable()->default(0);//0 awaiting fulfillment, 1 in transit, 2 delivered, 3 recieved, 4 canceled
           // $table->tinyInteger('payment_status')->nullable()->default(0);//0 awaiting payment, 1 paid, 2 not paid
            //$table->tinyInteger('dispute')->nullable()->default(false);//0 no dispute, 1 dispute, 2 dispute_resolved
           // $table->integer('delivery_period')->nullable()->default(false);
           // $table->string('clients_email')->nullable()->default(false);
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
        Schema::dropIfExists('products');
    }
}
