<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instandpay extends Model
{
    protected $fillable = [
        'receiver_number', 'sender_email', 'account_number', 'link_token', 'tracking_id', 'account_name', 'bank_code', 'otp', 'amount', 'status', 'description', 'withdrawal_pin'
    ];
}
