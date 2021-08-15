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

    function createVirtualAccount($email, $is_permanent, $name){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->post(env('FLW_BASE_URL').'/v3/virtual-account-numbers', [
            "email" => $email,
            "is_permanent" => $is_permanent,
            "tx_ref" => $name.'-'.time(),
            "narration" => $name,
        ]);

        return $response;
    }

    function getVirtualAccount($account_ref){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('FLW_SECRET_KEY')
        ])->get(env('FLW_BASE_URL').'/v3/virtual-account-numbers/'. $account_ref);

        return $response;
    }
}
