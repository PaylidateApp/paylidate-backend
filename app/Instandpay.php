<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instandpay extends Model
{
    protected $fillable = [
        'user_id', 'receiver_number', 'sender_email', 'sender_name', 'account_number', 'link_token', 'payment_ref', 'tracking_id', 'account_name', 'bank_code', 'withdrawal_pin', 'amount', 'status', 'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
