<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fulfilment extends Model
{
    protected $fillable = [
        'user_id', 'transaction_id', 'transaction_ref', 'code'
    ];

    const PENDING = 0;
    const SUCCESSFUL = 1;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function payment()
    {
        return $this->hasOne('App\Payment');
    }

    public function product()
    {
        return $this->hasOne('App\Product');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
