<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralWidrawal extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'bank_id', 'narration', 'debit_currency', 'f_withdrawal_id', 'status'
    ];


    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
