<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Wallet;

class UserTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Paylidate',
            'email' => 'paylidate@gmail.com',
            'password' =>  bcrypt('secret'),
            'is_admin' => true,
            'is_staff' => true
        ]);
        Wallet::create([
            'user_id' => 1,
            'wallet' => 200000
        ]);

        // ------------------------------------------------ //

        User::create([
            'name' => 'Paylidate Staff',
            'email' => 'staff@gmail.com',
            'password' =>  bcrypt('secret'),
            'is_staff' => true
        ]);
        Wallet::create([
            'user_id' => 2,
            'wallet' => 200000
        ]);
    }
}
