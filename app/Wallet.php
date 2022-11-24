<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'order_ref', 'tx_ref', 'account_number', 'balance', 'bonus', 'bank_name'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function wallet_settlement()
    {
        return $this->hasMany('App\WalletsettlementId');
    }

    public function wallet_history()
    {
        return $this->hasMany('App\WalletHistory');
    }
}
