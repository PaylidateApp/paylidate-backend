<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id','product_id','type','payment_ref','transaction_id',
        'transaction_ref','status','verified','amount','balance_befor',
        'balance_after','description'
    ];
}
