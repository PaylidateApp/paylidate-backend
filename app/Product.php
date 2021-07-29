<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasSlug;

    protected $fillable = [
        'user_id','name','image','product_number','price','quantity','type','description','confirmed',
        'delivery_status','payment_status','dispute','delivery_period','clients_email','description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
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


    public function payment()
    {
        return $this->hasOne('App\Payment');
    }

    public function secondary_user()
    {
        return $this->belongsTo('App\User', 'secondary_user_id');
    }

}
