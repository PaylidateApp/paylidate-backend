<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id','product_id','payment_id','status','amount','wallet_befor','amount_due','wallet_after','description'
    ];
}
