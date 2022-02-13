<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class VirtualCard extends Model
{
    protected $fillable = [
        'user_id','card_id','account_id','currency','label','default'
    ];

    
}
