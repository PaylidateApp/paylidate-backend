<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
       'user_id','product_id','payment_ref','transaction_id','transaction_ref','status','description'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

   
}
