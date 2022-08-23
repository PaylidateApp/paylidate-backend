<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisputeChat extends Model
{
    protected $fillable = [
        'user_id','transaction_id','message', 'dispute_id'
     ];

    public function user()
    {
        return $this->hasOne('App\User');
    }
    public function dispute()
    {
        return $this->hasOne('App\Dispute');
    }
    public function transaction()
    {
        return $this->hasOne('App\Transaction');
    }
}
