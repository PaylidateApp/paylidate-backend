<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    protected $fillable = [
        'user_id','bank_name','account_number','bank_code','account_name','branch_name'
    ];
}
