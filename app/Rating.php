<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'rated_user_id', 'rating'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function rated_user(){
        return $this->belongsTo(User::class,'rated_user_id');
    }
}
