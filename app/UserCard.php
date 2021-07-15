<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $fillable = [
        'user_id','first_6digits','last_4digits','issuer','country','type','token','expiry'
    ];
}
