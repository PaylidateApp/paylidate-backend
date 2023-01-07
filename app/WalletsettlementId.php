<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletsettlementId extends Model
{
    protected $fillable = [
        'wallet_id', 'settlementId'
    ];

    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }
}
