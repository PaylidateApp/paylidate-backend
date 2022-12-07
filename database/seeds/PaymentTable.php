<?php

use App\Payment;
use Illuminate\Database\Seeder;

class PaymentTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payment::factory()->time(50)->create();
    }
}
