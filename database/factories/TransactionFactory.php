<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 4),
        'product_id' => rand(1, 50),
        'quantity' => rand(1, 2),
        'transaction_ref' => $faker->word(),
        'referer_id' => null,
        'description' => $faker->word(),
        'accept_transaction' => true,
    ];
});
