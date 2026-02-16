<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = [
        'user_id', 'product_id', 'vendor_id', 'review_key','rating', 'comment','is_active'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function reviewImages()
    {
        return $this->hasMany(ReviewImage::class,'review_key','review_key');
    }
}
