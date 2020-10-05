<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
       'user_id','product_id','quantity','type','status','expires'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
