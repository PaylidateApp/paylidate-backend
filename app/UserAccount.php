<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
