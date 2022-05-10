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
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable()->default('default_product.png');
            $table->string('product_number')->nullable();
            $table->double('price', 15, 8)->nullable()->default(0.00);
            $table->integer('quantity')->unsigned()->nullable()->default(0);
            $table->string('type')->nullable();// product, or service
            $table->boolean('payment_status')->nullable()->default(true);//true for available, true for not available       
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
