<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'type', 'amount', 'narration', 'balance_before', 'balance_after'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }
}
