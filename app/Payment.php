<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
       'user_id','product_id','payment_id','transaction_id','payment_ref','status', 'refund','description',
       'payment_method','currency','verified','balance_before','balance_after'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function withdrawal()
    {
        return $this->hasOne('App\Withdrawal');
    }
    public function refund()
    {
        return $this->hasOne('App\Refund');
    }

}
