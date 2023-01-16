<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'transaction_ref', 'status',
        'amount', 'accept_transaction', 'dispute', 'description', 'referer_id'
    ];

    public function secondary_user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // public function payment()
    // {
    //     return $this->hasOne('App\Payment');
    // }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function referral()
    {
        return $this->belongsTo('App\Referer');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment');
    }

    public function dispute_chat()
    {
        return $this->hasMany('App\DisputeChat');
    }

    public function dispute()
    {
        return $this->hasMany('App\Dispute');
    }

    public function withdrawal()
    {
        return $this->hasOnce('App\Withdrawal');
    }
    public function refund()
    {
        return $this->hasOnce('App\Refund');
    }
    public function fulfilment()
    {
        return $this->hasOne('App\Fulfilment');
    }
}
