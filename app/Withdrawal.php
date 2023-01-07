<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{

    protected $fillable = [
        'user_id','payment_id','transaction_id','bank_id','narration',
        'debit_currency','f_withdrawal_id', 'status'
     ];

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
    
    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
