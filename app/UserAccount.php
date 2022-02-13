<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class UserAccount extends Model
{
    protected $fillable = [
        'user_id','ref'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
