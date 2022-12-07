<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'user_id' => $this->faker->name(),
        'user_id' => rand(1, 35),
        'transaction_id' => $this->faker->unique()->string(),
        'payment_ref' => $this->faker->unique()->string().'P_REF'.'Goodt_Guy',
        'payment_id' => $this->faker->unique()->string().'P_ID'.'Badt_Guy', // password
        'verified' => true,
        'balance_after' => 200,
        // 'balance_before' => Str::random(10),

    ];
});
