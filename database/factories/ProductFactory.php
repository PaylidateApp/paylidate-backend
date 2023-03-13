<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => 'product_'.rand(1, 50),
        'user_id' => rand(1, 4),
        'price' => rand(200, 890),
        'quantity' => rand(1, 7),
        'product_number' => rand(0000000, 1111111)
    ];
});
