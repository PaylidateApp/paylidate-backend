<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'account_name', 'account_number', 'balance', 'bonus'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function wallet_settlement()
    {
        return $this->hasMany('App\WalletsettlementId');
    }
}
