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
        'name', 'email', 'email_token', 'password','phone','avatar','active','is_admin','is_staff'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wallet()
    {
        return $this->hasOne('App\Wallet');
    }

    public function dispute()
    {
        return $this->hasOne('App\Dispute');
    }

    public function account()
    {
        return $this->hasOne('App\UserAccount');
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

    public function withdrawal()
    {
        return $this->hasMany('App\Withdrawal');
    }

}
