<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factory;

class Product extends Model
{
    use HasSlug;

    protected $fillable = [
        'user_id', 'name', 'image', 'product_number', 'referral_amount', 'slug', 'price', 'quantity', 'type', 'transaction_type',
        'product_status', 'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    // public function secondary_user()
    // {
    //     return $this->belongsTo('App\User', 'user_id');
    // }

    public function transaction()
    {
        return $this->hasMany('App\Transaction');
    }

    public function recommendation()
    {
        return $this->hasMany('App\Recommendation');
    }
}
