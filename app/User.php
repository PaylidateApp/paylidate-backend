<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_token', 'password', 'phone', 'avatar', 'active', 'is_admin', 'is_staff', 'referral_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_token'
    ];

        public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function instantpay()
    {
        return $this->hasOne('App\Instandpay');
    }
    public function wallet()
    {
        return $this->hasOne('App\Wallet');
    }

    public function dispute()
    {
        return $this->hasOne('App\Dispute');
    }
    public function dispute_chat()
    {
        return $this->hasMany('App\DisputeChat');
    }

    public function account()
    {
        return $this->hasOne('App\UserAccount');
    }
    public function bank()
    {
        return $this->hasOne('App\Bank');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function refund()
    {
        return $this->hasMany('App\Refund');
    }
    public function withdrawal()
    {
        return $this->hasMany('App\Withdrawal');
    }

    public function referral_bonus_withdrawal()
    {
        return $this->hasMany('App\ReferralWidrawal');
    }

    public function referral()
    {
        return $this->hasMany('App\Referer');
    }
}
