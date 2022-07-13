<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referer extends Model
{
    protected $fillable = [
        'user_id','amount'
    ];

    public function transaction()
    {
        return $this->hasOne('App\Transaction');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
