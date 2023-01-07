<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'user_id', 'bank_name', 'account_number', 'bank_code', 'account_name', 'branch_name'
    ];
    protected $hidden = ['created_at', 'updated_at',  'user_id', 'branch_name'];

    public function withdrawal()
    {
        return $this->hasMany('App\Withdrawal');
    }
    public function refund()
    {
        return $this->hasMany('App\Refund');
    }
    public function referral_bonus_withdrawal()
    {
        return $this->hasMany('App\ReferralWidrawal');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
