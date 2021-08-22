<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Wallet;
use App\VirtualCard;

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

        $user_virtual_card = new VirtualCard;

        // create NGN virtual card for transactions
        $virtual_card = $user_virtual_card->virtualCard($currency = 'NGN', $ammount = '150', $name = 'Paylidate');
        $naira_card_id = null;

        if ($virtual_card['status'] == 'success') {
            $naira_card_id = $virtual_card['data']['id'];
            VirtualCard::create([
                'user_id' => 1,
                'card_id' => $virtual_card['data']['id'],
                'account_id' => $virtual_card['data']['account_id'],
                'currency' => $virtual_card['data']['currency'],
                'default' => 1,
            ]);
        }else {
            // Mail::raw($virtual_card['message'], function ($message) {
        }

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

         // create NGN virtual card for transactions
         $virtual_card = $user_virtual_card->virtualCard($currency = 'NGN', $ammount = '150', $name = 'Paylidate Staff');
         $naira_card_id = null;

         if ($virtual_card['status'] == 'success') {
             $naira_card_id = $virtual_card['data']['id'];
             VirtualCard::create([
                 'user_id' => 2,
                 'card_id' => $virtual_card['data']['id'],
                 'account_id' => $virtual_card['data']['account_id'],
                 'currency' => $virtual_card['data']['currency'],
                 'default' => 1,
             ]);
         }else {
             // Mail::raw($virtual_card['message'], function ($message) {
         }
    }
}
